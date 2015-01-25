<?php

use Ctct\Components\Account\AccountInfo;
use Ctct\Components\Account\VerifiedEmailAddress;
use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Message\Response;

class AccountServiceUnitTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    private static $client;

    public static function setUpBeforeClass()
    {
        self::$client = new Client();
        $verifiedAddressStream = Stream::factory(JsonLoader::getVerifiedAddressesJson());
        $accountInfoStream = Stream::factory(JsonLoader::getAccountInfoJson());
        $mock = new Mock([
            new Response(200, array(), $verifiedAddressStream),
            new Response(200, array(), $accountInfoStream)
        ]);
        self::$client->getEmitter()->attach($mock);
    }

    public function testGetVerifiedAddresses() {
        $response = self::$client->get('/');
        $verifiedAddresses = array();
        foreach ($response->json() as $verifiedAddress) {
            $verifiedAddresses[] = VerifiedEmailAddress::create($verifiedAddress);
        }

        foreach ($verifiedAddresses as $verifiedAddress) {
            $this->assertInstanceOf('Ctct\Components\Account\VerifiedEmailAddress', $verifiedAddress);
            $this->assertEquals("test123@roving.com", $verifiedAddress->email_address);
            $this->assertEquals("CONFIRMED", $verifiedAddress->status);
        }
    }

    public function testGetAccountInfo()
    {
        $response = self::$client->get('/');
        $result = AccountInfo::create($response->json());

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
