<?php

use Ctct\ConstantContact;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;

require_once 'config.inc';

class ListsIntegrationTest extends PHPUnit_Framework_TestCase{
        
    public $cc; 

    public function setUp()
    {
        $this->cc = new ConstantContact(APIKEY);
    }

    public function testAddList()
    {
        $list = new ContactList();
        $list->name = self::createRandomName();
        $list->status = "ACTIVE";
        $returnList = $this->cc->addList(ACCESS_TOKEN, $list);

        $this->assertEquals($list->name, $returnList->name);
        $this->assertEquals("ACTIVE", $returnList->status);

        return $returnList;
    }

    public function testGetLists()
    {
        $lists = $this->cc->getLists(ACCESS_TOKEN);
        $this->assertGreaterThan(0, count($lists));
    }

    /**
     * @depends testAddList
     */
    public function testGetList(ContactList $list)
    {
        $contactList = $this->cc->getList(ACCESS_TOKEN, $list->id);
        $this->assertNotNull($contactList);
        $this->assertEquals($contactList->id, $list->id);
        $this->assertEquals($contactList->name, $list->name);
    }

    /**
     * @depends testAddList
     */
    public function testUpdateList(ContactList $list)
    {
        $this->markTestSkipped('Known contacts encoding issue.');
        $list->name = "my new list name";

        $returnList = $this->cc->updateList(ACCESS_TOKEN, $list);

        $this->assertNotNull($returnList);
        $this->assertEquals($contactList->id, $list->id);
        $this->assertEquals($contactList->name, $list->name);
    }

    /**
     * @depends testAddList
     */ 
    public function testGetContactsFromList(ContactList $list)
    {
        $email = self::createRandomEmail();
        $contact = new Contact();
        $contact->addList($list);
        $contact->addEmail($email);
        
        // add the contact to the newly created list
        $returnContact = $this->cc->addContact(ACCESS_TOKEN, $contact);
        $this->assertNotNull($returnContact);

        // get the listing of contacts in the list and verify the contact exists
        $resultSet = $this->cc->getContactsFromList(ACCESS_TOKEN, $list->id, 1);
        $this->assertEquals(1, count($resultSet->results));
        $this->assertEquals($email, $resultSet->results[0]->email_addresses[0]->email_address);
    }

    private static function createRandomName()
    {
        return "List " . sprintf(time() . '%s%s%s', mt_rand(0, 99), mt_rand(0, 99), mt_rand(0, 99));
    }

    private static function createRandomEmail($domain = 'roving.com')
    {
        return sprintf(time() . '%s%s%s', mt_rand(0, 99), mt_rand(0, 99), mt_rand(0, 99)) . '@' . $domain;
    }

}
