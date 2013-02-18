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
     * @var $rest_client - RestClient 
     */
    public static $rest_client;
    
    /**
     * Constructor with the option to to supply an alternative rest client to be used
     * @param RestClientInterface - RestClientInterface implementation to be used in the service
     */
    public function __construct($rest_client = null)
    {
        if (is_null($rest_client)) {
            self::$rest_client = new RestClient();
        } else {
            self::$rest_client = $rest_client;
        }

    }
    
    /**
     * Get the rest client being used by the service
     * @return RestClientInterface - RestClientInterface implementation being used
     */
    public static function getRestClient()
    {
        if (is_null(self::$rest_client)) {
            return new RestClient();
        } else {
            return self::$rest_client;
        }
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


    /**
     * Helper function to build a url depending on the offset and limit
     * @param string $url
     * @param int $offset
     * @param int $limit
     * @return string - resulting url
     */
    protected static function paginateUrl($url, $offset = null, $limit = null)
    {
        $query_params = array();

        if ($offset != null) {
            $query_params['offset'] = $offset;
        }

        if ($limit != null) {
            $query_params['limit'] = $limit;
        }

        if (!empty($query_params)) {
            $url = $url . '?' . http_build_query($query_params);
        }

        return $url;
    }

    public static function paginateTrackingUrl($url, $next = null, $limit = null)
    {
        $query_params = array();

        if ($next != null) {
            $query_params['next'] = $next;
        }

        if ($limit != null) {
            $query_params['limit'] = $limit;
        }

        if (!empty($query_params)) {
            $url = $url . '?' . http_build_query($query_params);
        }

        return $url;
    }
}
