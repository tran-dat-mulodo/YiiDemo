<?php
require_once DIRNAME(__FILE__).'/UtilityTest.php';
require_once DIRNAME(__FILE__).'/ConstantTest.php';
class PostTest extends CDbTestCase
{
	/**
	 * We use both 'Post' and 'Comment' fixtures.
	 * @see CWebTestCase::fixtures
	 */
	public $fixtures=array(
		'posts'=>'Post',
// 		'comments'=>'Comment',
	);

	protected  $utility;
	
	public function setUp()
	{
		parent::setUp();
		$this->utility = new UtilityTest();
		$this->base_url = Yii::app()->request->baseUrl;
	}
	
	public function testSave()
	{
		// write code here to test post saving method
		$path= "/post";
		$url = BASE_URL.$path;
		
		$result = $this->utility->get_response($url , "post");
		var_dump($result);
	}
}