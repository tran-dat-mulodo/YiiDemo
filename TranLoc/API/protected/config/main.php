<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Yii Api',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.extensions.*',
	),

	'defaultController'=>'post',

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		
		#Using cache with CDbCache
		'cache'=>array(
            'class'=>'system.caching.CDbCache',
        ),
		
		/*
		'db'=>array(
			'connectionString' => 'sqlite:protected/data/blog.db',
			'tablePrefix' => 'tbl_',
		),
		*/
		// Use a MySQL database
		
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=thithi_api',
			'emulatePrepare' => true,
			'enableProfiling'=>true,
        	'enableParamLogging'=>true,
        	
			'username' => 'root',
			'password' => 'root',
			'charset' => 'utf8',
			'tablePrefix' => 'tt_',
		),
// 		'db2' => array(
//             'connectionString' => 'mysql:host=localhost;dbname=yiitest2',
//             'username'         => 'root',
//             'password'         => 'root',
//             'class'            => 'CDbConnection' // !important
//         ),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
        'urlManager'=>array(
        	'urlFormat'=>'path',
        	'showScriptName'=>false,
        	'rules'=>array(
        				'dang-nhap.html'=>array('site/login', 'caseSensitive'=>false),
        				'<view:>.html'=>array('site/page', 'caseSensitive'=>false),
                        'post/<id:\d+>/<title:.*?>.html'=>'post/view',
                        'post/<id:\d+>/testfragment'=>'post/fragmentcache',
                        'posts/<tag:.*?>.html'=>'post/index',
                        // REST patterns
                        array('api/list', 'pattern'=>'api/<model:\w+>', 'verb'=>'GET'),
                        array('api/view', 'pattern'=>'api/<model:\w+>/<id:\d+>', 'verb'=>'GET'),
                        array('api/update', 'pattern'=>'api/<model:\w+>', 'verb'=>'PUT'),  // Update
                        array('api/delete', 'pattern'=>'api/<model:\w+>', 'verb'=>'DELETE'),
                        array('api/create', 'pattern'=>'api/<model:\w+>', 'verb'=>'POST'), // Create
                        //Test API
                        array('postid/view', 'pattern'=>'postid', 'verb'=>'GET'),
                        array('postid/update', 'pattern'=>'postid', 'verb'=>'PUT'),
                        array('postid/insert', 'pattern'=>'postid', 'verb'=>'POST'),
                        array('postid/delete', 'pattern'=>'postid', 'verb'=>'DELETE'),
                        array('getallpost/list', 'pattern'=>'getallpost(.<exts:>)', 'verb'=>'GET'),
                        //end Test API
                        '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
        	),
        ),
		'log'=>array(
			/*
			'class'=>'CLogRouter',
	        'routes'=>array(
	            array(
	                'class'=>'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
	                // Access is restricted by default to the localhost
	                //'ipFilters'=>array('127.0.0.1','192.168.1.*', 88.23.23.0/24),
	            ),
	        ),
			*/
	        
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
				),*/
				
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__).'/params.php'),
);
