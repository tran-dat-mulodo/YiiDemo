<?php 
class Log extends CActiveRecord {
	public function tableName() {
		return 'tbl_logging_log';
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}


