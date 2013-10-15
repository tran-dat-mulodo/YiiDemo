<?php
class UserController extends ApiController
{

	
	
	public function actionIndex()
	{
// 		$user = new User;
// 		$user->attributes = array('email' => 'admin@mulodo.com', 'password' => '123456');

// 		$array = $user->doLogin();
		
// 		//$array = array('1'=>'abc', '2' => 'osduf');
// 		echo CJavaScript::jsonEncode($array);
// 		Yii::app()->end();
		$model = new User;
		$model->attributes = array('id' => 1, 'email' => 'admin@mulodo.com', 'password' => '123456');
		$data = array(
			'count' => 1,
			'data' => array($model)
		);
		$this->render('empty', $data);
	}

	
}