<?php
namespace Ctct\Auth;

use Ctct\Util\Config;
use Ctct\Util\RestClient;

/**
 * Class that implements necessary functionality to obtain an access token from a user
 *
 * @package 	Auth
 * @author 		Constant Contact
 */
class CtctOAuth2{
	
	public $client_id;
	public $client_secret;
	public $redirect_uri;
	public $props;
	
	public function __construct($client_id, $client_secret, $redirect_uri)
	{		
		$this->client_id = $client_id;
		$this->client_secret = $client_secret;
		$this->redirect_uri = $redirect_uri;
	}
	
	/**
	 * Get the URL at which the user can authenticate and authorize the requesting application
	 * @param boolean $server - Whether or not to use OAuth2 server flow, alternative is client flow
     * @return string - url to send a user to, to grant access to their account
	 */
	public function getAuthorizationUrl($server = true)
	{
		
		$params = array(
			'response_type'		=> ($server) ? Config::get('auth.response_type_code') : Config::get("auth.response_type_token"),
			'client_id'			=> $this->client_id,
			'redirect_uri'		=> $this->redirect_uri
		);
		
		return Config::get('auth.base_url') . Config::get('auth.authorization_endpoint') . '?' . http_build_query ($params);
	}
	
	/**
	 * Obtain an access token
	 * @param string $code - code returned from Constant Contact after a user has granted access to their account
	 * @return array
	 */
	public function getAccessToken($code)
	{
		$params = array(
			'grant_type'		=> Config::get('auth.authorization_code_grant_type'),
			'client_id'			=> $this->client_id,
			'client_secret'		=> $this->client_secret,
			'code'				=> $code,
			'redirect_uri'		=> $this->redirect_uri
		);
		
		$url = Config::get('auth.base_url') . Config::get('auth.token_endpoint') . '?' . http_build_query($params);
		
		$rest_client = new RestClient();
		$response = $rest_client->post($url);
		$response_body = json_decode($response->body, true);
		
		if(array_key_exists('error', $response_body))
		{
			throw new OAuth2Exception($response_body['error'] . ': ' . $response_body['error_description']);
		}
		
		return $response_body;
	}

	
}
