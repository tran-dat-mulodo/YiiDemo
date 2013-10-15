<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CommentControllerTest
 *
 * @author nguyenthihaiyen
 */
class CommentControllerTest extends CDbTestCase{
    //put your code here
    public $fixtures = array(
        'comments' => ':tbl_comment'
    );
    
    public function testSaveModel() {
// 		echo '<pre>';
// 		var_dump(self::$id);
// 		echo '</pre>';die;
        
	var_dump($this->comments);
	}
    
}

?>
