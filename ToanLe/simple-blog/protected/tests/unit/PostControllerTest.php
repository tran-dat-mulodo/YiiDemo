<?php

/**
 *
 * @author letoan
 *
 */

require_once DIRNAME(__FILE__).'/UtilityTest.php';
require_once DIRNAME(__FILE__).'/ConstantTest.php';

class PostsControllerTest extends CDbTestCase
{
	private $path_action_list_create = '/posts';
	private $path_action_view_update = '/posts/1';
	private $path_action_view_update_input_error = '/posts/asd';


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

	/**
	 * @group actionList
	 */
	public function testActionListStatusCode()
	{
		$url = BASE_URL.$this->path_action_list_create;

		$result = $this->utility->get_response($url);

		$this->assertEquals(STATUS_SUCCESS, $result->getElementsByTagName('status')->item(0)->nodeValue);
		$this->assertEquals('', $result->getElementsByTagName('message')->item(0)->nodeValue);
	}

	/**
	 * @group actionList
	 */
	public function testActionListHitCount()
	{
		$url = BASE_URL.$this->path_action_list_create;;

		$result = $this->utility->get_response($url);
		$this->assertEquals(3, $result->getElementsByTagName('hit_count')->item(0)->nodeValue);
	}

	/**
	 * @group actionList
	 */
	public function testActionListItemTags()
	{
		$url = BASE_URL.$this->path_action_list_create;

		$result = $this->utility->get_response($url);
		$matcher = array('tag' => 'items',
				'children' => array(
						'count' => 3,
						'only' => array('tag' => 'item'))
					
		);
		$this->assertTag($matcher, $result, 'error', FALSE);
	}

	/**
	 * @group actionList
	 */
	public function testActionListItemStructure()
	{
		$url = BASE_URL.$this->path_action_list_create;
		$result = $this->utility->get_response($url);

		$expected = new DOMDocument;
		$expected->loadXML('<item><id/><title/><content/><tags/><status/><create_time/><update_time/><author_id/></item>');
		 
		$this->assertEqualXMLStructure(
				$expected->firstChild, $result->getElementsByTagName('item')->item(0)
		);
	}
	
	/**
	 * Test case for actionView
	 *
	 *
	 *
	 *
	 */

	/**
	 * @group actionView
	 */
	public function testActionViewStatusCodeWithErrorInput()
	{
		$url = BASE_URL.$this->path_action_view_update_input_error;
	
		$result = $this->utility->get_response($url);
	
		$this->assertEquals(RESOURCE_NOT_FOUND, $result->getElementsByTagName('status')->item(0)->nodeValue);
		
	}
	
	

	/**
	 * @group actionView
	 */
	public function testActionViewStatusCodeWithOkInput()
	{
		$url = BASE_URL.$this->path_action_view_update;

		$result = $this->utility->get_response($url);

		$this->assertEquals(STATUS_SUCCESS, $result->getElementsByTagName('status')->item(0)->nodeValue);
		$this->assertEquals('', $result->getElementsByTagName('message')->item(0)->nodeValue);
	}
	
	 	 
	
	/**
	 * @group actionView
	 */
	public function testActionViewItemStructure()
	{
		$url = BASE_URL.$this->path_action_view_update;
		$result = $this->utility->get_response($url);

		$expected = new DOMDocument;
		$expected->loadXML('<item><id/><title/><content/><tags/><status/><create_time/><update_time/><author_id/></item>');
		 
		$this->assertEqualXMLStructure(
				$expected->firstChild, $result->getElementsByTagName('item')->item(0)
		);
	}
	
	
	/**
	 * Test case for actionCreate
	 *
	 *
	 *
	 *
	 */
	
	/**
	 * @group actionCreate
	 */
	public function testActionCreateStatusCodeOk()
	{
		$url = BASE_URL.$this->path_action_list_create;
		$data = array('title' => 'this is a title1', 'content' => 'this is a conten 1', 'tags' => 'tagteo', 'author_id'=>1, 'status' => 0);
		
		$result = $this->utility->get_response($url, $data, 'post');
		$this->assertEquals(STATUS_SUCCESS, $result->getElementsByTagName('status')->item(0)->nodeValue);
		$this->assertEquals('', $result->getElementsByTagName('message')->item(0)->nodeValue);
		
	}
	
	/**
	 * @group actionCreate
	 */
	public function testActionCreateStatusCodeNotOkTitleNotExist()
	{
		$url = BASE_URL.$this->path_action_list_create;
		$data = array('content' => 'this is a conten 1', 'tags' => 'tagteo', 'author_id'=>1, 'status' => 0);
	
		$result = $this->utility->get_response($url, $data, 'post');		
		
		$this->assertEquals(SERVER_ERROR, $result->getElementsByTagName('status')->item(0)->nodeValue);		
	}
	
	/**
	 * @group actionCreate
	 */
	public function testActionCreateStatusCodeNotOkTitleNotValid()
	{
		$url = BASE_URL.$this->path_action_list_create;
		$data = array('title'=> 'sd', 'content' => 'this is a conten 1', 'tags' => 'tagteo', 'author_id'=>1, 'status' => 0);
	
		$result = $this->utility->get_response($url, $data, 'post');
	
		$this->assertEquals(SERVER_ERROR, $result->getElementsByTagName('status')->item(0)->nodeValue);
	}
	
	
	/**
	 * @group actionCreate
	 */
	public function testActionCreateStatusCodeNotOkTitleEmpty()
	{
		$url = BASE_URL.$this->path_action_list_create;
		$data = array('title'=> '', 'content' => 'this is a conten 1', 'tags' => 'tagteo', 'author_id'=>1, 'status' => 0);
	
		$result = $this->utility->get_response($url, $data, 'post');
	
		$this->assertEquals(SERVER_ERROR, $result->getElementsByTagName('status')->item(0)->nodeValue);
	}
	
	
	/**
	 * @group actionCreate
	 */
	public function testActionCreateStatusCodeNotOkContentNotExist()
	{
		$url = BASE_URL.$this->path_action_list_create;
		$data = array('title' => 'this is a title 1', 'tags' => 'tagteo', 'author_id'=>1, 'status' => 0);
	
		$result = $this->utility->get_response($url, $data, 'post');
	
		$this->assertEquals(SERVER_ERROR, $result->getElementsByTagName('status')->item(0)->nodeValue);
	}
	
	
	
	/**
	 * @group actionCreate
	 */
	public function testActionCreateStatusCodeNotOkContentNotValid()
	{
// 		$post = $this->posts['sample1'];
// 		var_dump($post);die;
		$url = BASE_URL.$this->path_action_list_create;
		$data = array('title'=> 'sdfasdfsafsdafsa', 'content' => '23d', 'tags' => 'tagteo', 'author_id'=>1, 'status' => 0);
	
		$result = $this->utility->get_response($url, $data, 'post');
	
		$this->assertEquals(SERVER_ERROR, $result->getElementsByTagName('status')->item(0)->nodeValue);
	}
	
	/**
	 * @group actionCreate
	 */
	public function testActionCreateStatusCodeNotOkContentEmpty()
	{
		// 		$post = $this->posts['sample1'];
		// 		var_dump($post);die;
		$url = BASE_URL.$this->path_action_list_create;
		$data = array('title'=> 'sdfasdfsafsdafsa', 'content' => '', 'tags' => 'tagteo', 'author_id'=>1, 'status' => 0);
	
		$result = $this->utility->get_response($url, $data, 'post');
	
		$this->assertEquals(SERVER_ERROR, $result->getElementsByTagName('status')->item(0)->nodeValue);
	}
	
	
	/**
	 * @group actionCreate
	 */
	public function testActionCreateStatusCodeNotOkStatusEmpty()
	{
		// 		$post = $this->posts['sample1'];
		// 		var_dump($post);die;
		$url = BASE_URL.$this->path_action_list_create;
		$data = array('title'=> 'sdfasdfsafsdafsa', 'content' => 'content test', 'tags' => 'tagteo', 'author_id'=>1, 'status' => '');
	
		$result = $this->utility->get_response($url, $data, 'post');
	
		$this->assertEquals(SERVER_ERROR, $result->getElementsByTagName('status')->item(0)->nodeValue);
	}
	
	
	/**
	 * @group actionCreate
	 */
	public function testActionCreateStatusCodeNotOkStatusIsString()
	{
		// 		$post = $this->posts['sample1'];
		// 		var_dump($post);die;
		$url = BASE_URL.$this->path_action_list_create;
		$data = array('title'=> 'sdfasdfsafsdafsa', 'content' => 'content test', 'tags' => 'tagteo', 'author_id'=>1, 'status' => 'sadfsadf');
	
		$result = $this->utility->get_response($url, $data, 'post');
	
		$this->assertEquals(SERVER_ERROR, $result->getElementsByTagName('status')->item(0)->nodeValue);
	}
	
	
	/**
	 * @group actionCreate
	 */
	public function testActionCreateStatusCodeNotOkAuthorIdEmpty()
	{
		// 		$post = $this->posts['sample1'];
		// 		var_dump($post);die;
		$url = BASE_URL.$this->path_action_list_create;
		$data = array('title'=> 'sdfasdfsafsdafsa', 'content' => 'content test', 'tags' => 'tagteo', 'author_id'=> '', 'status' => 0);
	
		$result = $this->utility->get_response($url, $data, 'post');
	
		$this->assertEquals(SERVER_ERROR, $result->getElementsByTagName('status')->item(0)->nodeValue);
	}
	
	
	/**
	 * @group actionCreate
	 */
	public function testActionCreateStatusCodeNotOkAuthorIdIsString()
	{
		// 		$post = $this->posts['sample1'];
		// 		var_dump($post);die;
		$url = BASE_URL.$this->path_action_list_create;
		$data = array('title'=> 'sdfasdfsafsdafsa', 'content' => 'content test', 'tags' => 'tagteo', 'author_id'=> 'sdf', 'status' => 0);
	
		$result = $this->utility->get_response($url, $data, 'post');
	
		$this->assertEquals(SERVER_ERROR, $result->getElementsByTagName('status')->item(0)->nodeValue);
	}
	
	
	/**
	 * Test case for actionUpdate
	 * 
	 * 
	 * 
	 * 
	 */
	
	
	
	/**
	 * @group actionUpdate
	 */
	public function testActionUpdateTitleEmptyStatusCodeNotOk()
	{
		$url = BASE_URL.$this->path_action_view_update;
		$data = array('title' => '');
	
		$result = $this->utility->get_response($url, $data, 'put');
		$this->assertEquals(SERVER_ERROR, $result->getElementsByTagName('status')->item(0)->nodeValue);	
	}
	
	/**
	 * @group actionUpdate
	 */
	public function testActionUpdateTitleNumberStatusCodeNotOk()
	{
		$url = BASE_URL.$this->path_action_view_update;
		$data = array('title' => 324);
	
		$result = $this->utility->get_response($url, $data, 'put');
		$this->assertEquals(SERVER_ERROR, $result->getElementsByTagName('status')->item(0)->nodeValue);
	}
	
	
	
	
	/**
	 * @group actionUpdate
	 */
	public function testActionUpdateContentEmptyStatusCodeNotOk()
	{
		$url = BASE_URL.$this->path_action_view_update;
		$data = array('content' => '');
	
		$result = $this->utility->get_response($url, $data, 'put');
		$this->assertEquals(SERVER_ERROR, $result->getElementsByTagName('status')->item(0)->nodeValue);
	}
	
	/**
	 * @group actionUpdate
	 */
	public function testActionUpdateContentIsNumberStatusCodeNotOk()
	{
		$url = BASE_URL.$this->path_action_view_update;
		$data = array('content' => 324);
	
		$result = $this->utility->get_response($url, $data, 'put');
		$this->assertEquals(SERVER_ERROR, $result->getElementsByTagName('status')->item(0)->nodeValue);
	}
	
}