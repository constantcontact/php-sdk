<?php
namespace Ctct\Util;

use Ctct\Exceptions\CtctException;

/**
 * Wrapper for curl HTTP request
 *
 * @package Util
 * @author Constant Contact
 */
class RestClient implements RestClientInterface
{
    /**
     * Make an Http GET request
     * @param $url - request url
     * @param array $headers - array of all http headers to send
     * @return CurlResponse - The response body, http info, and error (if one exists)
     */
    public function get($url, array $headers)
    {
        return self::httpRequest($url, "GET", $headers);
    }

    /**
     * Make an Http POST request
     * @param $url - request url
     * @param array $headers - array of all http headers to send
     * @param $data - data to send with request
     * @return CurlResponse - The response body, http info, and error (if one exists)
     */
    public function post($url, array $headers = array(), $data = null)
    {
        return self::httpRequest($url, "POST", $headers, $data);
    }

    /**
     * Make an Http PUT request
     * @param $url - request url
     * @param array $headers - array of all http headers to send
     * @param $data - data to send with request
     * @return CurlResponse - The response body, http info, and error (if one exists)
     */
    public function put($url, array $headers = array(), $data = null)
    {
        return self::httpRequest($url, "PUT", $headers, $data);
    }

    /**
     * Make an Http DELETE request
     * @param $url - request url
     * @param array $headers - array of all http headers to send
     * @return CurlResponse - The response body, http info, and error (if one exists)
     */
    public function delete($url, array $headers = array())
    {
        return self::httpRequest($url, "DELETE", $headers);
    }

    /**
     * Make an HTTP request
     * @param $url - request url
     * @param $method - HTTP method to use for the request
     * @param array $headers - any http headers that should be included with the request
     * @param string|null $data - payload to send with the request, if any
     * @return CurlResponse
     * @throws CTCTException
     */
    private static function httpRequest($url, $method, array $headers = array(), $data = null)
    {
        //adding the version header to the existing headers
        $headers[] = self::getVersionHeader();
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, "ConstantContact AppConnect PHP Library v" . Config::get('settings.version'));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);

        // add data to send with request if present
        if ($data) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        $response = CurlResponse::create(curl_exec($curl), curl_getinfo($curl), curl_error($curl));
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
    
    /**
     * Returns the version header for the rest calls
     * @return string
     */
    public static function getVersionHeader(){
        return 'x-ctct-request-source: sdk.php.' . Config::get('settings.version');
    }
}
