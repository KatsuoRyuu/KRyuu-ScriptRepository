<?php

namespace ScriptRepository\Entity;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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