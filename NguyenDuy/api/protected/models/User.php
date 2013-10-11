<?php

interface IUser {
	public function doLogin();
	public function doLogout();
}


class User extends CActiveRecord implements IUser
{
	public $id;
	public $email;
	public $password;
	
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('id, email, password', 'required'),
			// email has to be a valid email address
			array('email', 'email')
		);
	}
	
	public function doLogin(){
		$user = $this->getInfoByEmail($this->email);
		
		return $user;
	}
	
	public function doLogout(){
		return true;
	}
	
	public function getInfoByEmail($email = null){
		
		$connection = $this->getDbConnection();
		$sql="SELECT * FROM user where email = :email";
		$command=$connection->createCommand($sql);
		// replace the placeholder ":email" with the actual email value
		$command->bindParam(":email",$email,PDO::PARAM_STR);
		return $command->execute();
	}
	
	public function tableName() {
		return 'user';
	}
}