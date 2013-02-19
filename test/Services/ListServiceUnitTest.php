<?php

use Ctct\Services\ListService;
use Ctct\Util\RestClient;
use Ctct\Components\Contacts\ContactList;
 
class ListServiceUnitTest extends PHPUnit_Framework_TestCase{

	public function testGetLists()
	{
        $rest_client = new MockRestClient(200, JsonLoader::getListsJson());

		$list_service = new ListService("apikey", $rest_client);
		$response = $list_service->getLists('access_token');
		
		$this->assertEquals(1, $response[0]->id);
		$this->assertEquals("General Interest", $response[0]->name);
		$this->assertEquals("ACTIVE", $response[0]->status);
		$this->assertEquals(17, $response[0]->contact_count);
		
		$this->assertEquals(3, $response[1]->id);
		$this->assertEquals("mod_Test List 1", $response[1]->name);
		$this->assertEquals("HIDDEN", $response[1]->status);
		$this->assertEquals(18, $response[1]->contact_count);
	}
	
	public function testGetList()
	{
        $rest_client = new MockRestClient(200, JsonLoader::getListJson());
		
		$list_service = new ListService("apikey", $rest_client);
		$list = $list_service->getList('access_token', 6);
		
		$this->assertEquals(6, $list->id);
		$this->assertEquals("Test List 4", $list->name);
		$this->assertEquals("HIDDEN", $list->status);
		$this->assertEquals(19, $list->contact_count);
	}

	public function testAddList()
	{
        $rest_client = new MockRestClient(200, JsonLoader::getListJson());
		
		$list_service = new ListService("apikey", $rest_client);
		$list = $list_service->addList('access_token', new ContactList());
		
		$this->assertEquals(6, $list->id);
		$this->assertEquals("Test List 4", $list->name);
		$this->assertEquals("HIDDEN", $list->status);
		$this->assertEquals(19, $list->contact_count);
	}
	
	public function testUpdateList()
	{
        $rest_client = new MockRestClient(200, JsonLoader::getListJson());
		
		$list_service = new ListService("apikey", $rest_client);
		$list = $list_service->updateList('access_token', new ContactList());
		
		$this->assertEquals(6, $list->id);
		$this->assertEquals("Test List 4", $list->name);
		$this->assertEquals("HIDDEN", $list->status);
		$this->assertEquals(19, $list->contact_count);
	}
	
	public function testGetContactsFromList()
	{
        $rest_client = new MockRestClient(200, JsonLoader::getContactsJson());
		
		$list_service = new ListService("apikey", $rest_client);
		$response = $list_service->getContactsFromList('access_token', 1);
		$contact = $response->results[1];
		
		$this->assertEquals(231, $contact->id);
		$this->assertEquals("ACTIVE", $contact->status);
		$this->assertEquals("", $contact->fax);
		$this->assertEquals("", $contact->prefix_name);
		$this->assertEquals("Jimmy", $contact->first_name);
		$this->assertEquals("", $contact->middle_name);
		$this->assertEquals("Roving", $contact->last_name);
		$this->assertEquals("Bear Tamer", $contact->job_title);
		$this->assertEquals("Animal Trainer Pro", $contact->company_name);
		$this->assertEquals("details", $contact->source_details);
		$this->assertEquals("ACTION_BY_OWNER", $contact->action_by);
		$this->assertEquals(false, $contact->confirmed);
		$this->assertEquals("", $contact->source);
		
		// custom fields
		$this->assertEquals("CustomField1", $contact->custom_fields[0]->name);
		$this->assertEquals("1", $contact->custom_fields[0]->value);
		
		//addresses
		$this->assertEquals("Suite 101", $contact->addresses[0]->line1);
		$this->assertEquals("line2", $contact->addresses[0]->line2);
		$this->assertEquals("line3", $contact->addresses[0]->line3);
		$this->assertEquals("Brookfield", $contact->addresses[0]->city);
		$this->assertEquals("PERSONAL", $contact->addresses[0]->address_type);
		$this->assertEquals("WI", $contact->addresses[0]->state_code);
		$this->assertEquals("us", $contact->addresses[0]->country_code);
		$this->assertEquals("53027", $contact->addresses[0]->postal_code);
		$this->assertEquals("", $contact->addresses[0]->sub_postal_code);
		
		//notes
		$this->assertEquals(0, count($contact->notes));
		
		//lists
		$this->assertEquals(1, $contact->lists[0]->id);
		$this->assertEquals("ACTIVE", $contact->lists[0]->status);

		// EmailAddress		
		$this->assertEquals("ACTIVE", $contact->email_addresses[0]->status);
		$this->assertEquals("NO_CONFIRMATION_REQUIRED", $contact->email_addresses[0]->confirm_status);
		$this->assertEquals("ACTION_BY_OWNER", $contact->email_addresses[0]->opt_in_source);
		$this->assertEquals("2012-06-22T10:29:09.976Z", $contact->email_addresses[0]->opt_in_date);
		$this->assertEquals("", $contact->email_addresses[0]->opt_out_date);
		$this->assertEquals("anothertest@roving.com", $contact->email_addresses[0]->email_address);
	}
}
