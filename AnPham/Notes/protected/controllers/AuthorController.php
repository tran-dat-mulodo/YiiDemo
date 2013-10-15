<?php 

// require 'protected/models/Author.php';

class AuthorController extends APIController {

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
		$dependency = new CDbCacheDependency('SELECT count(*) FROM tbl_authors');
		$records = Author::model()->cache(30000, $dependency)->findAll();
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
		$input_params = $_GET;
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
				if( $model->delete())
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