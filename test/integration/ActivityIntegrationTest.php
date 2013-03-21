<?php

use Ctct\ConstantContact;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\Activites\Activity;
use Ctct\Components\Activities\ExportContacts;
use Ctct\Components\Activities\AddContactsImportData;
use Ctct\Components\Activities\AddContacts;

class ActivityIntegrationTest extends PHPUnit_Framework_TestCase{
        
    public $cc; 

    const CONTACTLIST_KEY = "contactlist";
    const ACTIVITY_KEY = "activity";
    const CONTACT_KEY = "contact";

    public function setUp()
    {
        $this->cc = new ConstantContact(APIKEY);
    }

    public function testAddCreateContactsActivity()
    {
        $contact = new AddContactsImportData();
        $contact->addEmail(self::createRandomEmail());
        $contact->first_name = "Test";
        $contact->last_name = "mcTester";

        $contactTwo = new AddContactsImportData();
        $contactTwo->addEmail(self::createRandomEmail());
        $contactTwo->first_name = "Test2";
        $contactTwo->last_name = "mcTester2";

        // create a new list to add the contacts to
        $list = new ContactList();
        $list->name = self::createRandomName();
        $list->status = "ACTIVE";
        $returnList = $this->cc->addList(ACCESS_TOKEN, $list);
        $this->assertNotNull($returnList);

        // add a contact to the list OUTSIDE of bulk activitues (data seeding for a remove contacts from list call)
        $singleContact = new Contact();
        $emailAddress = self::createRandomEmail();
        $singleContact->addEmail($emailAddress);
        $singleContact->addList($returnList);
        $returnContact = $this->cc->addContact(ACCESS_TOKEN, $singleContact);
        $this->assertNotNull($returnContact);
        $this->assertEquals($emailAddress, $returnContact->email_addresses[0]->email_address);

        $addContacts = new AddContacts(array($contact, $contactTwo), array($returnList->id));

         $activity = $this->cc->addCreateContactsActivity(ACCESS_TOKEN, $addContacts);
         $this->assertNotNull($activity);

        return array(
            self::ACTIVITY_KEY => $activity,
            self::CONTACTLIST_KEY => $returnList,
            self::CONTACT_KEY => $returnContact
        );
    }

    /**
     * @depends testAddCreateContactsActivity
     */ 
    public function testClearListsActivity(array $params)
    {

        $activity = $this->cc->addClearListsActivity(ACCESS_TOKEN, array($params[self::CONTACTLIST_KEY]->id));
        $this->assertNotNull($activity);
        $this->assertEquals("CLEAR_CONTACTS_FROM_LISTS", $activity->type);
    }

    /**
     * @depends testAddCreateContactsActivity
     */ 
    public function testRemoveContactsFromListActivity(array $params)
    {
        $emailAddresses = array($params[self::CONTACT_KEY]->email_addresses[0]->email_address);
        $lists = array($params[self::CONTACTLIST_KEY]->id);
        $activity = $this->cc->addRemoveContactsFromListsActivity(ACCESS_TOKEN, $emailAddresses, $lists);
        $this->assertNotNull($activity);
        $this->assertEquals("REMOVE_CONTACTS_FROM_LISTS", $activity->type);
    }

    /**
     * @depends testAddCreateContactsActivity
     */ 
    public function testExportContactsActivity(array $params)
    {
        $exportActivity = new ExportContacts(array($params[self::CONTACTLIST_KEY]->id));
        $returnActivity = $this->cc->addExportContactsActivity(ACCESS_TOKEN, $exportActivity);
        $this->assertNotNull($returnActivity);
        $this->assertEquals("EXPORT_CONTACTS", $returnActivity->type);
    }

    /**
     * @depends testAddCreateContactsActivity
     */ 
    public function testGetActivities(array $params)
    {
        $activities = $this->cc->getActivities(ACCESS_TOKEN);
        $this->assertNotNull($activities);
        $this->assertGreaterThan(0, count($activities));
    }

    /**
     * @depends testAddCreateContactsActivity
     */ 
    public function testGetActivity(array $params)
    {
        $returnActivity = $this->cc->getActivity(ACCESS_TOKEN, $params[self::ACTIVITY_KEY]->id);
        $this->assertNotNull($returnActivity);
        $this->assertEquals("ADD_CONTACTS", $returnActivity->type);
    }

    private static function createRandomEmail($domain = 'snoopy.roving.com')
    {
        return sprintf(time() . '%s%s%s', mt_rand(0, 99), mt_rand(0, 99), mt_rand(0, 99)) . '@' . $domain;
    }

    private static function createRandomName()
    {
        return "List " . sprintf(time() . '%s%s%s', mt_rand(0, 99), mt_rand(0, 99), mt_rand(0, 99));
    }  


}
