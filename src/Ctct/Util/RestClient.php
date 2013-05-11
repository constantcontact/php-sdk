<?php
namespace Ctct\Util;

use Ctct\Exceptions\CTCTException;
use Ctct\Util\RestClientInterface;
use Ctct\Util\CurlResponse;

/**
 * Wrapper for curl HTTP request
 *
 * @package     Util
 * @author         Constant Contact
 */
class RestClient implements RestClientInterface
{
    /**
     * Maximum Queries Per Second Requested
     * @var integer
     */
    private $queriesPerSecond;
	
	/**
     * Tiems of calls made in the last second
     * @var array
     */
    private $callTimes = array();

	
    /**
     * Class constructor
     * Stores queries per second if requested, otherwise does nothing
     * @param integer $queriesPerSecond - Maximum Queries Per Second Requested
     */
    public function __construct($queriesPerSecond = null)
    {
		$this->queriesPerSecond = $queriesPerSecond;
	}
    /**
     * Make an Http GET request
     * @param $url - request url
     * @param array $headers - array of all http headers to send
     * @return array - array of the response body, http info, and error (if one exists)
     */
    public function get($url, array $headers)
    {
        return $this->httpRequest($url, "GET", $headers);
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
        return $this->httpRequest($url, "POST", $headers, $data);
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
        return $this->httpRequest($url, "PUT", $headers, $data);
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
        return $this->httpRequest($url, "DELETE", $headers);
    }

    /**
     * Make an Http request
     * @param $url - request url
     * @param array $headers - array of all http headers to send
     * @param $data - data to send with the request
     * @throws CTCTException - if any errors are contained in the returned payload
     * @return CurlResponse
     */
    private function httpRequest($url, $method, array $headers = array(), $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, "ConstantContact Appconnect PHP Library v1.0");
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);

        // add data to send with request if present
        if ($data) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
		
		//Call metering has been requested
		if(is_int($this->queriesPerSecond)){
			//Check each existing call time and remove if it happened more than a second ago
			foreach($this->callTimes as $id => $cCall)
				if(microtime(true) - $cCall > 1)
					unset($this->callTimes[$id]);
				
			//If we have made more than the maximum number of calls, wait 2 seconds
			if(count($callTimes) >= $this->queriesPerSecond) sleep(2);
		}

        $response = CurlResponse::create(curl_exec($curl), curl_getinfo($curl), curl_error($curl));
		if(is_int($this->queriesPerSecond)) $callTimes[] = microtime(true);
        curl_close($curl);

        // check if any errors were returned
        $body = json_decode($response->body, true);
        if (isset($body[0]) && array_key_exists('error_key', $body[0])) {
            $ex = new CtctException($response->body);
            $ex->setCurlInfo($response->info);
            $ex->setErrors($body);
            throw $ex;
        }

        return $response;
    }
}
