<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Web Application',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
                'application.extensions.*',
                'application.modules.*',
                'application.vendor.*'
	),
       
       // 'defaultController'=>'user',
       
	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'forum',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
                        'generatorPaths'=>array('ext.bootstrap.gii',),
		),
		
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat'=>'path',
			//'showScriptName' => false,//yen
                        //'baseUrl' => '',//Yen
			'rules'=>array(
				/*'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',*/
                                // REST patterns
                           /*     array('api1/view', 'pattern'=>'api1/<model:\w+>/<id:\d+>.<exts:>', 'verb'=>'GET'),
                                array('api1/list', 'pattern'=>'api1/<model:\w+>.<exts:>', 'verb'=>'GET'),
                                array('api1/update', 'pattern'=>'api1/<model:\w+>/<id:\d+>', 'verb'=>'PUT'),  // Update
                                array('api1/delete', 'pattern'=>'api1/<model:\w+>/<id:\d+>', 'verb'=>'DELETE'),
                                array('api1/create', 'pattern'=>'api1/<model:\w+>', 'verb'=>'POST'), // Create
                                //Test APIs Post Group
                                array('postid/view', 'pattern'=>'postid.<exts:>', 'verb'=>'GET'),
                                array('postid/update', 'pattern'=>'postid.<exts:>', 'verb'=>'PUT'),
                                array('postid/insert', 'pattern'=>'postid.<exts:>', 'verb'=>'POST'),
                                array('postid/delete', 'pattern'=>'postid.<exts:>', 'verb'=>'DELETE'),
                                array('getallpost/list', 'pattern'=>'getallpost.<exts:>', 'verb'=>'GET'),
                                //end Test APIs Post Group
                                //Test APIs User Group
                                array('user/list', 'pattern'=>'user.<exts:>', 'verb'=>'GET'),//list
                                array('user/view', 'pattern'=>'user/<id:\d+>.<exts:>', 'verb'=>'GET'),//view
                                array('user/update', 'pattern'=>'user/<id:\d+>.<exts:>', 'verb'=>'PUT'),  // Update
                                array('user/delete', 'pattern'=>'user/<id:\d+>.<exts:>', 'verb'=>'DELETE'),//delete
                                array('user/create', 'pattern'=>'user.<exts:>', 'verb'=>'POST'), // Create*/
                                //end Test APIs User Group
                                //Test APIs Comment Group
                                
                                array('comment/view', 'pattern'=>'comment/<id:\d+>.<exts:>', 'verb'=>'GET'),//view
                                array('comment/list', 'pattern'=>'comment.<exts:>', 'verb'=>'GET'),//list
//                                array('comment/update', 'pattern'=>'comment/<id:\d+>.<exts:>', 'verb'=>'PUT'),  // Update
//                                array('comment/delete', 'pattern'=>'comment/<id:\d+>.<exts:>', 'verb'=>'DELETE'),//delete
//                                array('commet/create', 'pattern'=>'comment.<exts:>', 'verb'=>'POST'), // Create
                                array('getallcomment/list', 'pattern'=>'getallcomment.<exts:>', 'verb'=>'GET'),//list
                                //end Test APIs COmment Group
                                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		
                #Using cache with CDbCache
//		'cache'=>array(
//                    'class'=>'system.caching.CDbCache',
//                ),
//             	'cache'=>array(
// 		     'class'=>'ext.redis.CRedisCache',
// 		//if you dont set up the servers options it will use the default one
// 		//"host=>'127.0.0.1',port=>6379"
// 	        'servers'=>array(
// 		 array(
// 		 'host'=>'127.0.0.1',
// 		 'port'=>6379,
// 		 ))),
            
            
            
            
//		'db'=>array(
//			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
//		),
		// uncomment the following to use a MySQL database
		
	        'db' => array(
                    'connectionString' => 'mysql:host=127.0.0.1;dbname=yiitest;',
                    'emulatePrepare' => true,
                    'username' => 'root',
                    'password' => 'root',
                    'charset' => 'utf8',
                    'tablePrefix' => 'tbl_',
                ),
           
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning, info',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);