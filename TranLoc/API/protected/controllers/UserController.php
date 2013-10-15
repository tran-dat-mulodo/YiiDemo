<?php

class UserController extends ApiController {
	private $_model;

	public function filters() {
		//        return array('accessControl', // perform access control for CRUD operations
		//        );
	}




	/* Shows a single item using GET method
	 *
	* @access public
	* @param $_GET['id'] // is Numberic
	* @return void
	*/

	public function actionList() {
		#$this->check_auth();
		//Get params data
		//validate params
		//$model = User::model();
		//		$model->scenario = 'User'; // create scenario name
		//		$model->id = 1; // set value for id
		//		if (!$model->validate()){
		//			$result = $this->get_error_validates($model->getErrors());
		//			$this->response($result, 'errors');
		//		}

		$models = User::model()->findAll();
		if (is_null($models)) {
			$this->_sendResponse(200, sprintf('No items where found for model user'));
		} else {
			$rows = array();

			foreach ($models as $model)
				$rows[] = $model->attributes;
			//            $API = new ApiController("");
			$this->_sendResponse(200, $this->_getObjectEncoded('user', $rows));
		}


		//		if(empty($data))
			//            $this->response(array('error'=>array('status'=>204, 'message'=>'No Content')));
			//        else {
			//        	$data_response = array(
			//				'error'=> array('status'=>200, 'message'=>''),
			//				'datas'=>$data->attributes
			//			);
			//			$this->response($data_response);
			//		}
		}

		public function actionView($id)
		{
			#$this->check_auth();
			//validate params

			$model = User::model();
			$model->scenario = 'User'; // create scenario name
			$model->id = $id; // set value for id
			if (!$model->validate()){
				$result = $this->get_error_validates($model->getErrors());
				$this->response($result, 'errors');
			}
			$data = $model->findByPk($id);
			if(empty($data))
				$this->response(array('error'=>array('status'=>204, 'message'=>'No Content')));
			else {
				$data_response = array(
						'error'=> array('status'=>200, 'message'=>''),
						'datas'=>$data->attributes
				);
				$this->response($data_response);
			}
		}

		/* Insert new item using POST method
		 *
		* @access public
		* @param array $params
		* @return void
		*/

		public function actionInsert() {
			//check login
			//        $id = $this->check_auth();

			$model = new User();
			//get data from POST http
			$post_params = array();

			foreach ($_POST as $key => $value) {
				// Does the model have this attribute?
				if ($model->hasAttribute($key)) {
					$model->$key = rawurldecode($value);
				}
				else
					$this->response(array('error' => array('status' => 204, 'message' => 'No Content')));
			}
			//        print_r($model);
			if ($model->save()) {
				// Saving was OK
				$data_response = array(
						'error' => array('status' => 200, 'message' => ''),
						'datas' => $model->attributes
				);
				$this->response($data_response);
			} else {
				$result = $this->get_error_validates($model->errors);
				$this->response($result, 'errors');
			}
		}

		/* Update item is exist using PUT method
		 *
		* @access public
		* @param array $params
		* @return void
		*/

		public function actionUpdate_bk() {
			//check login
			$userid = $this->check_auth();

			$post_params = null;
			//get data from PUT http
			parse_str(file_get_contents('php://input'), $post_params);
			$id = (int) $post_params['id'];
			unset($post_params['id']);

			//validate params id
			$model = new Post();
			$model->scenario = 'PostId'; // create scenario name
			$model->id = $id; // set value for id
			if (!$model->validate()) {
				$result = $this->get_error_validates($model->getErrors());
				$this->response($result, 'errors');
			}
			//find item with $id
			$model = $model->findByPk($id);
			if (empty($model))
				$this->response(array('error' => array('status' => 204, 'message' => 'No Content')));

			//Set value for propeties
			if (!empty($post_params)) {
				foreach ($post_params as $key => $value) {
					// Does the model have this attribute?
					if ($model->hasAttribute($key)) {
						$model->$key = rawurldecode($value);
					}
					else
						$this->response(array('error' => array('status' => 204, 'message' => 'No Content')));
				}
				if ($model->save()) {
					// Saving was OK
					$data_response = array(
							'error' => array('status' => 200, 'message' => ''),
							'datas' => $model->attributes
					);
					$this->response($data_response);
				} else {
					$result = $this->get_error_validates($model->errors);
					$this->response($result, 'errors');
				}
			}
			else
				$this->response(array('error' => array('status' => 204, 'message' => 'No Content')));
		}

		/**
		 * Updates a particular model.
		 * If update is successful, the browser will be redirected to the 'view' page.
		 */
		public function actionUpdate($id) {
			$model = $this->loadModel();

			$post_params = null;
			//get data from PUT http
			//                $input = file_get_contents('php://input');
			//    print_r($input);die;
			parse_str(file_get_contents('php://input'), $post_params);
			//        parse_str(file_get_contents('php://input'), $post_params);
			//        print_r($post_params);

			//        $id = (int) $post_params['id'];
			if (isset($post_params)) {

				// Try to assign PUT parameters to attributes
				foreach($post_params as $var=>$value) {
					// Does model have this attribute?
					if($model->hasAttribute($var)) {
						if($var == 'birthday')
							$model->$var = strtotime ($value);
						else $model->$var = $value;
					} else {
						// No, raise error
						$this->_sendResponse(500, sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>', $var, $_GET['model']) );
					}
				}

				if ($model->save()){
					//				$this -> redirect(array('view', 'id' => $model -> id));
					$data = $model->findByPk($id);
					$data_response = array(
							'error'=> array('status'=>200, 'message'=>''),
							'datas'=>$data->attributes
					);
					$this->response($data_response);
				}
			}

			//		$this -> render('update', array('model' => $model, ));
		}

		/* Delete item is exist using DELETE method
		 *
		* @access public
		* @param int $id
		* @return void
		*/

		public function actionDelete() {
			//check login
			//        $userid = $this->check_auth();

			$post_params = null;
			//get data from PUT http
			parse_str(file_get_contents('php://input'), $post_params);
			$id = (int) $post_params['id'];
			unset($post_params['id']);

			//validate params id
			$model = new User();
			$model->scenario = 'User'; // create scenario name
			$model->id = $id; // set value for id
			if (!$model->validate()) {
				$result = $this->get_error_validates($model->getErrors());
				$this->response($result, 'errors');
			}
			//find item with $id
			$model = $model->findByPk($id);
			if (empty($model))
				$this->response(array('error' => array('status' => 204, 'message' => 'No Content')));

			$num = $model->delete();
			if ($num > 0)
				$this->response(array('error' => array('status' => 200, 'message' => '')));
			else
				$this->response(array('error' => array('status' => 500, 'message' => 'Internal Server Error')));
		}

		/**
		 * Returns the data model based on the primary key given in the GET variable.
		 * If the data model is not found, an HTTP exception will be raised.
		 */
		public function loadModel() {

			if ($this ->_model === null) {

				if (isset($_GET['id'])) {

					if (Yii::app()->user ->isGuest)
						$condition = 'status=' . User::STATUS_PUBLISHED . ' OR status=' . User::STATUS_ARCHIVED;
					else
						$condition = '';
					//using CDbCache component
					//$dependency = new CDbCacheDependency('SELECT MAX(update_time) FROM tbl_post');
					//$this -> _model = Post::model() -> cache(30, $dependency) -> findByPk($_GET['id'], $condition);
					$this ->_model = User::model() -> findByPk($_GET['id']);
				}

				if ($this ->_model === null)
					throw new CHttpException(404, 'The requested page does not exist.');
			}

			return $this ->_model;
		}

}

?>