<?php
class PostIdController extends ApiController {
	
	public function filters() {
		return array('accessControl', // perform access control for CRUD operations
		);
	}
	
    /* Shows a single item using GET method
     * 
     * @access public
	 * @param $_GET['id'] // is Numberic
     * @return void
     */
    public function actionView()
    {
        #$this->check_auth();
        
        //Get params data
        if (isset($_GET['id']))
        	$id = $_GET['id'];
		else 
			$this->response(array('error'=>array('status'=>204, 'message'=>'No Content')));
        
		//validate params
		$model = Post::model();
		$model->scenario = 'PostId'; // create scenario name
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
        $id = $this->check_auth();
		
        $model = new Post();
        //get data from POST http
        $post_params = array();
        $post_params['title'] = (isset($_POST['title']))? $_POST['title'] : null;
		$post_params['content'] = (isset($_POST['content']))? $_POST['content'] : null;
		$post_params['tags'] = (isset($_POST['tags']))? $_POST['tags'] : null;
		$post_params['status'] = (isset($_POST['status']))? $_POST['status'] : null;
		$post_params['author_id'] = $id;
        foreach($post_params as $key=>$value) {
            // Does the model have this attribute?
            if ($model->hasAttribute($key)) {
                $model->$key = rawurldecode($value);
            } 
            else 
				$this->response(array('error'=>array('status'=>204, 'message'=>'No Content')));
		}
		
		if ($model->save()) {
            // Saving was OK
            $data_response = array(
					'error'=> array('status'=>200, 'message'=>''),
					'datas'=>$model->attributes
				);
			$this->response($data_response);
        } 
        else {
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
    public function actionUpdate() {
        //check login
        $userid = $this->check_auth();
		
		$post_params = null;
        //get data from PUT http
        parse_str(file_get_contents('php://input'), $post_params);
		$post_params['author_id'] = $userid;
		$id = (int) $post_params['id'];
		unset($post_params['id']);
		
		//validate params id
		$model = new Post();
		$model->scenario = 'PostId'; // create scenario name
		$model->id = $id; // set value for id
		if (!$model->validate()){
			$result = $this->get_error_validates($model->getErrors());
			$this->response($result, 'errors');
		}
		//find item with $id
		$model = $model->findByPk($id);
		if(empty($model)) 	
            $this->response(array('error'=>array('status'=>204, 'message'=>'No Content')));
		
		//Set value for propeties
		if (!empty($post_params)) {
	        foreach($post_params as $key=>$value) {
	            // Does the model have this attribute?
	            if ($model->hasAttribute($key)) {
	                $model->$key = rawurldecode($value);
	            } 
	            else 
					$this->response(array('error'=>array('status'=>204, 'message'=>'No Content')));
			}
			if ($model->save()) {
	            // Saving was OK
	            $data_response = array(
						'error'=> array('status'=>200, 'message'=>''),
						'datas'=>$model->attributes
					);
				$this->response($data_response);
	        } 
	        else {
	        	$result = $this->get_error_validates($model->errors);
				$this->response($result, 'errors');
			}
		}
		else 
			$this->response(array('error'=>array('status'=>204, 'message'=>'No Content')));		
    } 

	/* Delete item is exist using DELETE method
     * 
     * @access public
	 * @param int $id
     * @return void
     */
    public function actionDelete() {
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
		if (!$model->validate()){
			$result = $this->get_error_validates($model->getErrors());
			$this->response($result, 'errors');
		}
		//find item with $id
		$model = $model->findByPk($id);
		if(empty($model)) 	
            $this->response(array('error'=>array('status'=>204, 'message'=>'No Content')));
		
		$num = $model->delete();
		if ($num > 0)
			$this->response(array('error'=> array('status'=>200, 'message'=>'')));
		else
			$this->response(array('error'=> array('status'=>500, 'message'=>'Internal Server Error')));
	        	
    } 
}
?>