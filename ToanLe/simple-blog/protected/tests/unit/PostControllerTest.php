<?php

/**
 *
 * @author letoan
 *
 */

require_once DIRNAME(__FILE__).'/UtilityTest.php';
require_once DIRNAME(__FILE__).'/ConstantTest.php';


class PostControllerTest extends CDbTestCase
{
	
	
	
	public $fixtures = array(
		'posts'=>':tbl_post',		
	);
	
	protected  $utility;
	
	
	
	public function setUp()
	{
		parent::setUp();
		$this->utility = new UtilityTest();
		$this->base_url = Yii::app()->request->baseUrl;
	}
	
	public function testActionListStatusCode()
	{
		$path= "/post";						
		$url = BASE_URL.$path;
		
		$result = $this->utility->get_response($url);		
		$this->assertEquals(SUCCESS_HTTP, $result->getElementsByTagName('status')->item(0)->nodeValue);
		$this->assertEquals('', $result->getElementsByTagName('message')->item(0)->nodeValue);
	}
	
	public function testActionListHitCount()
	{	
		$path= "/post";
		$url = BASE_URL.$path;
	
		$result = $this->utility->get_response($url);
		$this->assertEquals(3, $result->getElementsByTagName('hit_count')->item(0)->nodeValue);
	}
	
	public function testActionListItemTags()
	{
		$path= "/post";
		$url = BASE_URL.$path;
		
		$result = $this->utility->get_response($url);

		$matcher = array('tag' => 'items', 			
			'children' => array(
					'count' => 3, 
					'only' => array('tag' => 'item'))
			
			);
		
		$this->assertTag($matcher, $result->getElementsByTagName('items'), 'error', FALSE);
	}
 
}