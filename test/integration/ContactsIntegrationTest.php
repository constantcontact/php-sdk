<?php

use Ctct\ConstantContact;
use Ctct\Components\Contacts\Address;
use Ctct\Components\Contacts\CustomField;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;

require_once 'config.inc';

class ContactsIntegrationTest extends PHPUnit_Framework_TestCase{
        
    public $cc; 

    public function setUp()
    {
        $this->cc = new ConstantContact(APIKEY);
    }

    /**
     * @covers Ctct\ConstantContact::addContact
     */ 
    public function testAddContact()
    {
        $contact = new Contact();
        $emailAddress = self::createRandomEmail();
        $contact->addEmail($emailAddress);
        $contact->addList("1");
        
        $returnContact = $this->cc->addContact(ACCESS_TOKEN, $contact);

        $this->assertNotNull($returnContact);
        $this->assertEquals("1", $contact->lists[0]->id);
        $this->assertEquals($emailAddress, $contact->email_addresses[0]->email_address);

        return $returnContact;
    }

    /**
     * @covers Ctct\ConstantContact::getContacts
     */ 
    public function testGetContacts()
    {
        $resultSet = $this->cc->getContacts(ACCESS_TOKEN);

        $this->assertNotNull($resultSet);
        $this->assertGreaterThan(0, count($resultSet->results));
    } 

    public function testGetContactWithLimit()
    {
        $resultSet = $this->cc->getContacts(ACCESS_TOKEN, 1);

        $this->assertEquals(1, count($resultSet->results));
        $this->assertNotNull($resultSet->next);
    }

    /**
     * @depends testAddContact
     */ 
    public function testGetContactByEmail(Contact $contact) 
    {
        $emailAddress = $contact->email_addresses[0]->email_address;
        $resultSet = $this->cc->getContactByEmail(ACCESS_TOKEN, $emailAddress);

        $this->assertNotNull($resultSet);   
        $this->assertGreaterThan(0, count($resultSet->results));
        $this->assertEquals($emailAddress, $resultSet->results[0]->email_addresses[0]->email_address);
    }  

    /**
     * @depends testGetContact
     */
    public function testGetContact(Contact $contact)
    {
        $returnContact = $this->cc->getContact(ACCESS_TOKEN, $contact->id);
        $this->assertEquals($contact->id, $returnContact->id);
        $this->assertEquals($contact->first_name, $returnContact->first_name);
    }

    /**
     * @depends testAddContact
     */
    public function testUpdateContact(Contact $contact)
    {
        $emailAddress = $contact->email_addresses[0]->email_address;
        $firstName = "new first name";
        $contact->first_name = $firstName;
        
        $updateContact = $this->cc->updateContact(ACCESS_TOKEN, $contact);

        $this->assertNotNull($updateContact);
        $this->assertEquals($firstName, $updateContact->first_name);
    }

    /**
     * @depends testAddContact
     */ 
    public function testDeleteContactFromList(Contact $contact)
    {
        $list = new ContactList();
        $list->name = self::createRandomName();
        $list->status = "ACTIVE";

        // add the list
        $returnedList = $this->cc->addList(ACCESS_TOKEN, $list);
        $this->assertNotNull($returnedList);

        // add the contact to the list
        $contact->addList($returnedList);
        $updatedContact = $this->cc->updateContact(ACCESS_TOKEN, $contact);
        $this->assertNotNull($updatedContact);

        $result = $this->cc->deleteContactFromList(ACCESS_TOKEN, $contact, $list);
        $this->assertTrue($result);

    }

    /**
     * @depends testAddContact
     */ 
    public function testDeleteContactFromLists(Contact $contact)
    {
        $list = new ContactList();
        $list->name = self::createRandomName();
        $list->status = "ACTIVE";

        // add the list
        $returnedList = $this->cc->addList(ACCESS_TOKEN, $list);
        $this->assertNotNull($returnedList);

        // add the contact to the list
        $contact->addList($returnedList);
        $updatedContact = $this->cc->updateContact(ACCESS_TOKEN, $contact);
        $this->assertNotNull($updatedContact);

        $result = $this->cc->deleteContactFromLists(ACCESS_TOKEN, $contact);
        $this->assertTrue($result);
    }

    /**
     * @depends testAddContact
     */  
    public function testDeleteContact(Contact $contact)
    {
        $this->assertTrue($this->cc->deleteContact(ACCESS_TOKEN, $contact));
    }

    private static function createRandomEmail($domain = 'roving.com')
    {
        return sprintf(time() . '%s%s%s', mt_rand(0, 99), mt_rand(0, 99), mt_rand(0, 99)) . '@' . $domain;
    }

     private static function createRandomName()
    {
        return "List " . sprintf(time() . '%s%s%s', mt_rand(0, 99), mt_rand(0, 99), mt_rand(0, 99));
    }

}
