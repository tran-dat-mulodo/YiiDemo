<?php 
class Auth extends CFormModel {
	
	private $_email;
	private $_password;
	
	
	public function __construct( $email, $password) {
		parent::__construct();
		$this->_email = $email;
		$this->_password = $password;
	}
	
	public function authenticate() {
	}
}