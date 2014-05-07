<?php
return array(
            'js/jquery' => array(
                    '1.10.2' => array(
                        'id'            => 10,
                        'version'       => '1.10.2',
                        'name'          => 'jquery',
                        'url'           => 'http://code.jquery.com/jquery-1.11.0.min.js',
                        'dependencies'  => array(),
                        'type'          => 'JS'
                    ),                    
            ),
            'js/jquery-migrate' => array(
                    '1.2.1' => array(
                        'id'            => 20,
                        'version'       => '1.2.1',
                        'name'          => 'jquery-migrate',
                        'url'           => 'http://code.jquery.com/jquery-migrate-1.2.1.min.js',
                        'dependencies'  => array('js/jquery'=>'1.10.2'),
                        'type'          => 'JS'
                    ),
            ),
            'js/spin' => array(
                    '2.0.0' => array(
                        'id'            => 30,
                        'version'       => '2.0.0',
                        'name'          => 'spin',
                        'url'           => 'http://fgnass.github.io/spin.js/spin.min.js',
                        'dependencies'  => array('js/jquery'=>'1.10.2'),
                        'type'          => 'JS'
                    ),
            ),
            'js/jquery.cycle2' => array(
                    '20130909' => array(
                        'id'            => 40,
                        'version'       => '20130909',
                        'name'          => 'jquery.cycle2',
                        'url'           => 'http://malsup.github.io/min/jquery.cycle2.min.js',
                        'dependencies'  => array('js/jquery'=>'1.10.2'),
                        'type'          => 'JS'
                    ),
            ),
            'js/loading' => array(
                    '0.0.1' => array(
                        'id'            => 50,
                        'version'       => '0.0.1',
                        'name'          => 'loading',
                        'url'           => null,
                        'dependencies'  => array('js/jquery'=>'1.10.2'),
                        'type'          => 'JS'
                    ),
            ),
            'js/emailSpanProtection' => array(
                    '1.0' => array(
                        'id'            => 60,
                        'version'       => '1.0',
                        'name'          => 'emailSpanProtection',
                        'url'           => null,
                        'dependencies'  => array('js/jquery'=>'1.10.2'),
                        'type'          => 'JS'
                    ),
            ),
            'js/jquery-ui' => array(
                    '1.10.4' => array(
                        'id'            => 70,
                        'version'       => '1.10.4',
                        'name'          => 'jquery-ui',
                        'url'           => null,
                        'dependencies'  => array('js/jquery'=>'1.10.2'),
                        'type'          => 'JS'
                    ),
            ),
            'css/bootstrap' => array(
                    '3.1.1' => array(
                        'id'            => 80,
                        'version'       => '3.1.1',
                        'name'          => 'bootstrap',
                        'url'           => 'http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css',
                        'dependencies'  => array('js/bootstrap'=>'3.1.1'),
                        'type'          => 'CSS'
                    ),
            ),
            'css/bootstrap-theme' => array(
                    '3.1.1' => array(
                        'id'            => 90,
                        'version'       => '3.1.1',
                        'name'          => 'bootstrap-theme',
                        'url'           => '//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css',
                        'dependencies'  => array('css/bootstrap'=>'3.1.1'),
                        'type'          => 'CSS'
                    ),
            ),
            'js/bootstrap.min' => array(
                    '3.1.1' => array(
                        'id'            => 100,
                        'version'       => '3.1.1',
                        'name'          => 'bootstrap.min',
                        'url'           => '//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js',
                        'dependencies'  => array(),
                        'type'          => 'JS'
                    ),
            ),
            'js/bootstrap' => array(
                    '3.1.1' => array(
                        'id'            => 110,
                        'version'       => '3.1.1',
                        'name'          => 'bootstrap',
                        'url'           => '//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.js',
                        'dependencies'  => array(),
                        'type'          => 'JS'
                    ),
            ),
            'css/bootstrap.min' => array(
                    '3.1.1' => array(
                        'id'            => 120,
                        'version'       => '3.1.1',
                        'name'          => 'bootstrap.min',
                        'url'           => '//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css',
                        'dependencies'  => array('js/bootstrap.min'=>'3.1.1'),
                        'type'          => 'CSS'
                    ),
            ),
            'css/bootstrap-theme.min' => array(
                    '3.1.1' => array(
                        'id'            => 130,
                        'version'       => '3.1.1',
                        'name'          => 'bootstrap-theme.min',
                        'url'           => '//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css',
                        'dependencies'  => array('css/bootstrap.min'=>'3.1.1'),
                        'type'          => 'CSS'
                    ),
            ),
            'css/font-collection' => array(
                    '1.0.0' => array(
                        'id'            => 140,
                        'version'       => '1.0.0',
                        'name'          => 'font-collection',
                        'url'           => null,
                        'dependencies'  => array(),
                        'type'          => 'CSS'
                    ),
            ),
            'js/angular-js' => array(
                    '1.3.0' => array(
                        'id'            => 150,
                        'version'       => '1.3.0',
                        'name'          => 'angular-js',
                        'url'           => "https://ajax.googleapis.com/ajax/libs/angularjs/1.3.0-beta.7/angular.js",
                        'dependencies'  => array(),
                        'type'          => 'JS'
                    ),
            ),
            'css/jquery-ui' => array(
                    '1.10.3' => array(
                        'id'            => 160,
                        'version'       => '1.10.3',
                        'name'          => 'jquery-ui',
                        'url'           => null,
                        'dependencies'  => array('js/jquery'=>'1.10.2'),
                        'type'          => 'CSS'
                    ),
            ),
            
        ); 