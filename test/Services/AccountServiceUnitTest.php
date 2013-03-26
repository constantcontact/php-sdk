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
}
