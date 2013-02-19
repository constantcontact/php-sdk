<?php

use Ctct\Services\ActivityService;
use Ctct\Util\RestClient;
use Ctct\Components\Contacts\Address;
use Ctct\Components\Contacts\CustomField;
use Ctct\Components\Activities\ExportContacts;
use Ctct\Components\Activities\AddContacts;
use Ctct\Components\Activities\AddContactsImportData;

class ActivityServiceUnitTest extends PHPUnit_Framework_TestCase{
	
	public function testGetActivity()
	{
		$rest_client = new MockRestClient(200, JsonLoader::getActivities());

        $activityService = new ActivityService("apikey", $rest_client);
        $activities = $activityService->getActivities('access_token');
        
        $activity = $activities[0];
        $this->assertEquals("a07e1ikxwuphd4nwjxl", $activity->id);
        $this->assertEquals("EXPORT_CONTACTS", $activity->type);
        $this->assertEquals("COMPLETE", $activity->status);
        $this->assertEquals("2013-02-13T15:57:03.627Z", $activity->start_date);
        $this->assertEquals("2013-02-13T15:57:03.649Z", $activity->finish_date);
        $this->assertEquals("2013-02-13T15:56:14.697Z", $activity->created_date);
        $this->assertEquals(0, $activity->error_count);
        $this->assertEquals(0, $activity->contact_count);
	}

	public function testGetActivities()
	{
		$rest_client = new MockRestClient(200, JsonLoader::getActivity());

        $activityService = new ActivityService("apikey", $rest_client);
        $activity = $activityService->getActivity('access_token', 'a07e1ikxyomhd4la0o9');
        
        $this->assertEquals("a07e1ikxyomhd4la0o9", $activity->id);
        $this->assertEquals("REMOVE_CONTACTS_FROM_LISTS", $activity->type);
        $this->assertEquals("COMPLETE", $activity->status);

    	$this->assertEquals("djellesma@roving.com (not found in subscriber list)", $activity->errors[0]->message);
    	$this->assertEquals(0, $activity->errors[0]->line_number);
    	$this->assertEquals("", $activity->errors[0]->email_address);

    	$this->assertEquals("djellesma@constantcontact.com (not found in subscriber list)", $activity->errors[1]->message);
    	$this->assertEquals(0, $activity->errors[1]->line_number);
    	$this->assertEquals("", $activity->errors[1]->email_address);

        $this->assertEquals("2013-02-13T14:43:01.635Z", $activity->start_date);
        $this->assertEquals("2013-02-13T14:43:01.662Z", $activity->finish_date);
        $this->assertEquals("2013-02-13T14:42:44.073Z", $activity->created_date);
        $this->assertEquals(2, $activity->error_count);
        $this->assertEquals(2, $activity->contact_count);
	}

	public function testAddClearListsActivity()
	{
		$rest_client = new MockRestClient(201, JsonLoader::getClearListsActivity());

        $activityService = new ActivityService("apikey", $rest_client);
        $activity = $activityService->addClearListsActivity("access_token", array("1","2"));

        $this->assertEquals("a07e1il69fwhd7uan9h", $activity->id);
        $this->assertEquals("CLEAR_CONTACTS_FROM_LISTS", $activity->type);
        $this->assertEquals(0, $activity->error_count);
        $this->assertEquals(0, $activity->contact_count);
	}

	public function testAddExportContactsActivity()
	{
		$rest_client = new MockRestClient(201, JsonLoader::getExportContactsActivity());
		$exportContacts = new ExportContacts(array("1", "2"));

        $activityService = new ActivityService("apikey", $rest_client);
        $activity = $activityService->addExportContactsActivity("access_token", $exportContacts);

		$this->assertEquals("a07e1i5nqamhcfeuu0h", $activity->id);
        $this->assertEquals("EXPORT_CONTACTS", $activity->type);
        $this->assertEquals(0, $activity->error_count);
        $this->assertEquals(0, $activity->contact_count);
	}

	public function testAddRemoveContactsFromListsActivity()
	{
		$rest_client = new MockRestClient(201, JsonLoader::getRemoveContactsFromListsActivity());

        $activityService = new ActivityService("apikey", $rest_client);
        $emailAddresses = array("djellesma@roving.com", "djellesma@constantcontact.com");
        $lists = array("1", "2");
        $activity = $activityService->addRemoveContactsFromListsActivity("access_token", $emailAddresses, $lists);

		$this->assertEquals("a07e1i5nqamhcfeuu0h", $activity->id);
        $this->assertEquals("REMOVE_CONTACTS_FROM_LISTS", $activity->type);
        $this->assertEquals(0, $activity->error_count);
        $this->assertEquals(0, $activity->contact_count);
	}	

	public function testAddAddContactsActivity()
	{
        $rest_client = new MockRestClient(201, JsonLoader::getAddContactsActivity());
        $activityService = new ActivityService("apikey", $rest_client);

        $contact = new AddContactsImportData();
        $address = new Address();
        $address->line1 = "1601 Trapelo Rd";
        $address->city = "Waltham";
        $address->state = "MA";
        $contact->addAddress($address);

        $customField = new CustomField();
        $customField->name = "custom_field_1";
        $customField->value = "my custom value";
        $contact->addCustomField($customField);

        $customField = new CustomField();
        $customField->name = "custom_field_4";
        $customField->value = "my custom 4";
        $contact->addCustomField($customField);
        $contact->addEmail("djellesma123@roving.com");

        $addContacts = new AddContacts(array($contact), array("1"));

        $activity = $activityService->createAddContactsActivity("access_token", $addContacts);
        $this->assertEquals("a07e1il69qzhdby44ro", $activity->id);
        $this->assertEquals("ADD_CONTACTS", $activity->type);
        $this->assertEquals(0, $activity->error_count);
        $this->assertEquals(1, $activity->contact_count);
	}
}
