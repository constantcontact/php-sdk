<?php
namespace Ctct\Auth;

use Ctct\Exceptions\CtctException;
use Ctct\Exceptions\OAuth2Exception;
use Ctct\Util\Config;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

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
    public $client;
    public $props;

    public function __construct($clientId, $clientSecret, $redirectUri)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
        $this->client = new Client();
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

        $baseUrl = Config::get('auth.base_url') . Config::get('auth.authorization_endpoint');
        $request = $this->client->createRequest("GET", $baseUrl);
        $request->setQuery($params);
        return $request->getUrl();
    }

    /**
     * Obtain an access token
     * @param string $code - code returned from Constant Contact after a user has granted access to their account
     * @return array
     * @throws OAuth2Exception
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

        $baseUrl = Config::get('auth.base_url') . Config::get('auth.token_endpoint');
        $request = $this->client->createRequest("POST", $baseUrl);
        $request->setQuery($params);

        try {
            $response = $this->client->send($request)->json();
        } catch (ClientException $e) {
            throw $this->convertException($e);
        }

        return $response;
    }

    /**
     * Get an information about an access token
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @return array
     * @throws CtctException
     */
    public function getTokenInfo($accessToken)
    {
        $baseUrl = Config::get('auth.base_url') . Config::get('auth.token_info');
        $request = $this->client->createRequest("POST", $baseUrl);
        $request->setQuery(array("access_token" => $accessToken));

        try {
            $response = $this->client->send($request)->json();
        } catch (ClientException $e) {
            throw $this->convertException($e);
        }
        return $response;
    }

    /**
     * @param ClientException $exception
     * @return OAuth2Exception
     */
    private function convertException($exception) {
        $oauth2Exception = new OAuth2Exception($exception->getResponse()->getReasonPhrase(), $exception->getCode());
        $oauth2Exception->setUrl($exception->getResponse()->getEffectiveUrl());
        $oauth2Exception->setErrors(json_decode($exception->getResponse()->getBody()->getContents()));
        return $oauth2Exception;
    }
}
