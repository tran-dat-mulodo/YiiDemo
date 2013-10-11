<?php 

class ApiBase extends CController {

	/*
	 * define the rest format
	 * */
	protected $rest_format = NULL;

	/**
	 * Defines the list of method properties such as limit, log and level
	 *
	 * @var array
	 */
	protected $methods = array();

	/**
	 * List of allowed HTTP methods
	 *
	 * @var array
	 */
	protected $allowed_http_methods = array('get','post','put','delete');

	/**
	 * General request data and information.
	 * Stores accept, language, body, headers, etc.
	 *
	 * @var object
	 */
	protected $request = NULL;

	/**
	 * What is gonna happen in output?
	 *
	 * @var object
	 */
	protected $response = NULL;

	/**
	 * Stores DB, keys, key level, etc
	 *
	 * @var object
	 */
	protected $rest = NULL;

	/**
	 * The arguments for the GET request method
	 *
	 * @var array
	 */
	protected $_get_args = array();

	/**
	 * The arguments for the POST request method
	 *
	 * @var array
	 */
	protected $_post_args = array();

	/**
	 * The arguments for the PUT request method
	 *
	 * @var array
	 */
	protected $_put_args = array();

	/**
	 * The arguments for the DELETE request method
	 *
	 * @var array
	 */
	protected $_delete_args = array();

	/**
	 * The arguments from GET, POST, PUT, DELETE request methods combined.
	 *
	 * @var array
	 */
	protected $_args = array();

	/**
	 * If the request is allowed based on the API key provided.
	 *
	 * @var boolean
	 */
	protected $_allow = TRUE;

	/**
	 * Determines if output compression is enabled
	 *
	 * @var boolean
	 */
	protected $_zlib_oc = FALSE;

	/**
	 * The LDAP Distinguished Name of the User post authentication
	 *
	 * @var string
	*/
	protected $_user_ldap_dn = '';

	/**
	 * List all supported methods, the first will be the default format
	 *
	 * @var array
	 */
	protected $_supported_formats = array(
		'xml' => 'application/xml',
		'json' => 'application/json',
		'jsonp' => 'application/javascript',
		'serialized' => 'application/vnd.php.serialized',
		'html' => 'text/html'
	);
	
	//Logging access API
	private $_log;

	/**
	 * List all error method
	 * */
	protected $_arr_error= array();


	/**
	 * Developers can extend this class and add a check in here.
	 */
	//abstract protected function validate();

    /**
     * Developers can extend this class and add a check in here.
     *
     * This is class main action in child controller
     */
    //abstract public function do_action();

	/*
	 * Mark place to calculate the elapsed time between start place and end
	 */

	//abstract protected function mark($str);
	
	public function __construct()
	{
		//var_dump(Yii::app()); die;
		//parent::__construct();
		

		
// 		if($this->config->item('is_logged') === TRUE)
// 			require_once APPPATH . 'libraries/APILog.php';
		
		$this->_zlib_oc = @ini_get('zlib.output_compression');

		// Lets grab the config and get ready to party
		//$this->load->config('api');

		// let's learn about the request
		$this->request = new stdClass();
		
		// Is it over SSL?
		$this->request->ssl = $this->_detect_ssl();
		
		// How is this request being made? POST, DELETE, GET, PUT?
		$this->request->method = $this->_detect_method();
		
		// Create argument container, if nonexistent
// 		if ( ! isset($this->{'_'.$this->request->method.'_args'}))
// 		{
// 			$this->{'_'.$this->request->method.'_args'} = array();
// 		}
		

		// Set up our GET variables
		//$this->_get_args = array_merge($this->_get_args, $this->uri->ruri_to_assoc());
		
		//$this->load->library('security');
		
		// This library is bundled with APIBase, but will eventually be part of CodeIgniter itself
		//$this->load->library('HttpVal');
		

		// Try to find a format for the request (means we have a request body)
		$this->request->format = $this->_detect_input_format();
		
		// Some Methods cant have a body
		$this->request->body = NULL;

		//$this->{'_parse_' . $this->request->method}();
		
		// Now we know all about our request, let's try and parse the body if it exists
		if ($this->request->format and $this->request->body)
		{
			$this->request->body = Yii::app()->httpval->factory($this->request->body, $this->request->format)->to_array();
			// Assign payload arguments to proper method container
			$this->{'_'.$this->request->method.'_args'} = $this->request->body;
		}
		
		// Merge both for one mega-args variable
		//$this->_args = array_merge($this->_get_args, $this->_put_args, $this->_post_args, $this->_delete_args, $this->{'_'.$this->request->method.'_args'});

		// Which format should the data be returned in?
		$this->response = new stdClass();
		$this->response->format = $this->_detect_out_format();

		// Check if there is a specific auth type for the current class/method
		//$this->auth_override = $this->_auth_override_check();
		
// 		if ($this->config->item('api_auth') == 'basic')
// 		{
// 			$this->_prepare_basic_auth();
// 		}

		$this->rest = new StdClass();
		// Load DB if its enabled


		// Checking for keys? GET TO WORK!
		//if (config_item('api_enable_keys'))
// 		{
// 			$this->_allow = $this->_detect_api_key();
// 		}
		
	}




	public function response($data = array(), $http_code = null,$defnode = 'item')
	{
		//mark end for do_
		//$this->mark('do_action_end');//var_dump($this->_arr_error);die;
		global $CFG;
		
		// If data is empty and not code provide, error and bail
		if (empty($data) && $http_code === null)
		{
			$http_code = 404;
			
			// create the output variable here in the case of $this->response(array());
			$output = NULL;
			
		}		
		// If data is empty but http code provided, keep the output empty
		else if (empty($data) && is_numeric($http_code))
		{
			$output = NULL;
		}

		// Otherwise (if no data but 200 provided) or some data, carry on camping!
		else
		{
			// Is compression requested?
// 			if ($CFG->item('compress_output') === TRUE && $this->_zlib_oc == FALSE)
// 			{
// 				if (extension_loaded('zlib'))
// 				{
// 					if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) AND strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== FALSE)
// 					{
// 						ob_start('ob_gzhandler');
// 					}
// 				}
// 			}
			
			is_numeric($http_code) OR $http_code = 200;
			
			// If the format method exists, call and return the output in that format
			
			if (method_exists($this, '_format_'.$this->response->format))
			{
				// Set the correct format header
				header('Content-Type: '.$this->_supported_formats[$this->response->format]);

				$output= $this->{'_format_'.$this->response->format}($data);
				
			}			
			// If the format method exists, call and return the output in that format
			elseif (method_exists(Yii::app()->httpval, 'to_'.$this->response->format))
			{
				
				// Set the correct format header				
				header('Content-Type: '.$this->_supported_formats[$this->response->format]);

				//show info benchmark if flag_profiler is TRUE				
				$info_root = 'response lang="ja" xml:lang="ja"';

 				// remove empty-unrequrire tag
				//$this->filter_empty_tag($data);
				
				$output= Yii::app()->httpval->factory($data)->{'to_'.$this->response->format}($data, null,$info_root,$defnode);
				
				
			}

			// Format not supported, output directly
			else
			{
				
				$output= $data;
			}
		}
		
		$output = trim($output);
		//var_dump($output); die;
		//$retmsg = $this -> get_errorString();
		//$this->log_messages('DEBUG', $retmsg);
			
// 		if( $this->config->item('is_logged') && isset( $this->_log)){
// 			$this->_log->flush_data_to_file();
// 		}
				
		
		header('HTTP/1.1: ' . 200);
		header('Status: ' . 200);
		
		// If zlib.output_compression is enabled it will compress the output,
		// but it will not modify the content-length header to compensate for
		// the reduction, causing the browser to hang waiting for more data.
		// We'll just skip content-length in those cases.
// 		if ( ! $this->_zlib_oc && ! $CFG->item('compress_output'))
// 		{
// 			header('Content-Length: ' . strlen($output));
// 		}
		
		header('Content-Length: ' . strlen($output));
				//var_dump($output); die;		
		return $output;

	}


	/*
	 * Detect SSL
	 * */
	 private function _detect_ssl(){
	 	return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on');
	 }

	 private function _detect_input_format()
	 {
	 	
		if (isset($_SERVER['CONTENT_TYPE']))
		{			
			// Check all formats against the HTTP_ACCEPT header
			foreach ($this->_supported_formats as $format => $mime)
			{
				if (strpos($match = $_SERVER['CONTENT_TYPE'], ';'))
				{
					$match = current(explode(';', $match));
				}

				if ($match == $mime)
				{
					return $format;
				}
			}
		}

		return NULL;
	 }

	 /*
	  * Check detect ouput format, currenly ussing format XML
	  * @return: string
	  * */
	  private function _detect_out_format(){
	  	return 'xml';
	  }

	  /*
	   * Detect method
	   * @return: string
	   * */
	   private function _detect_method(){
	   	
	   		$method = strtolower($_SERVER['REQUEST_METHOD']);
			if(in_array($method, $this->allowed_http_methods) && method_exists($this, '_parse_' . $method)){
				return $method;
			}
			return 'get';
	   }

	   /*
	    * Detect API Key
	    * @return: boolean
	    * Update comming soon
	    * */
	   protected function _detect_api_key(){
	   		return FALSE;
	   }

	   protected function _parse_get(){
	   		if (isset($_SERVER['REQUEST_URI'])) {
	   			parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $get);
				$this->_get_args = array_merge($this->_get_args, $get);
	   		}
	   }

	   protected function _parse_post(){}

	   public function get($key, $xss_clean=TRUE){
		   	if ($key === NULL)
			{
				return $this->_get_args;
			}

			return array_key_exists($key, $this->_get_args) ? $this->_xss_clean($this->_get_args[$key], $xss_clean) : FALSE;
	   }

	   public function post($key, $xss_clean=TRUE){}

	   private function _check_login($uid = '', $pass = NULL){
			if (empty($uid))
			{
				return FALSE;
			}

			$auth_source = strtolower($this->config->item('auth_source'));

			if ($auth_source == 'ldap')
			{
				log_messages('debug', 'performing LDAP authentication for $username');
				return $this->_perform_ldap_auth($uid, $pass);
			}

			$valid_logins = & $this->config->item('api_valid_logins');

			if ( ! array_key_exists($uid, $valid_logins))
			{
				return FALSE;
			}

			// If actually NULL (not empty string) then do not check it
			if ($pass !== NULL AND $valid_logins[$uid] != $pass)
			{
				return FALSE;
			}

			return TRUE;
	   }

	   private function _prepare_basic_auth(){
	   		$uid = NULL;
	   		$pass = NULL;
			if ($this->input->server('PHP_AUTH_USER'))
			{
				$uid = $this->input->server('PHP_AUTH_USER');
				$pass = $this->input->server('PHP_AUTH_PW');
			}

			// most other servers
			elseif ($this->input->server('HTTP_AUTHENTICATION'))
			{
				if (strpos(strtolower($this->input->server('HTTP_AUTHENTICATION')), 'basic') === 0)
				{
					list($uid, $pass) = explode(':', base64_decode(substr($this->input->server('HTTP_AUTHORIZATION'), 6)));
				}
			}

			if ( ! $this->_check_login($uid, $pass))
			{
				$this->_force_login();
			}
	   }

	   protected function _force_login($nonce = '')
		{
			if ($this->config->item('api_auth') == 'basic')
			{
				header('WWW-Authenticate: Basic Realm="'.$this->config->item('api_realm').'"');
			}

			$this->response(array('status' => false, 'error' => 'Not authorized'), 401);
		}

		/**
		 * set error value
		 * @param: value is array ('status'=>code,'message'=>error_msg)
         *
         * It don't' use.
		 *
		 * */
		protected function set_error($item = null){
			if(empty($item) || $item ===null) return $this;
			else{
				//if input is array
				if(is_array($item) || is_object($item)){
					foreach ($item as $k => $v) {
						$this->_arr_error[]= array('error'=>array('status'=> $this->set_status_code($k),'message'=>$v));
						return $this;
					}
				}
			}
		}
        protected function _set_error($status = 200, $msg = ''){
            if(is_numeric($status)){
                $msg = (is_string($msg)? $msg: 'Unable to access an error message corresponding to your code.');

                $this->_arr_error=array('error'=> array('status'=>$status,'message'=>$msg));

            }
            return $this->_arr_error;
        }
        /**
         * set status error code
         *
         * @param code is string
         * */
        protected function set_status_code($code = 'DEF'){
            $fcode = '_'.$code.'_';

            if(!defined($fcode))
                return constant('_REQUEST_ERROR_');

            return constant($fcode);
        }

        /**
         * @param  status code is string
         * @return array
         * */
		protected function get_error($code = null){
		    if($code !== null && (is_string($code) || is_numeric($code))){
		        $this->_arr_error= array('error'=>array('status'=> $code,'message'=>$this->set_status_code($code)));
		    }
			return $this->_arr_error;
		}
		
		/**
         * @param  status code is string
         * @return array
         * */
		protected function get_errorString(){
			$ret = '';
			if (isset($this->_arr_error) && count($this->_arr_error) > 0) {
				foreach ($this->_arr_error as $val) {
					$ret .= $val['status'] . '=>' . $val['message'] . chr(10);
				}
			} else {
				$ret = '200';
			}
			return $ret;
		}
		
        /**
         * get status code
         *
         * @param $item is array
         * @return string
         * */
		protected function get_status_code($item = null){
			if(empty($item) || $item===null){
			     $this->_arr_error[]=array('status' => _REQUEST_ERROR_,'message'=>$this->lang->line('_REQUEST_ERROR_'));
			}else{
				if(is_array($item) || is_object($item)){
				    $_code =0;
					foreach ($item as $k => $v) {
					    $_code = $this->set_status_code($k);
					    $this->_arr_error[]=array('status' => $_code,'message'=>$v);

					}

                    return $_code;
				}
			}

			return constant('_REQUEST_ERROR_');
		}

	 
	
}