<?php

Yii::import('application.controllers.ApiController');

class PostController extends ApiController
{
	public function actionList()
	{
		$cache = Yii::app()->cache;
		$array_result_init = array('error'=>array('status'=>STATUS_SUCCESS, 'message'=>''));
			
		$cached_data = $cache->get('cached_data');
		if($cached_data == false)
		{
			$data = Yii::app()->db->createCommand('SELECT * FROM tbl_post')->queryAll();

			//cache data in 1 minute
			Yii::app()->cache->set('cache_data', $data, TIME_CACHED_DATA );
		}
		else
		{
			$data = $cached_data;
		}
			
		echo $this->response(array_merge($array_result_init, $data));

	}

	public function actionView()
	{
		$array_result_init = array('error'=>array('status'=>STATUS_SUCCESS, 'message'=>''));
		$cache = Yii::app()->cache;
		if(isset($_GET['id']))
		{
			$sql = "select * from tbl_post where id = :id";
			$command = Yii::app()->db->createCommand($sql);
			$command->bindParam(":id", $_GET['id']);
			$data = $command->query()->readAll();
			//var_dump($data); die;

			echo $this->response(array_merge($array_result_init, $data));
		}

	}

	public function actionUpdate()
	{
		// Get PUT parameters

		$init_array = array('error'=>array('status'=> STATUS_SUCCESS, 'message'=> ''));

		try {

			parse_str(file_get_contents('php://input'), $put_vars);
			$model = Post::model()->findByPk($_GET['id']);
			if(!isset($model))
			{
				throw new Exception("Message error", SERVER_ERROR);
			}
				

			// Try to assign PUT parameters to attributes
			foreach($put_vars as $var=>$value) {
				// Does model have this attribute?
				if($model->hasAttribute($var)) {
					$model->$var = $value;
				} else {
					// No, raise error
					throw new Exception("Message error", SERVER_ERROR);
				}
			}

			// Try to save the model
			if($model->save()) {
				echo $this->response($init_array);
			}
			else {
				throw new Exception("Message error", SERVER_ERROR);
			}
				
		}
		catch(Exception $e)
		{
			$this->_set_error($e->getMessage(),$e->getCode());
			echo $this->respone($this->_get_error());
		}

	}

	public function actionDelete() {
		$init_array = array('error'=>array('status'=> STATUS_SUCCESS, 'message'=> ''));
		$model = Post::model()->findByPk($_GET['id']);
		try{
			// Was a model found?
			if(is_null($model)) {
				// No, raise an error
				throw new Exception("Message error", SERVER_ERROR);
			}

			// Delete the model
			$num = $model->delete();
			if($num>0)
				echo $this->response($init_array);
			else
				throw new Exception("Message error", SERVER_ERROR);
		}
		catch(Exception $e)
		{
			$this->_set_error($e->getMessage(),$e->getCode());
			echo $this->respone($this->_get_error());
		}
			
	}

	public function actionCreate()
	{
		$init_array = array('error'=>array('status'=> STATUS_SUCCESS, 'message'=> ''));
		$model = new Post;

		try {
			// Try to assign POST values to attributes
			foreach($_POST as $var=>$value) {
				// Does the model have this attribute?
				if($model->hasAttribute($var)) {
					$model->$var = $value;
				} else {
					throw new Exception("Message error", SERVER_ERROR);
				}
			}
			// Try to save the model
			if($model->save()) {
				// Saving was OK
				echo $this->response($init_array);
					
			} else {
				throw new Exception("Message error", SERVER_ERROR);
			}
		}
		catch(Exception $e)
		{
			$this->_set_error($e->getMessage(),$e->getCode());
			echo $this->respone($this->_get_error());
		}
	}
}