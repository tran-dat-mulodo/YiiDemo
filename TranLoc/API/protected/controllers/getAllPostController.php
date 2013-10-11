<?php
include 'ApiController.php';

class getAllPostController extends ApiController {
	
	/* Shows all item in table post
     * 
     * @access public
     * @return void
     */
    public function actionList() {
        $models = Post::model()->findAll();
        $rows = array();
        foreach($models as $model) 
            $rows[] = $model->attributes;
		$data_response = array(
			'error'=> array('status'=>200, 'message'=>''),
			'datas'=>$rows
		);
        $this->response($data_response);
    } 
}
?>