<?php
namespace Ctct\Services;

use Ctct\Util\RestClient;
use Ctct\Util\RestClientInterface;

/**
 * Super class for all services
 *
 * @package Services
 * @author Constant Contact
 */
abstract class BaseService
{
    /**
     * RestClient Implementation to use for HTTP requests
     * @var RestClientInterface
     */
    protected $restClient;

    /**
     * ApiKey for the application
     * @var string
     */
    protected $apiKey;

    /**
     * Constructor with the option to to supply an alternative rest client to be used
     * @param string $apiKey - Constant Contact API Key
     * @param RestClientInterface $restClient - RestClientInterface implementation to be used in the service
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
