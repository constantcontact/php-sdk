<?php

use Ctct\Util\RestClientInterface;
use Ctct\Util\CurlResponse;

class MockRestClient implements RestClientInterface
{
	
	public $body;
	public $response_code;

    public function __construct($response_code = null, $response_body = null)
    {
        $this->response_code = $response_code;
        $this->body = $response_body;
    }
	
	private function get_mock_response()
	{
		$curl_response = new CurlResponse();
		$curl_response->body = $this->body;
		$curl_response->info = array('http_code' => $this->response_code);
		
		return $curl_response;
	}
	
	/**
	 * Make an Http GET request
	 * @param $url - request url
	 * @param array $headers - array of all http headers to send
	 * @return array - array of the response body, http info, and error (if one exists)
	 */
	public function get($url, array $headers)
	{
		return $this->get_mock_response();
	}
	
	
	/**
	 * Make an Http POST request
	 * @param $url - request url
	 * @param array $headers - array of all http headers to send
	 * @param $data - data to send with request
	 * @return array - array of the response body, http info, and error (if one exists)
	 */
	public function post($url, array $headers = array(), $data = null)
	{
		return $this->get_mock_response();
	}
	
	/**
	 * Make an Http PUT request
	 * @param $url - request url
	 * @param array $headers - array of all http headers to send
	 * @param $data - data to send with request
	 * @return array - array of the response body, http info, and error (if one exists)
	 */
	public function put($url, array $headers = array(), $data = null)
	{
		return $this->get_mock_response();
	}
	
	/**
	 * Make an Http DELETE request
	 * @param $url - request url
	 * @param array $headers - array of all http headers to send
	 * @param $data - data to send with request
	 * @return array - array of the response body, http info, and error (if one exists)
	 */
	public function delete($url, array $headers = array())
	{
		return $this->get_mock_response();
	}
	
}
