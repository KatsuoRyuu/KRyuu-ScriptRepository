<?php

namespace ScriptRepository;

return array(
    __NAMESPACE__.'\View\Helper' => array(
        'configuration' => array(
            /**
             * This is the place of where the scripts should reside.
             * Default placement is using __DIR__ and can't be entered in the define part of
             * the object, please refere to the constructor.
             * @var type String
             */
            'scriptDriectory'   => __DIR__ . '/../../../../views/scripts/',
            /**
             * System cache path.
             * This is differend from the cache path, it's the full path of the public 
             * cache directory. 
             * This is used when generating the merged cache files.
             * @var type String
             */
            'cacheDirectory'    => __DIR__ . '/../../../../public/cache/',
            /**
             * Array of the scripts needed by the system.
             * This array is the base search array of the repository.
             * @var type Array
             */
            'scripts'           => array(),
            /**
             * Array of the load order of the scripts.
             * This load order has no dublicated script entry, and is the order of loading
             * @var type Array()
             */
            'scriptLoadOrder'   => array(),
            /**
             * This is the place of where the scripts should reside.
             * Default placement is using __DIR__ and can't be entered in the define part of
             * the object, please refere to the constructor.
             * @var type String
             */
            'scriptDriectory'   => '',
            /**
             * public path for the script cache folder.
             * This path is for the cached script files. this is used for generating the 
             * public path way of the cache folder 
             * Example:
             *      <script src="/cache/js/script.js"></script>
             * @var type String
             */
            'publicCacheFolder' => "/cache/",
            /**
             * public script path.
             * This path is for the public scripts, and can be found from the web.
             * @var type 
             */
            'publicScriptFolder'=> "/scripts/",
            /**
             * Choose if the script repository should use a merged caching system.
             * @var type boolean
             */
            'useCache'          => false,
            /**
             * ADVANCED, DO NOT EDIT.
             * This is for parsing an overwrite on the auto caching system. 
             * please be aware of what you are doing here
             * @var type boolean
             */
            'cached'            => false,
            /**
             * System cache path.
             * This is differend from the cache path, it's the full path of the public 
             * cache directory. 
             * This is used when generating the merged cache files.
             * @var type String
             */
            'cacheDirectory'    => '/',
            /**
             * ADVANCED, DO NOT EDIT.
             * This is the cachefile name ans should be maintained by the auto cache function
             * cache filename generated from the URL path.
             * @var type string
             */
            'cachefile'         => null,
                
            /**
             * Choose to schrink the cached scripts by removing extraneous spaces and linebreaks.
             * @var type boolean
             */
            'minimize'          => false,
            
            /**
             * Accept using URL paths if avaiable.
             * @var Boolean
             */
            'urlscript'         => true,
            
            /**
             * force using array repo instead of doctrine 2.
             * @var Boolean
             */
            'arrayRepo'         => true,
            
            /**
             * force using internel paths, scripts will be parsed thro PHP.
             * This might give you a big performance hit.
             * @var Boolean
             */
            'useInternalPath'         => false,
            
            /**
             * The internal path for the Scripts.
             * Used when useInternalPath is true.
             * @var Boolean
             */
            'internalPath'         => __DIR__.'/../view/Scripts/',
                    
    /**
     * how many seconds the cachefile should live before it needs to be updated.
     * be aware of changes to designs doesn't update before ttl has run out, even if you
     * make changes to the scripts (To improve performance).
     * At development please disable cache function.
     * @var type int
     */
            'ttl'               => 3600,
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'ScriptRepository\Controller\ScriptRepository' => 'ScriptRepository\Controller\ScriptRepositoryController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'ScriptRepository' => array(
                'type'    => 'literal',
                'options' => array(
                    'route' => '/script',
                    'defaults' => array(
                        'controller'    => 'ScriptRepository\Controller\ScriptRepository',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'Script' => array(
                        'type'    => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/file[/:type][/:file]',
                            'constraints' => array(
                                'type'  => '[a-zA-Z0-9.-]*',
                                'file'  => '[a-zA-Z0-9.-]*',
                            ),
                            'defaults' => array(
                                'controller'    => 'ScriptRepository\Controller\ScriptRepository',
                                'action'        => 'script',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'doctrine'=> array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'),
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver',
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'scriptrepository' => __DIR__ . '/../view',
        ),
    ),
    
);