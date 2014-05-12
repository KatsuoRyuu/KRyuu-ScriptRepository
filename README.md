KRyuu-ScriptRepository 0.0.2
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
	    ->needScript('bootstrap-theme','3.1.1','CSS',array('media'=>'all'))
	    ->needScript('font-collection','1.0.0','CSS')
	    ->needScript('styling-for-1024','1.0.0','CSS',array('media'=>'screen and (max-width:1024px)'))
	    ->makeRepository()->addToHead(); 
    ?>

    <?= $this->headScript()  ?>	

    <?= $this->headStyle() ?>  

this will automatically push all the scripts to the system in the right order.

Changes: 13.05.2014
======================
1. Changed the way it loads the files.
2. Support for off site directory loading.
3. Support for loading files outside the repository.

Changes: 09.04.2014
======================
1. Your can now choose between URL paths for your scripts.
2. If you dont want to use doctrine, then just use the internal Array DepTree (src/DependencyTree/DepTree.php)
3. In view you will find a scriptfolder, with the script I use, you should copy this to you public folder, unless you use the internal parser
4. Note a few changes in the configuration.