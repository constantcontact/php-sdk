<?php

use Ctct\Services\AccountService;
use Ctct\Util\CurlResponse;

class AccountServiceUnitTest extends PHPUnit_Framework_TestCase
{

    private $restClient;
    private $accountService;

    public function setUp()
    {
        $this->restClient = $this->getMock('Ctct\Util\RestClientInterface');
        $this->accountService = new AccountService("apikey", $this->restClient);
    }

    public function testGetVerifiedAddresses()
    {
        $curlResponse = CurlResponse::create(JsonLoader::getVerifiedAddressesJson(), array('http_code' => 200));
        $this->restClient->expects($this->once())
            ->method('get')
            ->with()
            ->will($this->returnValue($curlResponse));

        $response = $this->accountService->getVerifiedEmailAddresses("accessToken", array());

        $this->assertInstanceOf('Ctct\Components\Account\VerifiedEmailAddress', $response[0]);
        $this->assertEquals("test123@roving.com", $response[0]->email_address);
        $this->assertEquals("CONFIRMED", $response[0]->status);
    }

    public function testGetAccountInfo()
    {
        $curlResponse = CurlResponse::create(JsonLoader::getAccountInfoJson(), array('http_code' => 200));
        $this->restClient->expects($this->once())
            ->method('get')
            ->with()
            ->will($this->returnValue($curlResponse));

        $response = $this->accountService->getAccountInfo("accessToken");

        $this->assertInstanceOf('Ctct\Components\Account\AccountInfo', $response);
        $this->assertEquals("http://www.example.com", $response->website);
        $this->assertEquals("My Company", $response->organization_name);
        $this->assertEquals("http://www.example.com", $response->website);
        $this->assertEquals("My Company", $response->organization_name);
        $this->assertEquals("US/Eastern", $response->time_zone);
        $this->assertEquals("Mary Jane", $response->first_name);
        $this->assertEquals("Doe", $response->last_name);
        $this->assertEquals("mjdoe@example.com", $response->email);
        $this->assertEquals("5555555555", $response->phone);
        $this->assertEquals("https://ih.constantcontact.com/fs137/1100371573368/img/90.jpg", $response->company_logo);
        $this->assertEquals("US", $response->country_code);
        $this->assertEquals("MA", $response->state_code);
    }
}
