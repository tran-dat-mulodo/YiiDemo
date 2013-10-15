<?php
/**
 *
 * @author letoan
 *
 */

Yii::import('application.controllers.ApiController');

class PostsController extends ApiController
{
	
	public function actionList()
	{
		Yii::log("action is list is called", 'info', 'application.controllers.PostController');
		$cache = Yii::app()->cache;
				
		$cached_data = $cache->get('cached_data');
		
		if($cached_data == false)
		{
			$data = Post::model()->getAllItem();
			$array_data = array('hit_count' => count($data), 'items' => $data);
	 
			//cache data in 1 minute
			Yii::app()->cache->set('cache_data', $array_data, TIME_CACHED_DATA );
		}
		else
		{
			$array_data = $cached_data;
		}
			
		echo $this->response(array_merge($this->init_array, $array_data));

	}
	
	
	public function actionView()
	{			
		if(isset($_GET['id']) && is_numeric($_GET['id']))
		{
			$data = Post::model()->getItembyId($_GET['id']);
			
			if (count($data) >  0)			
			{
				echo $this->response(array_merge($this->init_array, $array_data));
			}
			else
			{
				$error = array('status' => SERVER_ERROR, 'message' => 'Your post is not existed');
				echo $this->response(array_merge($error, $data));
			}
		}

	}

	public function actionUpdate()
	{
		// Get PUT parameters

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
				echo $this->response($this->init_array);
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
				echo $this->response($this->init_array);
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
				echo $this->response($this->init_array);
					
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