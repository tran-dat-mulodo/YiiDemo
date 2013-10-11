<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of getAllcommentController
 *
 * @author nguyenthihaiyen
 */
class getAllCommentController extends ApiController{
    //put your code here
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
}

?>
