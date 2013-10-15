<?php

return CMap::mergeArray(
	require(dirname(__FILE__).'/main.php'),
	array(
		'components'=>array(
			'fixture'=>array(
				'class'=>'system.test.CDbFixtureManager',
			),
			'CURL' =>array(
					'class' => 'ext.curl.Curl',
			),
// 			'db'=>array(
// 				'connectionString'=>'sqlite:'.dirname(__FILE__).'/../data/blog-test.db',
// 			),
			// uncomment the following to use a MySQL database
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
		),
	)
);
