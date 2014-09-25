<?php
namespace Ctct\Auth;

use Ctct\Util\Config;
use Ctct\Util\RestClient;
use Ctct\Exceptions\OAuth2Exception;

/**
 * Class that implements necessary functionality to obtain an access token from a user
 *
 * @package     Auth
 * @author      Constant Contact
 */
class CtctOAuth2
{
    public $clientId;
    public $clientSecret;
    public $redirectUri;
    public $props;

    public function __construct($clientId, $clientSecret, $redirectUri, $restClient = null)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
        $this->restClient = ($restClient) ? $restClient : new RestClient();
    }

    /**
     * Get the URL at which the user can authenticate and authorize the requesting application
     * @param boolean $server - Whether or not to use OAuth2 server flow, alternative is client flow
     * @param string $state - An optional value used by the client to maintain state between the request and callback.
     * @return string $url - The url to send a user to, to grant access to their account
     */
    public function getAuthorizationUrl($server = true, $state = null)
    {
        $responseType = ($server) ? Config::get('auth.response_type_code') : Config::get("auth.response_type_token");
        $params = array(
            'response_type' => $responseType,
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri
        );

        // add the state param if it was provided
        if ($state != null) {
            $params['state'] = $state;
        }

        $url = Config::get('auth.base_url') . Config::get('auth.authorization_endpoint');
        return $url . '?' . http_build_query($params);
    }

    /**
     * Obtain an access token
     * @param string $code - code returned from Constant Contact after a user has granted access to their account
     * @return array
     * @throws \Ctct\Exceptions\OAuth2Exception
     */
    public function getAccessToken($code)
    {
        $params = array(
            'grant_type' => Config::get('auth.authorization_code_grant_type'),
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $code,
            'redirect_uri' => $this->redirectUri
        );

        $url = Config::get('auth.base_url') . Config::get('auth.token_endpoint') . '?' . http_build_query($params);

        $response = $this->restClient->post($url);
        $responseBody = json_decode($response->body, true);

        if (array_key_exists('error', $responseBody)) {
            throw new OAuth2Exception($responseBody['error'] . ': ' . $responseBody['error_description']);
        }

        return $responseBody;
    }

    /**
     * Get an information about an access token
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @return array
     * @throws \Ctct\Exceptions\CtctException
     */
    public function getTokenInfo($accessToken)
    {
        $url = Config::get('auth.base_url') . Config::get('auth.token_info');
        $response = $this->restClient->post($url, array(), "access_token=" . $accessToken);
        return json_decode($response->body, true);
    }
}
