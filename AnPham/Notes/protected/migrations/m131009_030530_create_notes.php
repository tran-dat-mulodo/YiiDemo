<?php

class m131009_030530_create_notes extends CDbMigration
{
	public function up()
	{
		$transaction=$this->getDbConnection()->beginTransaction();
		try
		{
			$this->createTable('tbl_notes', array(
					'id'=>'pk',
					'author_id'=>'int',
					'title'=>'string',
					'content'=>'string NOT NULL',
					'create_at'=>'datetime',
					'update_at'=>'timestamp',
			));
// 			$this->addForeignKey('note->author', 'tbl_notes', 'author_id', 'tbl_authors', 'id');
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
		echo "m131009_030530_create_notes does not support migration down.\n";
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