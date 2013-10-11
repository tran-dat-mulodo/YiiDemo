<?php

class m131010_043907_sample_data extends CDbMigration
{
	public function up()
	{
		$transaction=$this->getDbConnection()->beginTransaction();
		try
		{
			//insert for tbl_authors
			for( $i=10; $i>0; $i--) {
				$sample_data = array(
						'name'=>"Name $i",
						'email'=>"email{$i}@email.com",
						'password'=>"password{$i}",
						'create_at'=>time(),
						'update_at'=>time(),
				);
				$this->insert('tbl_authors', $sample_data);
			}
			//insert for tbl_notes
			for( $i=10; $i>0; $i--) {
				$sample_data = array(
						'author_id'=>$i,
						'title'=>"Tile $i",
						'content'=>"Here is content of author $i",
						'create_at'=>time(),
						'update_at'=>time(),
				);
				$this->insert('tbl_notes', $sample_data);
			}
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
		echo "m131010_043907_sample_data does not support migration down.\n";
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