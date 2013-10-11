<?php 
abstract class APIController extends CController {

	private $_responseType = 'xml';

	private $_responseHeader = array(
			'status' => 200,
			'messages' => array(),
	);

	protected function sendResponse( $inputs = null, $node_name = 'value') {
		$response = null;
		$data = array(
				'hearder' => $this->_responseHeader,
		);
		if( !empty($inputs))
			$data["{$node_name}s"] = array( $node_name=>$inputs);

		if( $this->_responseType == 'xml') {
			header ("Content-Type:application/xml");
			$response = Array2XML::createXML('response', $data)->saveXML();

		} else {
			header ("Content-Type:application/json");
			$response = CJSON::encode( $data);
		}
		echo $response;
	}

	protected function setHeader( $status = 200, $messages = '') {
		$this->_responseHeader['status'] = $status;
		$this->_responseHeader['messages'] = $messages;
	}

	protected function convernAR2Array( $AR_data) {
		$data = array();
		foreach( $AR_data as $record) {
			$row = $record->getAttributes();
			$row['create_at'] = date('Y-m-d h:m:s', $row['create_at']);
			$row['update_at'] = date('Y-m-d h:m:s', $row['update_at']);
			$data[] = $row;
		}
		return $data;
	}
}