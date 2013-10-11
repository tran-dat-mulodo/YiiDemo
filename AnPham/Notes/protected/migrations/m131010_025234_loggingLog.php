<?php

class m131010_025234_loggingLog extends CDbMigration
{
	public function up()
	{
		$transaction=$this->getDbConnection()->beginTransaction();
		try
		{
			$this->createTable('tbl_logging_log', array(
					'email'=>'string',
					'hash_code'=>'string',
					'create_at'=>'timestamp',
			));
			$transaction->commit();
		}
		catch(Exception $e)
		{
			echo "Exception: ".$e->getMessage()."\n";
			$transaction->rollback();
			return false;
		}
	}

	public function down()
	{
		echo "m131010_025234_loggingLog does not support migration down.\n";
		return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}