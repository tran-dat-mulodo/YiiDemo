<?php

class LoginController extends BaseController
{
/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		var_dump(Yii::getVersion());
		$model=new User();
	
		// if it is ajax validation request
		/*if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}*/
	
		// collect user input data
		//if(isset($_POST['user']))
		//{
		
		
			//var_dump($_GET);
			$model->username=$_GET['user'];
			$model->password=$_GET['pass'];
			//echo "after <br/>";
			//var_dump($model->attributes);
			// validate user input and redirect to the previous page if valid
			if( $model->login())//$model->validate() &&
			{
				//echo "OK";
				$this->_sendResponse(200,$this->_getObjectEncoded('xml',array('status'=>'OK')), 'application/xml');
				//$this->redirect(Yii::app()->user->returnUrl);
			}
			else
			{
				echo "fail";
			}
		//}
		// display the login form
		//$this->render('login',array('model'=>$model));
		
	}
	
	public function actionList()
	{
		var_dump($_GET);
		echo "test";
	}
	
	public function actionView(){
		var_dump($_GET);
		echo "action iew";
	}
}