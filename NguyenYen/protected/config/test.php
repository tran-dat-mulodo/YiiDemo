<?php

return CMap::mergeArray(
	require(dirname(__FILE__).'/main.php'),
	array(
		'components'=>array(
			'fixture'=>array(
				'class'=>'system.test.CDbFixtureManager',
			),
			/* uncomment the following to provide test database connection
			'db'=>array(
				'connectionString'=>'DSN for test database',
			),
			*/
                  'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=yiitest',
			'emulatePrepare' => true,
                        'enableProfiling'=>true,
                        'enableParamLogging'=>true,
                    
			'username' => 'root',
			'password' => 'root',
			'charset' => 'utf8',
                    ),  
		),
	)
);
