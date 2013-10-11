<?php

class m131009_030516_create_authors extends CDbMigration
{
	public function up()
	{
		$transaction=$this->getDbConnection()->beginTransaction();
		try
		{
			$this->createTable('tbl_authors', array(
					'id'=>'pk',
					'name'=>'string',
					'email'=>'string NOT NULL',
					'password'=>'string',
					'create_at'=>'datetime',
					'update_at'=>'timestamp',
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
		echo "m131009_030516_create_authors does not support migration down.\n";
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