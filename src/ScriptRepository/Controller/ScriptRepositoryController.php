<?php
namespace ScriptRepository\Controller;

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

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ScriptRepository\Entity\Script as Script;

class ScriptRepositoryController extends AbstractActionController{

    
    
    public function indexAction() {
        
    }
    
    public function scriptAction(){
        $viewModel = new ViewModel();
        $cType = array('css'=>'text/css','js'=>'text/javascript');
                
        $type = strtolower($this->params('type'));
        $file = strtolower($this->params('file'));
        $surfix = $this->params('surfix');
        
		$response = $this->getEvent()->getResponse();
		if (isset($cType)) {
            $response->getHeaders()->addHeaderLine('Content-Type: ' . $cType[$type]);
        }
        
        $path = $this->getServiceLocator()->get('config')['ScriptRepository\View\Helper']['configuration']['internalPath'];
        $personalPath = $this->getServiceLocator()->get('config')['ScriptRepository\View\Helper']['configuration']['personalDirectory'];
        $viewModel->setTerminal(true);
        if (file_exists($path.strtolower($type).'/'.$file)) {
            $viewModel->setVariables(array('file' => file_get_contents($path.strtolower($type).'/'.$file)));
        } elseif (file_exists($personalPath.'/'.$surfix.'/'.strtolower($type).'/'.$file)) {
            $viewModel->setVariables(array('file' => file_get_contents($personalPath.'/'.$surfix.'/'.strtolower($type).'/'.$file)));
        } else {
            $viewModel->setVariables(array('file' => ''));
        }
        return $viewModel;
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