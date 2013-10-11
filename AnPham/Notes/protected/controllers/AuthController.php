<?php 
class AuthController extends APIController {

	public function actionLogin() {
		$result = array(
				'status' => 502,
				'message' => ''
		);
		$identity = new ClientIdentity($username,$password);
		if($identity->authenticate()) {
			Yii::app()->user->login($identity);
			$result = array(
					'status'=>200,
					'message'=>'Login Success!'
			);
		} else {
			$result = array(
					'status'=>200,
					'messages'=> $identity->errorMessage,
			);
		}
		$this->formatResult( $result);
	}

	public function actionLogout() {
		Yii::app()->user->logout();
	}
}
