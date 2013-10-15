<?php 
class ClientIdentity extends CUserIdentity {

	private $_id;

	public function authenticate() {
		$record = Author::model()->findByAttributes(array('email' => $this->username));
		if($record == null) {
			$this->errorCode = self::ERROR_USERNAME_INVALID;
			$this->errorMessage = 'Email invalid.';
		} else if($record->password != $this->password) {
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
			$this->errorMessage = 'Password invalid.';
		} else {
			$this->errorCode = self::ERROR_NONE;
			$this->_id = $record->id;
			$this->setState( 'token', md5($record->email));
			$this->setState( 'time', time());
			
			$log = Log::model()->findByAttributes( array( 'email' => $this->username));
			if( empty( $log)) $log = new Log( array( 'email' => $this->username));
			
			$log->hash_code = $this->getState('token');
			$log->create_at = $this->getState( 'time');
			
// 			var_dump( $log);die;
			
			if( !$log->save()) {
				$this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
				$this->errorMessage = 'Error when connect Logging Log table.';
			}
		}
		return !$this->errorCode;
	}

	public function getId() {
		return $this->_id;
	}
}