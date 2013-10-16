<?php 

// require 'protected/models/Author.php';
require_once DIRNAME(__FILE__).'/../lib/UtilityTest.php';
define('BASE_URL', 'http://localhost:8888/E_ws/YiiDemo/AnPham/Notes/');

class AuthorControllerTest extends CDbTestCase
{
	private $_util;
	private $_url;

	public $fixtures = array(
			'authors' => 'Author'
	);

	public function setUp()
	{
		parent::setUp();
		$this->_util = new UtilityTest();
		$this->_url = BASE_URL;
	}


	public function testAPIList()
	{
		$response = $this->_util->get_response( $this->_url . 'author/list', false, 'get');
		$this->checkHeader( $response);
		$this->assertTrue( !empty($response));
	}

	public function testAPICreate()
	{
		$request = array(
				'name' => 'test API Create',
				'email' => 'test@api.create',
				'password' => 'testapicreate'
		);
		$response = $this->_url_->get_response( $this->_url . 'author/create', $request, 'post');
	}
	
	protected function checkHeader( $response) {
		$header = $response->getElementsByTagName('header')->item(0);
		var_dump( $header->textContent);
		$this->assertEquals( 2, count( $header));
	}
}