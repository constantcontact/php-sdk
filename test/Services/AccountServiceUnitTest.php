<?php

use Ctct\Services\AccountService;

class AccountServiceUnitTest extends PHPUnit_Framework_TestCase{
    
  public function testGetActivity()
    {
        $rest_client = new MockRestClient(200, JsonLoader::getVerifiedAddressesJson());

        $accountService = new AccountService("apikey", $rest_client);
        $verifedAddresses = $accountService->getVerifiedEmailAddresses('access_token');
        
        $this->assertEquals(1, count($verifedAddresses));
        $this->assertEquals("test123@roving.com", $verifedAddresses[0]->email_address);
        $this->assertEquals("CONFIRMED", $verifedAddresses[0]->status);
    }
}
