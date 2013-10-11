<?php 
class AuthController extends APIController {

	public function actionLogin() {
		$response = null;
		$input_params = $this->getActionParams();
		$email = $input_params['email'];
		$password = $input_params['password'];
		
		$identity = new ClientIdentity($email,$password);
		if($identity->authenticate()) {
			Yii::app()->user->login($identity);
			$this->setHeader(200, 'Login Success!');
			$response = array(
					'token' => $identity->getState( 'token'),
					'time' => $identity->getState( 'time'),
			);
		} else {
			$this->setHeader( 300, $identity->errorMessage);
		}
		$this->sendResponse( $response, 'token');
	}

	public function actionLogout() {
		Yii::app()->user->logout();
	}
}
