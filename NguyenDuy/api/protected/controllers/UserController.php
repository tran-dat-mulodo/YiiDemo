<?php
class UserController extends ApiController
{

	public function actionIndex()
	{
		$user = new User;
		$user->attributes = array('email' => 'admin@mulodo.com', 'password' => '123456');

		$array = $user->doLogin();
		
		
		//$array = array('1'=>'abc', '2' => 'osduf');
		echo CJavaScript::jsonEncode($array);
		Yii::app()->end();
	}

	
}