<?php 
class Author extends CActiveRecord {

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_authors';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
				array('email,password', 'required'),
				array('name,email,password', 'length',
						'max'=>255,
						'min'=>1),
				array('email','email','checkMX'=>true),
				array('create_at,update_at', 'type', 'type'=>'integer'),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Author the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
