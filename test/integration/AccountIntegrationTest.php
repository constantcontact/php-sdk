<?php

use Ctct\ConstantContact;

class AccountIntegrationTest extends PHPUnit_Framework_TestCase{
        
    public $cc; 

    public function setUp()
    {
        $this->cc = new ConstantContact(APIKEY);
    }

    public function testGetVerifiedEmailAddresses()
    {
        $verifiedAddresses = $this->cc->getVerifiedEmailAddresses(ACCESS_TOKEN);
        $this->assertNotNull($verifiedAddresses);
        $this->assertGreaterThan(0, count($verifiedAddresses));
    }  

}
