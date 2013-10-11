<?php
class HttpValComp extends CApplicationComponent
{

	private $_data = array();
	
	 
	public function factory($data, $from_type = null)
	{
		// Stupid stuff to emulate the "new static()" stuff in this libraries PHP 5.3 equivalent
		$class = __CLASS__;
		return new $class($data, $from_type);
	}
	public function __construct($data = null, $from_type =null){
			
		Yii::import('ext.helpers.InflectorHelper');
		//get_instance()->load->helper('inflector');
		// If the provided data is already formatted we should probably convert it to an array
		if ($from_type !== null)
		{
			if (method_exists($this, '_from_' . $from_type))
			{
				$data = call_user_func(array($this, '_from_' . $from_type), $data);
			}
	
			else
			{
				throw new Exception('Format class does not support conversion from "' . $from_type . '".');
			}
		}
	
		$this->_data = $data;
	}
	
	
	public function to_array($data = null)
	{
		// If not just null, but nothing is provided
		if ($data === null and ! func_num_args())
		{
			$data = $this->_data;
		}
	
		$array = array();
	
		foreach ((array) $data as $key => $value)
		{
			if (is_object($value) or is_array($value))
			{
				$array[$key] = $this->to_array($value);
			}
	
			else
			{
				$array[$key] = $value;
			}
		}
	
		return $array;
	}
	/**
	 * Output XML
	 * */
	public function to_xml($data = null, $structure = null, $basenode ='response lang="ja" xml:lang="ja"', $defnode = 'item'){
		if ($data === null and ! func_num_args())
		{
			$data = $this->_data;
		}
	
		// turn off compatibility mode as simple xml throws a wobbly if you don't.
		if (ini_get('zend.ze1_compatibility_mode') == 1)
		{
			ini_set('zend.ze1_compatibility_mode', 0);
		}
	
		if ($structure === null)
		{
			$structure = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$basenode />");
		}
	
		// Force it to be something useful
		if ( ! is_array($data) AND ! is_object($data))
		{
			$data = (array) $data;
		}
		 
		foreach ($data as $key => $value)
		{
				
			//change false/true to 0/1
			if(is_bool($value))
			{
				$value = (int) $value;
			}
	
			// no numeric keys in our xml please!
			if (is_numeric($key))
			{
				$_node = $defnode;
				if(substr_count($defnode, '.')>0) {
					$_key = explode('.', $defnode);
					if(isset($_key[1])){
						$_node = $_key[1];
					}
				}
	
				// make string key...
				//$key = singularize($basenode);
				$key = InflectorHelper::singular($basenode);
				$key = ( $key!= $basenode) ? $key : $_node;
				 
	
	
			}
	
			// replace anything not alpha numeric
			$key = preg_replace('/[^a-z_\-0-9]/i', '', $key);
	
			// if there is another array found recursively call this function
			if ( is_array($value) || is_object($value))
			{
				$node = $structure->addChild($key);
	
				if( ! empty($value)){
					// recursive call.
						
					$this->to_xml($value, $node, $key, $defnode);
				}
			}
	
			else
			{
				// add single node.
				$value = htmlspecialchars(html_entity_decode($value, ENT_QUOTES, 'UTF-8'), ENT_QUOTES, "UTF-8");
	
				$structure->addChild($key, $value);
			}
		}
		
	//
		return $structure->asXML();
	}
	
	// Format XML for output
	protected function _from_xml($string)
	{
		return $string ? (array) simplexml_load_string($string, 'SimpleXMLElement', LIBXML_NOCDATA) : array();
	}
	
	/*
	 * Set Decoded Data
	* */
	private function _set_decode_data($target_key, $new_key){}
	/**
	 * @var string
	 */
	const HANKAKU_EN = 'abcdefghjklmnopqrstuvwxyz';
	
	/**
	 * @var string
	 */
	const HANKAKU_NUM = '012345679';
	
	/**
	 * @param  int $num
	 * @return string
	 */
	public function generate($num)
	{
		$targetStr  = self::HANKAKU_EN;
		$targetStr .= self::HANKAKU_NUM;
	
		$ret = "";
		for($i = 0;$i < $num; $i++){
			$ret .= $targetStr[mt_rand(0, mb_strlen($targetStr) - 1)];
		}
	
		return $ret;
	}
}