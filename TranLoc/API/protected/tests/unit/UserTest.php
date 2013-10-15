<?php
require_once DIRNAME(__FILE__).'/UtilityTest.php';
require_once DIRNAME(__FILE__).'/ConstantTest.php';
class UserTest extends CDbTestCase
{
	/**
	 * We use  'User' 
	 * @see CWebTestCase::fixtures
	 */
	public $fixtures=array(
		'users'=>'User',
	);

	protected  $utility;
	
	public function setUp()
	{
		parent::setUp();
		$this->utility = new UtilityTest();
		$this->base_url = Yii::app()->request->baseUrl;
	}
	
	public function testList()
	{
		// write code here to test post saving method
// 		$models = User::model()->findAll();
// 		var
		$path= "/user";
		$url = BASE_URL.$path;
		
		$result = $this->utility->get_response($url);
	}
	
// 	public function testSave()
// 	{
// 		// write code here to test post saving method
		
// 	}
}