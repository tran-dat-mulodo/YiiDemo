<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'defaultController' => 'Login',
	
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Web Application',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		/*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'Enter Your Password Here',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		*/
	),

	// application components
	'components'=>array(
		//Redish Cache
		'cache'=>array(
					'class'=>'ext.redis.CRedisCache',
					//if you dont set up the servers options it will use the default one
					//"host=>'127.0.0.1',port=>6379"
					'servers'=>array(
					array(
							'host'=>'127.0.0.1',
 		 'port'=>6379,
		))),
			
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
		/*
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		*/
		'urlManager'=>array(
				'urlFormat'=>'path',
				'rules'=>array(
						'post/<id:\d+>/<title:.*?>'=>'post/view',
						'posts/<tag:.*?>'=>'post/index',
						// REST patterns
						array('login/login', 'pattern'=>'api/<model:\w+>', 'verb'=>'GET'),
						//array('login/list', 'pattern'=>'login/<model:\w+>', 'verb'=>'GET'),
						array('login/view', 'pattern'=>'api/<model:\w+>/<id:\d+>', 'verb'=>'GET'),
						array('login/update', 'pattern'=>'api/<model:\w+>/<id:\d+>', 'verb'=>'PUT'),
						array('login/delete', 'pattern'=>'api/<model:\w+>/<id:\d+>', 'verb'=>'DELETE'),
						array('login/create', 'pattern'=>'api/<model:\w+>', 'verb'=>'POST'),
						
						// Other controllers
						'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				),
		),
		
		
		
		/*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),*/
		// uncomment the following to use a MySQL database
		
		'db'=>array(
			'connectionString' => 'mysql:host=127.0.0.1;port=3306;dbname=yiiTest', //port=8889
			//'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',//root
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
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);