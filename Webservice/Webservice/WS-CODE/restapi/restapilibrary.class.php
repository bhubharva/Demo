<?php
/**
 * Class that includes all the REST API response/reply methods.
 * @author	: Krishna Komarpant @Cybage Team
 * @date	: 31-May-2013
 */

require_once(CFG_LIB_PLUGINS.'xmlprocessor/xmlbuildercdata.php');
require_once(CFG_LIB_PLUGINS.'xmlprocessor/xmlparser2.php');

define('USAP_XML_TEXT_HEADER', '<?xml version="1.0" encoding="UTF-8"?>' . "\n");
class clsRestApiLibrary {
	public $objDataCleaner;
	
	function __construct() {
		//do nothing constructor
	}
	
	public function replyBack($orig_api_call, $msg, $code=1, $data_dump="", $client_id ='', $src_serial_id ='')
	{
		if (empty($code) || ($code <= 0))
		$code = 1;

		$serial_id =  time() . '-' . rand(10,1000);

		$data = USAP_XML_TEXT_HEADER;
		$data .= "<" . $orig_api_call . "-reply> " . NEWLINE .
 				 " <serial-id>" . $serial_id . "</serial-id>" . NEWLINE ;

		// " <reply-to-call>" . $orig_api_call . "</reply-to-call>" . NEWLINE .

		if (!empty($client_id))
		$data .= ' <client-id>' . $client_id . '</client-id>' . NEWLINE;

		if (!empty($src_serial_id))
		$data .= ' <reply-to-serial-id>' . $src_serial_id . '</reply-to-serial-id>'  . NEWLINE;


		if (defined('REST_API_ORDER_SHARED_SECRET_KEY') && (REST_API_ORDER_SHARED_SECRET_KEY != ''))
		{
			$data .= ' <auth-checksum>' . md5($serial_id . REST_API_ORDER_SHARED_SECRET_KEY) . '</auth-checksum>'  . NEWLINE;
		}


		$data .= " <code>{$code}</code>  " . NEWLINE .
 				 " <message><![CDATA[ " . htmlentities($msg) . " ]]></message> " . NEWLINE ;
		$data .= "<data-dump><![CDATA[ " . htmlentities($data_dump) . " ]]></data-dump>" . NEWLINE;
		$data .= "</" . $orig_api_call . "-reply>";

		return $data;
	}




	public function replyError($orig_api_call, $msg, $code=1, $data_dump="", $client_id='', $src_serial_id='')
	{

		$serial_id =  time() . '-' . rand(10,1000);

		if (empty($code) || ($code <= 0))
		$code = -1;

		$data = USAP_XML_TEXT_HEADER;
		$data .= "<" . $orig_api_call . "-error> " . NEWLINE .
 				 " <code>{$code}</code>  " . NEWLINE .
 				 " <message><![CDATA[ " . htmlentities($msg) . " ]]></message> " . NEWLINE .
 				 " <serial-id>" . $serial_id . "</serial-id>" . NEWLINE ;

		if (!empty($client_id))
		$data .= ' <client-id>' . $client_id . '</client-id>'. NEWLINE;

		if (!empty($src_serial_id))
		$data .= ' <reply-to-serial-id>' . $src_serial_id . '</reply-to-serial-id>' . NEWLINE;


		if (defined('REST_API_ORDER_SHARED_SECRET_KEY') && (REST_API_ORDER_SHARED_SECRET_KEY != ''))
		{
			$data .= ' <auth-checksum>' . md5($serial_id . REST_API_ORDER_SHARED_SECRET_KEY) . '</auth-checksum>'  . NEWLINE;
		}


		$data .= "<data-dump><![CDATA[ " . htmlentities($data_dump) .  " ]]></data-dump>";

		$data .= NEWLINE . "</" . $orig_api_call . "-error>";


		return $data;
	}


	public function errorMessage($msg, $code=0, $data_dump="")
	{
		if (empty($code))
		$code = 0;

		$serial_id =  time() . '-' . rand(10,1000);

		$data = USAP_XML_TEXT_HEADER;
		$data .= "<error> " . NEWLINE .
 				 " <code>{$code}</code>  " . NEWLINE .
 				 " <message><![CDATA[ " . htmlentities($msg) . " ]]></message> " . NEWLINE .
 				 " <serial-id>" . $serial_id . "</serial-id>" . NEWLINE ;

		if (defined('REST_API_ORDER_SHARED_SECRET_KEY') && (REST_API_ORDER_SHARED_SECRET_KEY != ''))
		{
			$data .= ' <auth-checksum>' . md5($serial_id . REST_API_ORDER_SHARED_SECRET_KEY) . '</auth-checksum>'  . NEWLINE;
		}

		$data .= "<data-dump><![CDATA[ " . htmlentities($data_dump) .  " ]]></data-dump>";

		$data .= NEWLINE . "</error>";

		return $data;
	}


	public function cleanHandlerValue( $api_call )
	{
		return preg_replace('/[^a-zA-Z0-9-_]/', '', $api_call);
	}

	/**
	 * Sanitize an Array using stripslashes
	 *
	 * @param Array $data
	 * @return Array
	 */
	public function magicon_stripslashes( $data )
	{
		if ( get_magic_quotes_gpc() ) {
			$data = $this->sanitizer_stripslashes($data);
		}
		return $data;
	}
	
	/**
	 * Sanitize an Array or String using stripslashes() function
	 *
	 * @param mixed  $value array or string to sanitize
	 * @return mixed return same data type of @param
	 */
	public function sanitizer_stripslashes( $value  )
	{
		if (!is_array($value) && !is_string($value))
		return $value;

		if (is_array($value))
		{
			$value = array_map(array('clsRestApiLibrary', 'sanitizer_stripslashes'), $value);
		}
		else
		{
			$value = stripslashes($value);
		}
		return $value;
	}
	
	/**
	 * Sanitize an Array or String using strip_tags() function
	 *
	 * @param  mixed  $value   array or string to sanitize
	 * @return mixed		   return same data type of @param
	 */
	public function sanitizer_strip_tags( $value  )
	{
		if (!is_array($value) && !is_string($value))
		return $value;

		if (is_array($value))
		{
			$value = array_map( array('clsRestApiLibrary', 'sanitizer_strip_tags'), $value);
		}
		else
		{
			$value = strip_tags($value);
		}
		return $value;
	}
	
	/**
	 * Sanitize an Array or String using htmlentities() function
	 *
	 * @param mixed  $value array or string to sanitize
	 * @return mixed		   return same data type of @param
	 */
	public function sanitizer_htmlentities( $value  )
	{
		if (!is_array($value) && !is_string($value))
		return $value;

		if (is_array($value))
		{
			$value = array_map(array('clsRestApiLibrary', 'sanitizer_htmlentities'), $value);
		}
		else
		{
			$value = htmlentities($value);
		}
		return $value;
	}
	/**
	 * Called from restapiservice.php
	 * Commented as this function is not needed. Remove it after reviewing before deployment on staging
	 * @param $resp
	 */
	public function parseXML($resp)
	{
		$xml_parser = new XmlParser2( $resp );
		$xml_root = $xml_parser->GetRoot() ;
		$xml_data = $xml_parser->GetData();
		
		$data = array('root' => NULL, 'data' => NULL, 'array' => NULL);

		if (!empty($xml_root))
		{
			$data = array('root' => $xml_root, 'data' => $xml_data,  'clean_data' => $this->cleanParsedArray($xml_data[$xml_root]));
		}
		return $data;
	}
	
	/**
	 * Called from restapiservice.php
	 * Commented as this function is not needed. Remove it after reviewing before deployment on staging
	 * @param $xml_array
	 */
	public function cleanParsedArray($xml_array)
	{
		$new_data = array();

		foreach($xml_array as $key => $value) {
			if (is_array($value) && !isset($value['VALUE'])) {
				$new_data[$key] = $this->cleanParsedArray($value);
			}else{
				$new_data[$key] = $value['VALUE'];
			}
		}
		return $new_data;
	}
} //class

?>