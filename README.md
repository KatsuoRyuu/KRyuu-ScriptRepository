KRyuu-ScriptRepository 0.0.1
======================

Zend Framework Script and CSS maintainer, using Doctrine2ORM and graph dependency algorithm and cache. Made this to make frontend development easier.

Configuration
======================

Add the module to the application.config.php
Most of the configuration should be self explanatory in the config/module.config.php
you will be needed to run ./vendor/bin/doctrine-module orm:schema-tool:update (--force if needed)

Usage
======================

This is a viewhelper to help you on the way when some of your scripts has dependencies.
in any .phtml you will be able to use this script.

please use the $this->ScriptRepo($this->serverUrl(true))->needScript('TheScript','Version','JS/CSS'); to define the scipt you need. the repo will then get the rest depending on your configuration in the database.

To parse everything to ZF2's script parser do a $this->ScriptRepo($this->serverUrl(true))->needScript('TheScript','Version','JS/CSS')->addToHead();

Example of how im using it:

    <?= 
	$this->ScriptRepo($this->serverUrl(true))->needScript('jQuery','1.10.2','JS')
	    ->needScript('bootstrap','3.1.1','JS')
	    ->needScript('bootstrap-theme','3.1.1','CSS')
	    ->needScript('font-collection','1.0.0','CSS')
	    ->makeRepository()->addToHead(); 
    ?>

    <?= $this->headScript()  ?>	

    <?= $this->headStyle() ?>  

this will automatically push all the scripts to the system in the right order.
