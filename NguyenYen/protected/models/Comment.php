<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Comment
 *
 * @author nguyenthihaiyen
 */
class Comment extends CActiveRecord{
    //put your code here
    /**
     * The followings are the available columns in table 'tbl_user':
     * @var integer $id
     * @var string $content 
     * @var integer $status
     * @var integer create_time
     * @var string $author
     * @var string $email
     * @var string $url
     * @var integer $post_id
     */
    /**
     * Returns the static model of the specified AR class.
     * @return CActiveRecord the static model class
     */
    public static function model($className=__CLASS__)
    {
            return parent::model($className);
    }
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
            return '{{comment}}';
    }
    /**
     * @return array relational rules.
     */
    public function relations()
    {
            // NOTE: you may need to adjust the relation name and the related
            // class name for the relations automatically generated below.
            return array(
                    'post' => array(self::BELONGS_TO, 'Post', 'post_id'),
            );
    }
    
    /**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
                        // except=>'PostId' co nghia la luat required deu duoc ap dung chi ngoai tru scenerio 'PostId' 
                        //thi khong co luat required nay
			array('content, status, author,email', 'required', 'except'=> 'CommnetId'),//except nay co nghia la bo qua check validate trong kich ban 'PostId'
			array('status', 'in', 'range'=>array(1,2,3)),//status nam trong khoang (1,2,3)
			
			array('email', 'email'),
                        array('url','url'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('status', 'length', 'min'=>1, 'max'=>3),
			
			
			//validate getPostId API
			array('id', 'numerical', 'integerOnly'=>true, 'on'=> 'CommnetId'),
                        // luat id duoc yeu cau duy nhat chi duoc ap dung co sceneroi 'getPostId' thoi
			//laut nay kiem tra id da ton tai trong database chua
			array('id', 'exist'),
			
		);
	}
    
    
}

?>
