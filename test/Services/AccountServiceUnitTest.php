<?php

use Ctct\Components\Account\AccountInfo;
use Ctct\Components\Account\VerifiedEmailAddress;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class AccountServiceUnitTest extends PHPUnit_Framework_TestCase {
    /**
     * @var Client
     */
    private static $client;

    public static function setUpBeforeClass() {
        $mock = new MockHandler([
            new Response(200, array(), JsonLoader::getVerifiedAddressesJson()),
            new Response(200, array(), JsonLoader::getAccountInfoJson())
        ]);
        $handler = HandlerStack::create($mock);
        self::$client = new Client(['handler' => $handler]);
    }

    public function testGetVerifiedAddresses() {
        $response = self::$client->request('GET', '/');
        $verifiedAddresses = array();
        foreach (json_decode($response->getBody(), true) as $verifiedAddress) {
            $verifiedAddresses[] = VerifiedEmailAddress::create($verifiedAddress);
        }

        foreach ($verifiedAddresses as $verifiedAddress) {
            $this->assertInstanceOf('Ctct\Components\Account\VerifiedEmailAddress', $verifiedAddress);
            $this->assertEquals("test123@roving.com", $verifiedAddress->email_address);
            $this->assertEquals("CONFIRMED", $verifiedAddress->status);
        }
    }

    public function testGetAccountInfo() {
        $response = self::$client->request('GET', '/');
        $result = AccountInfo::create(json_decode($response->getBody(), true));

        $this->assertInstanceOf('Ctct\Components\Account\AccountInfo', $result);
        $this->assertEquals("http://www.example.com", $result->website);
        $this->assertEquals("My Company", $result->organization_name);
        $this->assertEquals("http://www.example.com", $result->website);
        $this->assertEquals("My Company", $result->organization_name);
        $this->assertEquals("US/Eastern", $result->time_zone);
        $this->assertEquals("Mary Jane", $result->first_name);
        $this->assertEquals("Doe", $result->last_name);
        $this->assertEquals("mjdoe@example.com", $result->email);
        $this->assertEquals("5555555555", $result->phone);
        $this->assertEquals("https://ih.constantcontact.com/fs137/1100371573368/img/90.jpg", $result->company_logo);
        $this->assertEquals("US", $result->country_code);
        $this->assertEquals("MA", $result->state_code);
    }
}
