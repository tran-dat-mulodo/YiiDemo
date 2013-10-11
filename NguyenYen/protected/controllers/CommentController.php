<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CommentController
 *
 * @author nguyenthihaiyen
 */

class CommentController extends ApiController{
    //put your code here
    //create api get list all comments
    public function actionList(){
        
        $models = Comment::model()->findAll();
        //var_dump($models);die;
        $row = array();
        foreach ($models as $model)
            $rows[] = $model->attributes;
                $data_response = array(
                    'error'=>array('status'=>200, 'message'=>''),
                    'datas'=>$rows
                );
         // var_dump($data_response);die;
         $this->response($data_response);
    }
    
    //create api view comment by id
//    public function actionView(){
//        echo "jgdh";die;
//        if(isset($_GET['id']))
//            $id = $_GET['id'];
//        else {
//            $data = array('error'=>array('status'=>204,'message'=>'no content'));
//            $this->response($data);
//        }
//        //validate params
//        $model = Comment::model();
//        $model->scenario = 'CommnetId'; // create scenario name
//	$model->id = $id; // set value for id
//	if (!$model->validate()){
//		$result = $this->get_error_validates($model->getErrors());
//		$this->response($result, 'errors');
//	}
//	$data = $model->findByPk($id);
//	if(empty($data)) 	
//        $this->response(array('error'=>array('status'=>204, 'message'=>'No Content')));
//        else {
//        $data_response = array(
//		'error'=> array('status'=>200, 'message'=>''),
//		'datas'=>$data->attributes
//		);
//	$this->response($data_response);	
//	}
//        
//       
//            
//    }
    
}

?>
