<?php

use Ctct\Util\RestClientInterface;
use Ctct\Util\CurlResponse;

/**
 * MockRestClient used in unit testing to simulate a response from a curl request 
 */
class MockRestClient implements RestClientInterface
{
	/**
	 * Response body from the server, probably JSON
	 * @var string
	 */
	public $body;

	/**
	 * Response status code from the server (ie: 401, 404, 204)
	 * @var string
	 */
	public $response_code;

    public function __construct($response_code = null, $response_body = null)
    {
        $this->response_code = $response_code;
        $this->body = $response_body;
    }
	
	/**
	 * Return the stored response
	 */
	private function getMockResponse()
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
		return $this->getMockResponse();
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
		return $this->getMockResponse();
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
		return $this->getMockResponse();
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
		return $this->getMockResponse();
	}
	
}
