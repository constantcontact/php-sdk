<?php
namespace Ctct\Services;

use Ctct\Util\RestClient;
use Ctct\Util\RestClientInterface;

/**
 * Super class for all services
 *
 * @package     Services
 * @author         Constant Contact
 */
abstract class BaseService
{
    /**
     * RestClient Implementation to use for HTTP requests
     * @var $restClient - RestClient 
     */
    public $restClient;

    /**
     * ApiKey for the application
     * @var string
     */
    protected $apiKey;
    
    /**
     * Constructor with the option to to supply an alternative rest client to be used
     * @param RestClientInterface - RestClientInterface implementation to be used in the service
     */
    public function __construct($apiKey, $restClient = null)
    {
        $this->apiKey = $apiKey;

        if (is_null($restClient)) {
            $this->restClient = new RestClient();
        } else {
            $this->restClient = $restClient;
        }
    }

    /**
     * Build a url from the base url and query parameters array
     * @return string
     */
    public function buildUrl($url, $queryParams = null)
    {
        $keyArr = array('api_key' => $this->apiKey);
        if ($queryParams) {
            $params = array_merge($keyArr, $queryParams);
        } else {
            $params = $keyArr;
        }
        
        return $url . '?' . http_build_query($params);
    }
    
    /**
     * Get the rest client being used by the service
     * @return RestClientInterface - RestClientInterface implementation being used
     */
    public function getRestClient()
    {
        return $this->restClient;
    }

    public function setRestClient(RestClientInterface $restClient)
    {
        $this->restClient = $restClient;
    }
    
    
    /**
     * Helper function to return required headers for making an http request with constant contact
     * @param $accessToken - OAuth2 access token to be placed into the Authorization header
     * @return array - authorization headers
     */
    protected static function getHeaders($accessToken)
    {
        return array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $accessToken
        );
    }
}
