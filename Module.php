<?php
namespace ScriptRepository;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function getViewHelperConfig()
    {
      return array(
        'factories' => array(
          'ScriptRepo' => function($sm) {
            $sm = $sm->getServiceLocator(); // $sm was the view helper's locator
            $helper = new View\Helper\ScriptHelper($sm);
            return $helper;
          }
        )
      );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
