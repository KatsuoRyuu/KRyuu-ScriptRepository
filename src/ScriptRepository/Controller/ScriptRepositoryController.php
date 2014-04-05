<?php

/** 
 * @note NO FINISHED AT ALL PLEASE DONT USE
 * @todo finish the administration tool, to maintain the repository.
 * as of now its only the test bench to please dont use this!
 * @package ScriptRepository
 * @author Anders Blenstrup-Pedersen <anders-github@drake-development.org>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 0.0.1 (2014-04-04)
 * @link https://github.com/KatsuoRyuu/KRyuu-ScriptRepository
 */


namespace ScriptRepository\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ScriptRepository\Entity\Script as Script;

class ScriptRepositoryController extends AbstractActionController{

    
    
    public function indexAction() {
        
        
        $entityManager = $this->getEntityManager();

        $jQuery = new Script();
        $jQuery->setName('jQuery')->setVersion('1.10.2')->setType('JS');
        
        $jQuery_migrate = new Script();
        $jQuery_migrate->setName("jquery-migrate")->setVersion("1.2.1")->setType("JS");
        
        $spin = new Script();
        $spin->setName('spin.js')->setVersion('1.3.1')->setType('JS');
        
        $loading = new Script();
        $loading->setName('loading')->setVersion('0.0.1')->setType('JS');
        
        $emailSpamProtection = new Script();
        $emailSpamProtection->setName('emailSpamProtection')->setVersion('1.0')->setType('JS');
        
        $jquery_ui = new Script();
        $jquery_ui->setName('jquery-ui')->setVersion('1.10.3')->setType('JS');
        
        $Cycle2 = new Script();
        $Cycle2->setName('jquery.cycle2')->setVersion('20130909')->setType('JS');
        
        $bootstrap_min = new Script();
        $bootstrap_min->setName('bootstrap')->setVersion('2012')->setType('JS');
        
        $spin->linkScript($jQuery);
        
        $Cycle2->linkScript($jQuery);
        
        $loading->linkScript($jQuery);
        
        $emailSpamProtection->linkScript($jQuery);
        
        $jquery_ui->linkScript($jQuery);
        
        
        $entityManager->persist($jQuery);
        $entityManager->persist($jQuery_migrate);
        $entityManager->persist($spin);
        $entityManager->persist($Cycle2);
        $entityManager->persist($loading);
        $entityManager->persist($emailSpamProtection);
        $entityManager->persist($jquery_ui);
        $entityManager->persist($bootstrap_min);
        
        $entityManager->flush();
    }
    
    public function index2Action() {
        $loader         = new Script(9, 'loader');
        $jQueryGalleri  = new Script(6, 'JQueryGalleri');
        $jQuery         = new Script(4, 'JQuery');
        $pointer        = new Script(8, 'Pointer');
        $angularJS      = new Script(5, 'AngularJS');
        $button         = new Script(7, 'Button');
        $spin           = new Script(11,'Spin');

        $jQueryGalleri->linkScript($pointer)->linkScript($loader)->linkScript($jQuery);
        $angularJS->linkScript($jQuery)->linkScript($button)->linkScript($spin);


        print_r($jQueryGalleri->readScripts());
        print_r($angularJS->readScripts());

        $keys = array_merge(array_keys($jQueryGalleri->readScripts()), array_keys($angularJS->readScripts()));
        $name = $jQueryGalleri->readScripts() + $angularJS->readScripts();

        print_r($keys);
        print_r($name);

        $keysize = count($keys)-1;
        $loadOrder=array();
        for($i=$keysize; $i>=0; $i--){
            if(!key_exists($keys[$i], $loadOrder)){
                $loadOrder[] = $name[$keys[$i]];
            }
        }

        print_r($loadOrder);
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
			$this->setEntityManager($this->getServiceLocator()->get('Doctrine\ORM\EntityManager'));
		}
		return $this->entityManager;
	}
    
}