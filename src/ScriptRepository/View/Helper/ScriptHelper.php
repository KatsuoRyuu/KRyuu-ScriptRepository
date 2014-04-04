<?php
namespace ScriptRepository\View\Helper;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
     * Loading all the default 
     * 
     */
    public function __construct($serviceLocator)
    {
        
        $config = $serviceLocator->get('config')[__NAMESPACE__]['configuration'];
        
        $this->scriptDriectory =        __DIR__ . '/../../../../../../../views/scripts/';
        $this->cacheDirectory =         __DIR__ . '/../../../../../../../public/cache/';
        
        
        $this->scripts =                $config['scripts'];
        $this->scriptLoadOrder =        $config['scriptLoadOrder'];
        $this->scriptDriectory =        $config['scriptDriectory'];
        $this->publicCacheFolder =      $config['publicCacheFolder'];
        $this->publicScriptFolder =     $config['publicScriptFolder'];
        $this->useCache =               $config['useCache'];
        $this->cached =                 $config['cached'];
        $this->cacheDirectory =         $config['cacheDirectory'];
        $this->cachefile =              $config['cachefile'];
        $this->timeNow =                $config['timeNow'];
        $this->minimize =               $config['minimize'];
        $this->ttl =                    $config['ttl'];
        
        $this->timeNow = time();
        $this->entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        $this->headLink = $serviceLocator->get('viewhelpermanager')->get('headLink');
        $this->headScript = $serviceLocator->get('viewhelpermanager')->get('headScript');
        return $this;
    }
    
    public function __invoke($url=null)
    {
        if ($url!=null){
            $this->url = $url;
            $this->cachefile = md5($this->url);
        } 
        if ($this->useCache==true){
            if(file_exists($this->cacheDirectory.$this->cachefile.'.js') && filemtime($this->cacheDirectory.$this->cachefile.'.js')+$this->ttl > $this->timeNow) {
                    $this->cached = true;
            }
        }
        return $this;
    }
    
    public function needScript($name,$ver,$type){
        if (!$this->cached){
            $script = $this->entityManager->getRepository('ScriptRepository\Entity\Script')
                    ->findOneBy(array('name'=>$name,'version'=>$ver,'type'=>$type));
            if (is_object($script)){
                $this->scripts[] = $script;
            }
        }
        return $this;
    }
    
    public function makeRepository(){
        
        if ($this->cached == false || $this->useCache == false){
            $name = array();
            $keys = array();
            foreach($this->scripts as $script){
                $scripts = $script->readScripts();                
                $keys = array_merge(array_keys($scripts), $keys);
                $name = $scripts + $name;
            }
            
            $keysize = count($keys)-1;
            $loadOrder=array();
            for($i=$keysize; $i>=0; $i--){
                if(!in_array($name[$keys[$i]], $loadOrder)){
                    $loadOrder[] = $name[$keys[$i]];
                }
            }

            $this->scriptLoadOrder = $loadOrder;
            
            if ($this->useCache) {
                $this->writeCacheFile();
            }
        }
        return $this;
    }
    
    public function toHtml(){
        if ($this->useCache){
            return "\n\t".'<script src="'.$this->publicCacheFolder.$this->cachefile.'.js"></script>'."\n\t"
                . '<link rel="stylesheet" type="text/css" href="'.$this->publicCacheFolder.$this->cachefile.'.css" />';
        } else {
            $this->addToHead();
        }
    }
    
    public function addToHead(){ 
        
        if ($this->useCache){
            $this->headScript->appendFile($this->publicCacheFolder.$this->cachefile.'.js');
            $this->headLink->appendStylesheet($this->publicCacheFolder.$this->cachefile.'.css');
        } else {
            $this->appendScripts();
        }
    }
    
    protected function appendScripts(){ 
        foreach ($this->scriptLoadOrder as $script){
            if ($script->getType()=='JS'){
                $this->headScript->appendFile($this->publicScriptFolder.strtolower($script->getType()).'/'.$script->getName().'-'.$script->getVersion().'.'.strtolower($script->getType()));
            } elseif ($script->getType()=='CSS'){
                $this->headLink->appendStylesheet($this->publicScriptFolder.strtolower($script->getType()).'/'.$script->getName().'-'.$script->getVersion().'.'.strtolower($script->getType()));
            }
        }
    }
    
    protected function writeCacheFile(){
        $cacheFileContentsJS='';
        $cacheFileContentsCSS='';
        
        foreach ($this->scriptLoadOrder as $script){
            if ($script->getType()=='JS'){
                $cacheFileContentsJS .= file_get_contents($this->scriptDriectory.strtolower($script->getType()).'/'.$script->getName().'-'.$script->getVersion().'.'.strtolower($script->getType()));
            }
            if ($script->getType()=='CSS'){
                $cacheFileContentsCSS .= file_get_contents($this->scriptDriectory.strtolower($script->getType()).'/'.$script->getName().'-'.$script->getVersion().'.'.strtolower($script->getType()));
            }
        }
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