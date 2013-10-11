<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
//

//Yii::import(dirname(__FILE__).'/constant.php');
include_once (dirname(__FILE__).'/constant.php');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Yii Blog Demo',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'defaultController'=>'post',
		'modules' => array(
				'gii' => array(	'class' => 'system.gii.GiiModule',
						'password' => false
				)
		),
	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		'region' => array('class' => 'RegionSingleton'),
		'httpval'=> array('class'=> 'HttpValComp'),
// 		'db'=>array(
// 			'connectionString' => 'sqlite:protected/data/blog.db',
// 			'tablePrefix' => 'tbl_',
// 		),
		// uncomment the following to use a MySQL database
		
		'db'=>array(
			'connectionString' => 'mysql:host=127.0.0.1;dbname=simple_blog;port=8889',
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
        'cache' => array(
        	'class' => 'CRedisCache',
        	'hostname' => 'localhost',
        	'port' => 6379,
        	'database' => 0
        ),
        'fixture' => array(
        	'class' => 'system.test.CDbFixtureManager',
        ),
        'urlManager'=>array(
        	'urlFormat'=>'path',
        	'showScriptName' => false,
        	'rules'=>array(
                        
                        // REST patterns
//                         array('post/list', 'pattern'=>'post/<model:\w+>', 'verb'=>'GET'),
//                         array('api/view', 'pattern'=>'api/<model:\w+>/<id:\d+>', 'verb'=>'GET'),
//                         array('api/update', 'pattern'=>'api/<model:\w+>/<id:\d+>', 'verb'=>'PUT'),  // Update
//                         array('api/delete', 'pattern'=>'api/<model:\w+>/<id:\d+>', 'verb'=>'DELETE'),
//                         array('api/create', 'pattern'=>'api/<model:\w+>', 'verb'=>'POST'), // Create

						array('<controller>/view', 'pattern' => '<controller:\w+>/<id:\d+>', 'verb' => 'GET'),
						array('<controller>/list', 'pattern' => '<controller:\w+>', 'verb' => 'GET'),
						array('<controller>/update', 'pattern' => '<controller:\w+>/<id:\d+>', 'verb' => 'PUT'),
        			
                        
        	),
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
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
	'params'=>require(dirname(__FILE__).'/params.php'),
);
