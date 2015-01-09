<?php
namespace Ctct\Services;

use Ctct\Util\Config;
use GuzzleHttp\Client;

/**
 * Super class for all services
 *
 * @package Services
 * @author Constant Contact
 */
abstract class BaseService
{
    /**
     * GuzzleHTTP Client Implementation to use for HTTP requests
     * @var Client
     */
    protected $client;

    /**
     * ApiKey for the application
     * @var string
     */
    protected $apiKey;

    /**
     * Constructor with the option to to supply an alternative rest client to be used
     * @param string $apiKey - Constant Contact API Key
     * @param Client $client - GuzzleHTTP Client implementation to be used in the service
     */
    public function __construct($apiKey, $client = null)
    {
        $this->apiKey = $apiKey;

        if (is_null($client)) {
            $this->client = new Client();
        } else {
            $this->client = $client;
        }
    }

    /**
     * Build a URL from a base url and optional array of query parameters to append to the url. URL query parameters
     * should not be URL encoded and this method will handle that.
     * @param $url
     * @param array $queryParams
     * @return string
     */
    public function buildUrl($url, array $queryParams = null)
    {
        $keyArr = array('api_key' => $this->apiKey);
        if ($queryParams) {
            $params = array_merge($keyArr, $queryParams);
        } else {
            $params = $keyArr;
        }

        return $url . '?' . http_build_query($params, '', '&');
    }

    /**
     * Get the rest client being used by the service
     * @return Client - GuzzleHTTP Client implementation being used
     */
    public function getClient()
    {
        return $this->client;
    }

    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    public function createBaseRequest($accessToken, $method, $baseUrl) {
        $request = $this->client->createRequest($method, $baseUrl);
        $request->getQuery()->set("api_key", $this->apiKey);
        $request->setHeaders($this->getHeaders($accessToken));
        return $request;
    }

    /**
     * Helper function to return required headers for making an http request with constant contact
     * @param $accessToken - OAuth2 access token to be placed into the Authorization header
     * @return array - authorization headers
     */
    protected static function getHeaders($accessToken)
    {
        return array(
            'User-Agent' => 'ConstantContact AppConnect PHP Library v' . Config::get('settings.version'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken
        );
    }
}
