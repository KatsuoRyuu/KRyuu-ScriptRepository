<?php
namespace ScriptRepository\View\Helper;
/** 
 * @note This modules is made for development, and was not meant to be used in production, even thou a lot of
 * performance boost options has been made(I'm still testing to see what performance hit it has).
 * 
 * The lower TTL you have the bigger the performance hit will be when using cache.
 * again the cache will be able to save you for a lot when active(bear in mind im still testing the 
 * way im using the cache it might be bad).
 * 
 * @todo Add support for auto downloading the scripts form the net, support for net positions of the scripts
 * so that you can use the fx. //netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js as a link.
 * Alternative dependency tree storage, like array or in file, and maybe even memory cache.
 * @package ScriptRepository
 * @author Anders Blenstrup-Pedersen <anders-github@drake-development.org>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.1 (2014-04-04)
 * @link https://github.com/KatsuoRyuu/KRyuu-ScriptRepository
 */

use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;
use ScriptRepository\Service\Minimizer;
use ScriptRepository\Entity\Script as Script;

class ScriptHelper extends AbstractHelper
{
    /**
     * parshing the default configuration to the module.
     */
    
    /**
     * entityManager for the Doctrine2 ORM framework
     * @var type DoctrineEntityManager
     */
    private $entityManager=null;    
    
    /**
     * Array of the scripts needed by the system.
     * This array is the base search array of the repository.
     * @var type Array
     */
    private $scripts=array();    
    
    /**
     * Array of the load order of the scripts.
     * This load order has no dublicated script entry, and is the order of loading
     * @var type Array()
     */
    private $scriptLoadOrder;
    
    /**
     * This is the place of where the scripts should reside.
     * Default placement is using __DIR__ and can't be entered in the define part of
     * the object, please refere to the constructor.
     * @var type String
     */
    private $scriptDriectory='';
    
    /**
     * public path for the script cache folder.
     * This path is for the cached script files. this is used for generating the 
     * public path way of the cache folder 
     * Example:
     *      <script src="/cache/js/script.js"></script>
     * @var type String
     */
    private $publicCacheFolder="/cache/";
    
    /**
     * public script path.
     * This path is for the public scripts, and can be found from the web.
     * @var type 
     */
    private $publicScriptFolder="/scripts/";
    
    /**
     * Choose if the script repository should use a merged caching system.
     * @var type boolean
     */
    private $useCache=false;
    
    /**
     * System cache path.
     * This is differend from the cache path, it's the full path of the public 
     * cache directory. 
     * This is used when generating the merged cache files.
     * @var type String
     */
    private $cacheDirectory='/';
    
    /**
     * Choose to schrink the cached scripts by removing extraneous spaces and linebreaks.
     * @var type boolean
     */
    private $minimize=false;
        
    /**
     * how many seconds the cachefile should live before it needs to be updated.
     * be aware of changes to designs doesn't update before ttl has run out, even if you
     * make changes to the scripts (To improve performance).
     * At development please disable cache function.
     * @var type int
     */
    private $ttl=3600;
        
    /**
     * ADVANCED, DO NOT EDIT.
     * This is for parsing an overwrite on the auto caching system. 
     * please be aware of what you are doing here
     * @var type boolean
     */
    private $cached=false;
    
    /**
     * ADVANCED, DO NOT EDIT.
     * This is the cachefile name ans should be maintained by the auto cache function
     * cache filename generated from the URL path.
     * @var type string
     */
    private $cachefile=null;
    
    /**
     * No change on editing.
     * System var for the time right now, is used bu the ttl(time to live), cache updater.
     * @var type int
     */
    private $timeNow=0;
    
    /**
     * Choose to accept url addresses on script if avaiable.
     * @var type boolean
     */
    private $urlscript=false;
    
    /**
     * Choose to accept url addresses on script if avaiable.
     * @var type boolean
     */
    private $arrayRepo=true;
            
    /**
     * force using internel paths, scripts will be parsed thro PHP.
     * This might give you a big performance hit.
     * @var Boolean
     */
    private $useInternalPath=true;

    /**
     * The internal path for the Scripts.
     * Used when useInternalPath is true.
     * @var Boolean
     */
    private $internalPath= '';

    
    
    /**
     * Loading all the default 
     * 
     */
    public function __construct($serviceLocator)
    {
        $tmpConfig = $serviceLocator->get('config');
        $config = $tmpConfig[__NAMESPACE__]['configuration'];
        $this->staticDependencyTree = include __DIR__ .'/../../DependencyTree/DepTree.php';
        
        $this->scriptDriectory =        __DIR__ . '/../../../../../../../views/scripts/';
        $this->cacheDirectory =         __DIR__ . '/../../../../../../../public/cache/';
        
        // getting the configuration from the module.config.php
        
        $this->scripts =                $config['scripts'];
        $this->scriptLoadOrder =        $config['scriptLoadOrder'];
        $this->scriptDriectory =        $config['scriptDriectory'];
        $this->publicCacheFolder =      $config['publicCacheFolder'];
        $this->publicScriptFolder =     $config['publicScriptFolder'];
        $this->useCache =               $config['useCache'];
        $this->cached =                 $config['cached'];
        $this->cacheDirectory =         $config['cacheDirectory'];
        $this->cachefile =              $config['cachefile'];
        $this->minimize =               $config['minimize'];
        $this->ttl =                    $config['ttl'];
        $this->urlscript =              $config['urlscript'];
        $this->arrayRepo =              $config['arrayRepo'];
        $this->useInternalPath =        $config['useInternalPath'];
        $this->internalPath =           $config['internalPath'];
        
        $this->timeNow = time();
        if ($this->arrayRepo == false) {
            $this->entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        }
        $this->headLink = $serviceLocator->get('viewhelpermanager')->get('headLink');
        $this->headScript = $serviceLocator->get('viewhelpermanager')->get('headScript');
        return $this;
    }
    
    
    /**
     * When invoked we want this object to be parsed back and check if the cache files are still valid.
     * 
     * @todo Need to improve performance, fx instead of using md5 then use DB call or file load. Maybe use the ZF2 internal cache to find the needed cache file.
     * @param type $url
     * @return \ScriptRepository\View\Helper\ScriptHelper
     */
    public function __invoke($url=null)
    {
        /*
         * if we are missing the url the cache filename hasn't been set yet.
         * so lets do it now.
         */
        if ($url!=null){
            $this->url = $url;
            $this->cachefile = md5($this->url);
        } 
        
        /*
         * if we are using cache check i the cache files exists and the ttl hasnt run out.
         */
        if ($this->useCache==true){
            if(file_exists($this->cacheDirectory.$this->cachefile.'.js') && filemtime($this->cacheDirectory.$this->cachefile.'.js')+$this->ttl > $this->timeNow) {
                    $this->cached = true;
            }
        }
        return $this;
    }
   
    public function setSurfix($dir){
        $this->surfix = $dir;
        return $this;
    }
    
    /**
     * Add the needed scripts to the repository.
     * @param type $name    script name
     * @param type $ver     Script version
     * @param type $type    Type of script CSS or JS
     * @return \ScriptRepository\View\Helper\ScriptHelper
     */
    public function needScript($name,$ver,$type,$options=array(),$personal=false){
        /*
         * If the cache isnt valid please find the needed scripts and their dependencies.
         * I'm using Doctrine 2 ORM for this. 
         * New: 
         * It is also possible to use the Static Array Dependency tree now.
         */
        if (!$this->cached){
            if ($personal == true){
                $script = $this->personalScript($name,$ver,$type,$options);
            } else if ($this->arrayRepo == false){
                $script = $this->entityManager->getRepository('ScriptRepository\Entity\Script')
                        ->findOneBy(array('name'=>$name,'version'=>$ver,'type'=>$type));
                $script->setOptions($options);
            } else {
                if (!array_key_exists(strtolower($type.'/'.$name), $this->staticDependencyTree)) {
                    $script = $this->personalScript($name,$ver,$type,$options);
                } else {
                    $script = new Script();
                    $script->setId($this->staticDependencyTree[strtolower($type.'/'.$name)][$ver]['id']);
                    $script->setName($this->staticDependencyTree[strtolower($type.'/'.$name)][$ver]['name']);
                    $script->setType($this->staticDependencyTree[strtolower($type.'/'.$name)][$ver]['type']);
                    $script->setVersion($this->staticDependencyTree[strtolower($type.'/'.$name)][$ver]['version']);
                    $script->setUrl($this->staticDependencyTree[strtolower($type.'/'.$name)][$ver]['url']);   
                    $script->setOptions($options);
                    $this->addDependencies($script);
                }
            }
            if (is_object($script)){
                $this->scripts[] = $script;
            }
        }
        return $this;
    }
    
    private function personalScript($name,$ver,$type,$options){

        $script = new Script();
        $script->setId(-1);
        $script->setName(strtolower($name));
        $script->setType(strtolower($type));
        $script->setVersion(strtolower($ver));
        $script->setUrl(null);   
        $script->setPersonal(true);
        $script->setOptions($options); 
        return $script;
    }
    
    private function addDependencies($script){
        
        $depArray = $this->staticDependencyTree[strtolower($script->getType().'/'.$script->getName())][$script->getVersion()]['dependencies'];
        foreach ($depArray as $scriptname => $scriptversion){
            $newScript = new Script();
            $newScript->setId($this->staticDependencyTree[strtolower($scriptname)][$scriptversion]['id']);
            $newScript->setName($this->staticDependencyTree[strtolower($scriptname)][$scriptversion]['name']);
            $newScript->setType($this->staticDependencyTree[strtolower($scriptname)][$scriptversion]['type']);
            $newScript->setVersion($this->staticDependencyTree[strtolower($scriptname)][$scriptversion]['version']);
            $newScript->setUrl($this->staticDependencyTree[strtolower($scriptname)][$scriptversion]['url']);
            $script->linkScript($newScript);
            $this->addDependencies($newScript);
        }
    }
    
    /**
     * This will generate a list of all the needed files form the repository.
     * @Hint To minimize stress you should only run the makeRepository() once.
     * @todo find a more optimized way to do this!
     * @return \ScriptRepository\View\Helper\ScriptHelper
     */
    public function makeRepository(){
        
        /*
         * We only want to run this if the cache was not accepted or if we are not using the cache at all.
         */
        if ($this->cached == false || $this->useCache == false){
            $name = array();
            $keys = array();
            /*
             * first lets generate a list of all the needed files for the script.
             */
            foreach($this->scripts as $script){
                $scripts = $script->readScripts();                  // see the Entity\Script for more information.             
                $keys = array_merge(array_keys($scripts), $keys);   // merge the returned array with all the existend keys
                $name = $scripts + $name;                           // merge the array with the needed scripts
            }
            
            $keysize = count($keys)-1;
            $loadOrder=array();
            /*
             * Now we need to get rid of all the dublicated scripts.
             * 
             */
            for($i=$keysize; $i>=0; $i--){
                if(!in_array($name[$keys[$i]], $loadOrder)){
                    $loadOrder[] = $name[$keys[$i]];
                }
            }
            
            /*
             * Add the scripts to the objects global script load order.
             */
            $this->scriptLoadOrder = array_reverse($loadOrder, true);
            $this->makeUrl();
            
            /*
             * If we are using cache we need to write everything to a cache file.
             */
            if ($this->useCache) {
                $this->writeCacheFile();
            }
        }
        return $this;
    }
    
    public function makeUrl(){
        $urlHelper = $this->view->plugin('url');
        foreach ($this->scriptLoadOrder as $script){
            if ($this->urlscript == true && $script->getPersonal()==false){
                // Nothing to do right now
            } elseif ($this->internalPath==true && $script->getPersonal()==false) {
                
                $script->setUrl( $urlHelper('ScriptRepository/Script',array('type'=>$script->getType(),'file'=>$script->getName().'-'.$script->getVersion().'.'.strtolower($script->getType()))) );
            } elseif ($this->internalPath==false && $script->getPersonal()==false) {

                $script->setUrl( $this->publicScriptFolder.strtolower($script->getType()).'/'.$script->getName().'-'.$script->getVersion().'.'.strtolower($script->getType()) );
            } elseif ($script->getPersonal()==true) {
                $script->setUrl( $urlHelper('ScriptRepository/Script',array('type'=>$script->getType(),'file'=>$script->getName().'-'.$script->getVersion().'.'.strtolower($script->getType()),'surfix'=>$this->surfix)) );
            }
        }
    }
    
    /**
     * Simple html generation.
     * To make it simple, if we are not using cache, then just add the scripts to the head by using ZF2s internal script handler.
     * @todo Improve the html output, we would like to have more configuration.
     * @return string
     */
    public function toHtml(){
        
        if ($this->useCache){
            return "\n\t".'<script src="'.$this->publicCacheFolder.$this->cachefile.'.js"></script>'."\n\t"
                . '<link rel="stylesheet" type="text/css" href="'.$this->publicCacheFolder.$this->cachefile.'.css" />';
        } else {
            $this->addToHead();
        }
        return  '';
    }
    
    
    /**
     * Add the scripts to ZF2s internal script handler.
     * This is using append to make sure the files are put in the right order.
     */
    public function addToHead(){ 
        
        /*
         * If we are using the cache file add it to the ZF2 script handler, be careful this might make dublicates in the ZF2s handler
         * because ZF2 doesnt know if its already added.
         */
        if ($this->useCache){
            $this->headScript->appendFile($this->publicCacheFolder.$this->cachefile.'.js');
            $this->headLink->appendStylesheet($this->publicCacheFolder.$this->cachefile.'.css');
        } else {
            $this->appendScripts();
        }
    }
    
    /**
     * This will append every single script in the scriptLoadOrder array to the ZF2s script and link handler.
     */
    protected function appendScripts(){ 
        $this->appendScriptsURL();
    }
    
    protected function appendScriptsURL(){
        foreach ($this->scriptLoadOrder as $script){
            if (strtoupper($script->getType())=='JS'){
                $this->headScript->prependFile($script->getURL());
            } elseif (strtoupper($script->getType())=='CSS'){
                $this->headLink->prependStylesheet($script->getURL());
            }
        }
    }
    
    /**
     * If you asked to use the cache function then this will write the script file to the system. 
     * @NOTE The collection of data will make a huge performance hit on your system, but will in the end give you a performance boot
     * when its done.
     * @todo Need to make a minimizer script for CSS files.
     */
    protected function writeCacheFile(){
        $cacheFileContentsJS='';
        $cacheFileContentsCSS='';
        
        /*
         * Merging the contents of all the script files, both CSS and JS
         */
        foreach ($this->scriptLoadOrder as $script){
            if ($script->getType()=='JS'){
                $cacheFileContentsJS .= file_get_contents($this->scriptDriectory.strtolower($script->getType()).'/'.$script->getName().'-'.$script->getVersion().'.'.strtolower($script->getType()));
            }
            if ($script->getType()=='CSS'){
                $cacheFileContentsCSS .= file_get_contents($this->scriptDriectory.strtolower($script->getType()).'/'.$script->getName().'-'.$script->getVersion().'.'.strtolower($script->getType()));
            }
        }
        
        /*
         * If you have activated minimizing of the files then we are going to minimize the file else
         * we will just write out the files to the public cache path.
         */
        if ($this->minimize == true){
            file_put_contents($this->cacheDirectory.$this->cachefile.'.js', "/* AUTOMERGED & MINIMIZED BY DRAKE DEVELOPMET */\n".Minimizer\JSMin::minify($cacheFileContentsJS));
            file_put_contents($this->cacheDirectory.$this->cachefile.'.css', "/* AUTOMERGED & MINIMIZED BY DRAKE DEVELOPMET */\n".$cacheFileContentsCSS);
        } else {
            file_put_contents($this->cacheDirectory.$this->cachefile.'.js', "/* AUTOMERGED BY DRAKE DEVELOPMET */\n".$cacheFileContentsJS);
            file_put_contents($this->cacheDirectory.$this->cachefile.'.css', "/* AUTOMERGED BY DRAKE DEVELOPMET */\n".$cacheFileContentsCSS);
        }
    }
       	
	/**
	* Sets the EntityManager
	*
	* @param EntityManager $em
	* @access protected
	* @return PostController
	*/
	protected function setEntityManager(\Doctrine\ORM\EntityManager $em)
	{
		$this->entityManager = $em;
		return $this;
	}
	
	/**
	* Returns the EntityManager
	*
	* Fetches the EntityManager from ServiceLocator if it has not been initiated
	* and then returns it
	*
	* @access protected
	* @return EntityManager
	*/
	protected function getEntityManager()
	{
		if (null === $this->entityManager) {
			throw new Exception(__NAMESPACE__.' The doctrine 2 Entity Manager was not set');
		}
		return $this->entityManager;
	}
}