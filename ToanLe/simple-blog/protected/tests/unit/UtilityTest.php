<?php
/**
 * 
 * @author letoan
 *
 */
class UtilityTest 
{	
	public $domDocument;
	public function http_post($url, $data)
	{
		$curl = curl_init();
		//curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $url,
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => $data,
			CURLOPT_TIMEOUT => 5,
			CURLOPT_CONNECTTIMEOUT => 10,			
		));
						
		$result = curl_exec($curl);		
		$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);		
		//var_dump(curl_getinfo($curl));
		curl_close($curl);
		return array('code' => $http_code, 'data' =>  $result);		
	}
	
	public function http_get($url, $data = FALSE)
	{
		$curl = curl_init();
		if($data !== FALSE)
		{
			$query_data = http_build_query($data);
			curl_setopt($curl, CURLOPT_URL, $url. $query_data);
		}
		else
		{
			curl_setopt($curl, CURLOPT_URL, $url);
		}
		
		curl_setopt_array($curl, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => $url,		
		CURLOPT_TIMEOUT => 5,
		CURLOPT_CONNECTTIMEOUT => 10,
		));
	
		$result = curl_exec($curl);
		$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);		
		//var_dump(curl_getinfo($curl));
		curl_close($curl);
		return array('code' => $http_code, 'data' =>  $result);	
	}
	
	public function http_put($url, $data)
	{
		$curl = curl_init();
		$query_data = http_build_query($data);
		curl_setopt($curl, CURLOPT_URL);
		curl_setopt_array($curl, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => $url,
		CURLOPT_CUSTOMREQUEST => 'PUT',
		CURLOPT_POSTFIELDS => $data,
		CURLOPT_TIMEOUT => 5,
		CURLOPT_CONNECTTIMEOUT => 10,
		));
	
		$result = curl_exec($curl);
		$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);		
		//var_dump(curl_getinfo($curl));
		curl_close($curl);
		return array('code' => $http_code, 'data' =>  $result);
	}
	
	
	 function http_delete($url, $data)
	{
		$curl = curl_init();
		$query_data = http_build_query($data);
		//curl_setopt($curl, CURLOPT_URL, $url. $query_data);
		curl_setopt_array($curl, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => $url,
		CURLOPT_TIMEOUT => 5,
		CURLOPT_CONNECTTIMEOUT => 10,
		));
	
		$result = curl_exec($curl);
		$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);		

		curl_close($curl);
		return array('code' => $http_code, 'data' =>  $result);
	}
	
	/**
	 * 
	 * @param string $url
	 * @param array $data
	 * @param string $method the default value is "get" method
	 * @return array DomDocument contain xml string  if sucessfull or FALSE otherwise
	 */
	public function get_response($url, $data = FALSE, $method = 'get')
	{
		$method = 'http_'.$method;
		$result = $this->{$method}($url, $data);
		
		
		if($result['code'] === SUCCESS_HTTP)
		{
			return $this->loadResponse($result['data']);
		}
		else
		{
			return FALSE;
		}
	}
	/**
	 * 
	 * @param string xml string
	 * @return DOMDocument
	 */
	public  function loadResponse($response) {
		$domDocument = DOMDocument::loadXML($response);
		return $domDocument;		
	}
}