<?php

namespace ScriptRepository\Entity;
/** 
 * @note this is the dependency graph node, it can generate the complete 
 * dependency by the use of readScripts(); 
 * @todo expand it to handle URL and maybe files full path
 * @package ScriptRepository
 * @author Anders Blenstrup-Pedersen <anders-github@drake-development.org>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.1 (2014-04-04)
 * @link https://github.com/KatsuoRyuu/KRyuu-ScriptRepository
 */

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="scriptrepository_script")
 */
class Script {
    
    /**
     *
     * @var type int
     * @ORM\id 
     * @ORM\column(type="integer") 
     * @ORM\generatedValue(strategy="AUTO")
     */
    private $id=-1;
    
    /**
     *
     * @var type string
     * @ORM\ManyToMany(targetEntity="ScriptRepository\Entity\Script", inversedBy="id")
     * @ORM\JoinTable(name="scriptrepository_script_script_linker",
     *      joinColumns={@ORM\JoinColumn(name="script", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="dependency", referencedColumnName="id")}
     * )
     */
    private $script;
    
    /**
     *
     * @var type string
     * @ORM\column(type="string")
     */
    private $name='';
    
    /**
     *
     * @var type string
     * @ORM\column(type="string")
     */
    private $type='';
    
    /**
     *
     * @var type string
     * @ORM\column(type="string")
     */
    private $version='';
    
    /**
     *
     * @var type string
     * @ORM\column(type="string")
     */
    private $url='';
    
    /**
     *
     * @var type boolean
     * @ORM\column(type="boolean")
     */
    private $isRoot = false;
    
    public function __construct(){
        $this->script= new ArrayCollection();
        return $this;
    }
    
    public function readScripts($nodeSearched=array()){
        $deps = array();
        // loop all the scripts that this depends on.
        // and return the collection.
        foreach ($this->script as $node){
            if (!in_array($node->getName(), $nodeSearched)){
                $nodeSearched[] = $node->getName();
                $deps = $node->readScripts($nodeSearched) + $deps;
            }
        }
        
        return array($this->id => $this) + $deps;
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function setId($id){
        $this->id = $id;
        return $id;
    }
    
    public function getScripts(){
        return $this->script;
    }
    
    public function getScript($id){
        return $this->script[$id];
    }
    
    public function addScript($id,$name){
        $newScript = new Script($id,$name);
        return $this->script[] = $newScript;
    }
    
    public function linkScript(Script $node){
        $this->script[] = $node;
        return $this;
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function setName($name){
        $this->name = $name;
        return $this;
    }
    
    public function getType(){
        return $this->type;
    }
    
    public function setType($type){
        $this->type = $type;
        return $this;
    }
    
    public function getVersion(){
        return $this->version;
    }
    
    public function setVersion($version){
        $this->version = $version;
        return $this;
    }
    
    public function getUrl(){
        return $this->url;
    }
    
    public function setUrl($url){
        $this->url = $url;
        return $this;
    }
    
    public function getRoot(){
        throw new Exception('Not yet Implementet');
    }
    
    public function setRootTrue(){
        $this->isRoot = true;
        return $this;
    }
    
    public function setRootFalse(){
        $this->isRoot = false;
        return $this;
    }
    
    
    
}