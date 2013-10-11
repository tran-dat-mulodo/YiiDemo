<?php

class Util
{
	public static function createError($error = null){
		return array('errors'=> $error);
	}
}