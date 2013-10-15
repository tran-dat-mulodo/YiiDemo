<?php 

// require 'protected/models/Author.php';

class AuthorController extends APIController {

	// 	public function filters() {
	// 		return array(
	// 				'accessControl',
	// 		);
	// 	}

	// 	/**
	// 	 * Specifies the access control rules.
	// 	 * This method is used by the 'accessControl' filter.
	// 	 * @return array access control rules
	// 	 */
	// 	public function accessRules()
	// 	{
	// 		return array(
	// 				array('allow',  // allow all users to perform 'index' and 'view' actions
	// 						'actions'=>array('detail','list'),
	// 						'users'=>array('*'),
	// 				),
	// 				array('allow', // allow authenticated user to perform 'create' and 'update' actions
	// 						'actions'=>array('create','update'),
	// 						'users'=>array('@'),
	// 				),
	// 				array('allow', // allow admin user to perform 'admin' and 'delete' actions
	// 						'actions'=>array('delete'),
	// 						'users'=>array('admin'),
	// 				),
	// 				array('deny',  // deny all users
	// 						'users'=>array('*'),
	// 				),
	// 		);
	// 	}

	public function actionDetail( $author_id) {
		$data = null;
		$records = Author::model()->findAllByPk( $author_id);
		if( !empty( $records)) {
			$data = $this->convernAR2Array( $records);
		} else
			$this->setHeader( 404, 'Not Found!');
		$this->sendResponse( $data, 'author');
	}

	public function actionList() {
		$data = null;
		$records = Author::model()->findAll();
		if( !empty( $records)) {
			$data = $this->convernAR2Array( $records);
		} else
			$this->setHeader( 404, 'List author as empty.');
		$this->sendResponse($data, 'author');
	}

	/**
	 * input array(
	 * 			name,
	 * 			email,
	 * 			password,
	 * 		)
	 */
	public function actionCreate() {
		$model = new Author();
		$input_params = $this->getActionParams();
		$input_params['create_at'] = $input_params['update_at'] = time();

		foreach ( $input_params as $key => $param) {
			if( $model->hasAttribute( $key))
				$model->$key = $param;
		}

		if( $model->save())
			$this->setHeader(200, 'Create Success!');
		else
			$this->setHeader( 500, $model->getErrors());
		$this->sendResponse();
	}

	public function actionUpdate( $author_id) {
		$input_params = $this->getActionParams();
		$models = Author::model()->findAllByPk( $author_id);
		if( !empty( $models)) {
			$model = $models[0];
			$input_params['update_at'] = time();
			$model->setAttributes( $input_params);

			if( $model->save())
				$this->setHeader(200, 'Create Success!');
			else
				$this->setHeader( 500, $model->getErrors());
		} else
			$this->setHeader( 500, 'We dont find this obj on DB.');
		$this->sendResponse();
	}

	public function actionRemove( $author_id) {
		$models = Author::model()->findAllByPk( $author_id);
		if( !empty( $models)) {
			$deleted_count = 0;
			foreach( $models as $model)
				if( $model[0]->delete())
					$deleted_count ++;
			
			if( $deleted_count > 0)
				$this->setHeader(200, 'Remove Success!');
			else
				$this->setHeader( 500, $model->getErrors());
		} else
			$this->setHeader( 500, 'We dont find this obj on DB.');
		$this->sendResponse();
	}

}