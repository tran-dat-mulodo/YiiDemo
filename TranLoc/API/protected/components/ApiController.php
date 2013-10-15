<?php
/**
 * ApiController
 *
 * @uses Controller
 * @author Nguyen.Sang
 */
class ApiController extends Controller {
	/**
	 * Key which has to be in HTTP USERNAME and PASSWORD headers
	 */
	Const APPLICATION_ID = 'ASCCPE';

	/**
	 * status code response
	 */
	protected $status_code = 200;

	/**
	 * status message response
	 */
	protected $status_message = '';

	/**
	 * response format
	 */
	protected $format = 'xml';

	/**
	 * List all supported format
	 * @var array
	 */
	protected $supported_formats = array(
					'xml' => 'application/xml', 
					'json' => 'application/json',
					'txt' => 'text/html',
					'html' => 'text/html',
					);

	/**
	 * @return array action filters
	 */
	public function filters() {
		return array();
	}
	
	/**
	 * set http header for response
	 * @param
	 * @return void
	 */
	private function set_headers() {
		header("HTTP/1.1 " . $this -> status_code . " " . $this -> status_message);
		header('Content-Type: ' . $this -> supported_formats[$this -> format] . '; charset=UTF-8');
	}

	/**
	 * Get format from http request
	 * @return false|format
	 */
	 private function get_format() {
		 if (isset($_GET['exts']))
		 	return $_GET['exts'];
		 else 
		 	return false;
	 }
	
	/**
	 * Get errors list from validate
	 * @param array $errors
	 * @return $error_list
	 */
	 protected function get_error_validates($errors) {
	 	if (is_array($errors)){
	 		foreach ($errors as $key => $value) {
				if (is_array($value)) {
					foreach ($value as $key_v => $value_v) {
						$temp['status'] = 400;
						$temp['message'] = $value_v;
						$result[] = $temp;
						unset($temp); 
					}
				}
			}
			return $result;
	 	}
		return false;
	 }
	
	/**
	 * Response result with format in $this->format
	 *
	 * @param array $data
	 * @param int $status
	 * @param string $message
	 * @param string $node_name_data
	 * @return response result
	 */
	public function response($data = null, $node_name_data = null, $status = null) {
		$node_name_data = (!empty($node_name_data))? $node_name_data : 'response';
		$this -> status_code = (!empty($status)) ? $status : 200;
		$this -> status_message = $this -> get_status_message($this -> status_code);
		
		//get response format from http request
		$format = $this -> get_format();
		if(!empty($format))
            $this -> format = $format;
		else 
			$this -> format = 'xml';
		
		//set http header
		$this -> set_headers();
		//$response = null;
		
		if (!empty($data))
			$response = $data;
		else if ($this->status_code != 200)
			$response = array('error'=>array('status'=>$this->status_code, 'message'=>$this->status_message));
		//response
		if ($this -> format == "xml")
			$result = $this -> to_xml($response, $node_name_data);
		else {
			$result[$node_name_data] = $response;
			$result = json_encode($result);
		}
		echo $result;
		exit;
	}

	/**
	 * Checks if a request is authorized
	 *
	 * @access private
	 * @return void
	 */
	public function check_auth() {
		$authorization = getallheaders();
		$authorization = explode(" ", $authorization['Authorization']);
		$authorization = explode(":", base64_decode($authorization[1]));
		
		if (!empty($authorization)) {
			$username = $authorization[0];
			$password = $authorization[1];
		}
		else {
			// Error: Unauthorized
			$this -> response('', '', 401);
		}
		
		// Find the user
		$user = User::model() -> find('LOWER(username)=?', array(strtolower($username)));
		if ($user === null) {
			// Error: Unauthorized
			$this -> response('', '', 401);
		} 
		else if ($user->validatePassword($password) == false) {
			// Error: Unauthorized
			$this -> response('', '', 401);
		}
		return $user->id;
	}

	/**
	 * Gets the message for a status code
	 *
	 * @param mixed $status
	 * @access private
	 * @return string
	 */
	private function get_status_message($status) {
		if (empty($status))
			return '';
		// for an example
		$codes = array(
			100 => 'Continue', 
			101 => 'Switching Protocols', 
			200 => 'OK', 
			201 => 'Created', 
			202 => 'Accepted', 
			203 => 'Non-Authoritative Information', 
			204 => 'No Content', 
			205 => 'Reset Content', 
			206 => 'Partial Content', 
			300 => 'Multiple Choices', 
			301 => 'Moved Permanently', 
			302 => 'Found', 
			303 => 'See Other', 
			304 => 'Not Modified', 
			305 => 'Use Proxy', 
			306 => '(Unused)', 
			307 => 'Temporary Redirect', 
			400 => 'Bad Request', 
			401 => 'Unauthorized', 
			402 => 'Payment Required', 
			403 => 'Forbidden', 
			404 => 'Not Found', 
			405 => 'Method Not Allowed', 
			406 => 'Not Acceptable', 
			407 => 'Proxy Authentication Required', 
			408 => 'Request Timeout', 
			409 => 'Conflict', 
			410 => 'Gone', 
			411 => 'Length Required', 
			412 => 'Precondition Failed', 
			413 => 'Request Entity Too Large', 
			414 => 'Request-URI Too Long', 
			415 => 'Unsupported Media Type', 
			416 => 'Requested Range Not Satisfiable', 
			417 => 'Expectation Failed', 
			500 => 'Internal Server Error', 
			501 => 'Not Implemented', 
			502 => 'Bad Gateway', 
			503 => 'Service Unavailable', 
			504 => 'Gateway Timeout', 
			505 => 'HTTP Version Not Supported');

		return (isset($codes[$status])) ? $codes[$status] : '';
	}

	/**
	 * The main function for converting to an XML document.
	 * Pass in a multi dimensional array and this recrusively loops through and builds up an XML document.
	 *
	 * @param array $data
	 * @param string $rootNodeName - what you want the root node to be - defaultsto data.
	 * @param SimpleXMLElement $xml - should only be used recursively
	 * @return string XML
	 */
	public function to_xml($data, $rootNodeName = 'response', $xml = null) {
		if ($xml == null)
			$xml = simplexml_load_string("<?xml version=\"1.0\" encoding=\"UTF-8\"?><$rootNodeName />");

		// loop through the data passed in.
		foreach ($data as $key => $value) {
			// no numeric keys in our xml please!
			if (is_numeric($key)) {
				// make string key...
				//$key = $rootNodeName . "_" . $key;
				$key = substr($rootNodeName,0, strlen($rootNodeName)-1);
			}

			// if there is another array found recrusively call this function
			if (is_array($value)) {
				$node = $xml -> addChild($key);
				// recrusive call.
				$this -> to_xml($value, $key, $node);
			} else {
				// add single node.
				//$value = htmlentities($value, ENT_QUOTES, 'UTF-8');
				$xml -> addChild($key, $value);
			}

		}
		// pass back as string. or simple xml object if you want!
		return $xml -> asXML();
	}
        
        public function _getObjectEncoded($model, $array)
    {
        if($this->format =='json')
            return CJSON::encode($array);
        else if($this->format =='xml') {
            $result = '<?xml version="1.0" encoding="utf-8"?>';
            $result .= "\n<$model>\n";
            foreach ($array as $params) {
                foreach($params as $key=>$value)
                    $result .= "<".$key.">".$value."</".$key.">\n";
            }
                
            $result .= '</'.$model.'>';
            return $result;
        }
        else
            return;
    }
    
    public function _sendResponse($status = 200, $body = '', $content_type = 'text/html') {
        $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
        // set the status
        header($status_header);
        // set the content type
        header('Content-type: ' . $this->supported_formats[$this->format]);

        // pages with body are easy
        if ($body != '') {
            // send the body
            echo $body;
            exit;
        }
        // we need to create the body if none is passed
        else {
            // create some body messages
            $message = '';

            // this is purely optional, but makes the pages a little nicer to read
            // for your users.  Since you won't likely send a lot of different status codes,
            // this also shouldn't be too ponderous to maintain
            switch ($status) {
                case 401:
                    $message = 'You must be authorized to view this page.';
                    break;
                case 404:
                    $message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
                    break;
                case 500:
                    $message = 'The server encountered an error processing your request.';
                    break;
                case 501:
                    $message = 'The requested method is not implemented.';
                    break;
            }

            // servers don't always have a signature turned on (this is an apache directive "ServerSignature On")
            $signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];

            // this should be templatized in a real-world solution
            $body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
                        <html>
                            <head>
                                <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
                                <title>' . $status . ' ' . $this->_getStatusCodeMessage($status) . '</title>
                            </head>
                            <body>
                                <h1>' . $this->_getStatusCodeMessage($status) . '</h1>
                                <p>' . $message . '</p>
                                <hr />
                                <address>' . $signature . '</address>
                            </body>
                        </html>';

            echo $body;
            exit;
        }
    }
    
    public function _getStatusCodeMessage($status) {
        // these could be stored in a .ini file and loaded
        // via parse_ini_file()... however, this will suffice
        // for an example
        $codes = Array(
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => '(Unused)',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported'
        );

        return (isset($codes[$status])) ? $codes[$status] : '';
    }
}
?>
