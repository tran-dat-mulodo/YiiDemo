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
				Yii::app()->cache->set('cache_data', $data,60 );
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
}