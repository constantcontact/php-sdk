<?php

use Ctct\Auth\CtctOAuth2;
use Ctct\Util\CurlResponse;
use Ctct\Util\Config;

class CtctOAuth2UnitTest extends PHPUnit_Framework_TestCase
{

    private $restClient;
    private $ctctOAuth2;
    private $apiKey = "apiKey";
    private $clientSecret = "clientSecret";
    private $redirectUri = "redirectUri";

    public function setUp()
    {
        $this->restClient = $this->getMock('Ctct\Util\RestClientInterface');
        $this->ctctOAuth2 = new CtctOAuth2($this->apiKey, $this->clientSecret, $this->redirectUri, $this->restClient);
    }

    public function testGetTokenInfo()
    {
        $curlResponse = CurlResponse::create(JsonLoader::getTokenInfoJson(), array('http_code' => 200));
        $this->restClient->expects($this->once())
            ->method('post')
            ->with()
            ->will($this->returnValue($curlResponse));

        $token = $this->ctctOAuth2->getTokenInfo("accessToken");

        $this->assertEquals("f98b207c-ta99b-4938-b523-3cc2895f5420", $token['client_id']);
        $this->assertEquals("ctcttest", $token['user_name']);
        $this->assertEquals("315110295", $token['expires_in']);
    }

    public function testGetAccessToken()
    {
        $curlResponse = CurlResponse::create(JsonLoader::getAccessTokenJson(), array('http_code' => 200));
        $this->restClient->expects($this->once())
            ->method('post')
            ->with()
            ->will($this->returnValue($curlResponse));

        $token = $this->ctctOAuth2->getAccessToken("fakeCode");

        $this->assertEquals("v6574b42-a5bc-4574-a87f-5c9d1202e316", $token['access_token']);
        $this->assertEquals("308874923", $token['expires_in']);
        $this->assertEquals("Bearer", $token['token_type']);
    }

    /**
     * @dataProvider authorizationUrlProvider
     */
    public function testGetAuthorizationUrl($server, $expectedResponse)
    {
        $this->assertEquals($expectedResponse, $this->ctctOAuth2->getAuthorizationUrl($server));
    }

    public function testGetAuthorizationUrlServer()
    {
        $authUrl = $this->ctctOAuth2->getAuthorizationUrl();
        $baseUrl = Config::get('auth.base_url') . Config::get('auth.authorization_endpoint');
        $params = array(
            'response_type' => 'code',
            'client_id' => $this->apiKey,
            'redirect_uri' => $this->redirectUri
        );
        $expectedUrl = $baseUrl . '?' . http_build_query($params);
        $this->assertEquals($expectedUrl, $authUrl);
    }

    public function testGetAuthorizationUrlClient()
    {
        $authUrl = $this->ctctOAuth2->getAuthorizationUrl(false);
        $baseUrl = Config::get('auth.base_url') . Config::get('auth.authorization_endpoint');
        $params = array(
            'response_type' => 'token',
            'client_id' => $this->apiKey,
            'redirect_uri' => $this->redirectUri
        );
        $expectedUrl = $baseUrl . '?' . http_build_query($params);
        $this->assertEquals($expectedUrl, $authUrl);
    }

    public function testGetAuthorizationUrlServerWithState()
    {
        $state = 'this is my state';
        $authUrl = $this->ctctOAuth2->getAuthorizationUrl(true, $state);
        $baseUrl = Config::get('auth.base_url') . Config::get('auth.authorization_endpoint');
        $params = array(
            'response_type' => 'code',
            'client_id' => $this->apiKey,
            'redirect_uri' => $this->redirectUri,
            'state' => $state
        );
        $expectedUrl = $baseUrl . '?' . http_build_query($params);
        $this->assertEquals($expectedUrl, $authUrl);
    }

    public function testGetAuthorizationUrlClientWithState()
    {
        $state = 'this is my state';
        $authUrl = $this->ctctOAuth2->getAuthorizationUrl(false, $state);
        $baseUrl = Config::get('auth.base_url') . Config::get('auth.authorization_endpoint');
        $params = array(
            'response_type' => 'token',
            'client_id' => $this->apiKey,
            'redirect_uri' => $this->redirectUri,
            'state' => $state
        );
        $expectedUrl = $baseUrl . '?' . http_build_query($params);
        $this->assertEquals($expectedUrl, $authUrl);
    }

    public function authorizationUrlProvider()
    {
        $requestParams = "&client_id=apiKey&redirect_uri=redirectUri";
        $serverParams = "?response_type=" . Config::get('auth.response_type_code') . $requestParams;
        $clientParams = "?response_type=" . Config::get('auth.response_type_token') . $requestParams;

        return array(
            array(true, Config::get('auth.base_url') . Config::get('auth.authorization_endpoint') . $serverParams),
            array(false, Config::get('auth.base_url') . Config::get('auth.authorization_endpoint') . $clientParams)
        );
    }
}
