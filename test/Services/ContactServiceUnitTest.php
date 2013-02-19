<?php

use Ctct\Services\ContactService;
use Ctct\Util\RestClient;
use Ctct\Components\Contacts\Contact;
 
class ContactServiceUnitTest extends PHPUnit_Framework_TestCase{

	/**
	 * Test getting a set of contacts
	 */
	public function testGetContacts()
	{
        $rest_client = new MockRestClient(200, JsonLoader::getContactsJson());
		
		$contact_service = new ContactService("apikey", $rest_client);
		$response = $contact_service->getContacts('access_token', array('limit' => 2));

		$this->assertEquals('?next=c3RhcnRBdD0zJmxpbWl0PTI', $response->next);
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

	public function testGetContactsNoNextLink()
	{
        $rest_client = new MockRestClient(200, JsonLoader::getContactsNoNextJson());
		
		$contact_service = new ContactService("apikey", $rest_client);
		$response = $contact_service->getContacts('access_token', array('limit' => 2));
		$contact = $response->results[1];
		
		$this->assertEquals($response->next, null);
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
	
	public function testGetContact()
	{
        $rest_client = new MockRestClient(200, JsonLoader::getContactJson());
		
		$contact_service = new ContactService("apikey", $rest_client);
		$contact = $contact_service->getContact('access_token', 1);
		
		$this->assertEquals(238, $contact->id);
		$this->assertEquals("ACTIVE", $contact->status);
		$this->assertEquals("555-1212", $contact->fax);
		$this->assertEquals("Mr.", $contact->prefix_name);
		$this->assertEquals("John", $contact->first_name);
		$this->assertEquals("S", $contact->middle_name);
		$this->assertEquals("Smith", $contact->last_name);
		$this->assertEquals("Software Engineer", $contact->job_title);
		$this->assertEquals("Constant Contact", $contact->company_name);
		$this->assertEquals("555-1212", $contact->home_phone);
		$this->assertEquals("555-1213", $contact->work_phone);
		$this->assertEquals("555-1214", $contact->cell_phone);
		$this->assertEquals("69f9d72b-0a5e-479d-b844-722b1da9595f", $contact->source_details);
		$this->assertEquals("ACTION_BY_VISITOR", $contact->action_by);
		$this->assertEquals(false, $contact->confirmed);
		$this->assertEquals("API", $contact->source);
		
		// custom fields
		$this->assertEquals("CustomField1", $contact->custom_fields[0]->name);
		$this->assertEquals("3/28/2011 11:09 AM EDT", $contact->custom_fields[0]->value);
		$this->assertEquals("CustomField2", $contact->custom_fields[1]->name);
		$this->assertEquals("Site owner", $contact->custom_fields[1]->value);
		
		//addresses
		$this->assertEquals("1601 Trapelo Rd", $contact->addresses[0]->line1);
		$this->assertEquals("Suite 329", $contact->addresses[0]->line2);
		$this->assertEquals("Line 3", $contact->addresses[0]->line3);
		$this->assertEquals("Waltham", $contact->addresses[0]->city);
		$this->assertEquals("PERSONAL", $contact->addresses[0]->address_type);
		$this->assertEquals("MA", $contact->addresses[0]->state_code);
		$this->assertEquals("us", $contact->addresses[0]->country_code);
		$this->assertEquals("01720", $contact->addresses[0]->postal_code);
		$this->assertEquals("7885", $contact->addresses[0]->sub_postal_code);
		
		//notes
		$this->assertEquals(1, $contact->notes[0]->id);
		$this->assertEquals("Here are some cool notes to add", $contact->notes[0]->note);
		$this->assertEquals("2012-12-03T17:09:22.702Z", $contact->notes[0]->created_date);
		
		//lists
		$this->assertEquals(9, $contact->lists[0]->id);
		$this->assertEquals("ACTIVE", $contact->lists[0]->status);

		// EmailAddress		
		$this->assertEquals("ACTIVE", $contact->email_addresses[0]->status);
		$this->assertEquals("NO_CONFIRMATION_REQUIRED", $contact->email_addresses[0]->confirm_status);
		$this->assertEquals("ACTION_BY_VISITOR", $contact->email_addresses[0]->opt_in_source);
		$this->assertEquals("2012-09-17T14:40:41.271Z", $contact->email_addresses[0]->opt_in_date);
		$this->assertEquals("2012-03-29T14:59:25.427Z", $contact->email_addresses[0]->opt_out_date);
		$this->assertEquals("john+smith@gmail.com", $contact->email_addresses[0]->email_address);
	}

	public function testGetContactByEmail()
	{
        $rest_client = new MockRestClient(200, JsonLoader::getContactsJson());
		
		$contact_service = new ContactService("apikey", $rest_client);
		$response = $contact_service->getContacts('access_token', array('email' => 'anothertest@roving.com'));

		$this->assertEquals('?next=c3RhcnRBdD0zJmxpbWl0PTI', $response->next);
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

	public function testAddContact()
	{
        $rest_client = new MockRestClient(201, JsonLoader::getContactJson());
		
		$testContact = new Contact();
		$contact_service = new ContactService("apikey", $rest_client);
		$contact = $contact_service->addContact('access_token', $testContact);
		
		$this->assertEquals(238, $contact->id);
		$this->assertEquals("ACTIVE", $contact->status);
		$this->assertEquals("555-1212", $contact->fax);
		$this->assertEquals("Mr.", $contact->prefix_name);
		$this->assertEquals("John", $contact->first_name);
		$this->assertEquals("S", $contact->middle_name);
		$this->assertEquals("Smith", $contact->last_name);
		$this->assertEquals("Software Engineer", $contact->job_title);
		$this->assertEquals("Constant Contact", $contact->company_name);
		$this->assertEquals("555-1212", $contact->home_phone);
		$this->assertEquals("555-1213", $contact->work_phone);
		$this->assertEquals("555-1214", $contact->cell_phone);
		$this->assertEquals("69f9d72b-0a5e-479d-b844-722b1da9595f", $contact->source_details);
		$this->assertEquals("ACTION_BY_VISITOR", $contact->action_by);
		$this->assertEquals(false, $contact->confirmed);
		$this->assertEquals("API", $contact->source);
		
		// custom fields
		$this->assertEquals("CustomField1", $contact->custom_fields[0]->name);
		$this->assertEquals("3/28/2011 11:09 AM EDT", $contact->custom_fields[0]->value);
		$this->assertEquals("CustomField2", $contact->custom_fields[1]->name);
		$this->assertEquals("Site owner", $contact->custom_fields[1]->value);
		
		//addresses
		$this->assertEquals("1601 Trapelo Rd", $contact->addresses[0]->line1);
		$this->assertEquals("Suite 329", $contact->addresses[0]->line2);
		$this->assertEquals("Line 3", $contact->addresses[0]->line3);
		$this->assertEquals("Waltham", $contact->addresses[0]->city);
		$this->assertEquals("PERSONAL", $contact->addresses[0]->address_type);
		$this->assertEquals("MA", $contact->addresses[0]->state_code);
		$this->assertEquals("us", $contact->addresses[0]->country_code);
		$this->assertEquals("01720", $contact->addresses[0]->postal_code);
		$this->assertEquals("7885", $contact->addresses[0]->sub_postal_code);
		
		//notes
		$this->assertEquals(1, $contact->notes[0]->id);
		$this->assertEquals("Here are some cool notes to add", $contact->notes[0]->note);
		$this->assertEquals("2012-12-03T17:09:22.702Z", $contact->notes[0]->created_date);
		
		//lists
		$this->assertEquals(9, $contact->lists[0]->id);
		$this->assertEquals("ACTIVE", $contact->lists[0]->status);

		// EmailAddress		
		$this->assertEquals("ACTIVE", $contact->email_addresses[0]->status);
		$this->assertEquals("NO_CONFIRMATION_REQUIRED", $contact->email_addresses[0]->confirm_status);
		$this->assertEquals("ACTION_BY_VISITOR", $contact->email_addresses[0]->opt_in_source);
		$this->assertEquals("2012-09-17T14:40:41.271Z", $contact->email_addresses[0]->opt_in_date);
		$this->assertEquals("2012-03-29T14:59:25.427Z", $contact->email_addresses[0]->opt_out_date);
		$this->assertEquals("john+smith@gmail.com", $contact->email_addresses[0]->email_address);
	}
	
	public function testDeleteContact()
	{
        $rest_client = new MockRestClient(204, null);
		
		$contact_service = new ContactService("apikey", $rest_client);
		$response = $contact_service->deleteContact('access_token', 1);
		
		$this->assertTrue($response);
	}
	
	public function testDeleteContactFailed()
	{
        $rest_client = new MockRestClient(400, null);
		
		$contact_service = new ContactService("apikey", $rest_client);
		$response = $contact_service->deleteContact('access_token', 1);
		
		$this->assertFalse($response);
	}
	
	public function testDeleteContactFromLists()
	{
        $rest_client = new MockRestClient(204, null);
		
		$contact_service = new ContactService("apikey", $rest_client);
		$response = $contact_service->deleteContactFromLists('access_token', 9);
		
		$this->assertTrue($response);
	}
	
	public function testDeleteContactFromListsFailed()
	{
        $rest_client = new MockRestClient(400, null);
		
		$contact_service = new ContactService("apikey", $rest_client);
		$response = $contact_service->deleteContactFromLists('access_token', 9);
		
		$this->assertFalse($response);
	}
	
	public function testDeleteContactFromList()
	{
        $rest_client = new MockRestClient(204, null);
		
		$contact_service = new ContactService("apikey", $rest_client);
		$response = $contact_service->deleteContactFromList('access_token', 9, 1);
		
		$this->assertTrue($response);
	}
	
	public function test_deleteContact_from_list_failed()
	{
        $rest_client = new MockRestClient(400, null);
		
		$contact_service = new ContactService("apikey", $rest_client);
		$response = $contact_service->deleteContactFromList('access_token', 9, 1);
		
		$this->assertFalse($response);
	}
	
	public function testUpdateContact()
	{
        $rest_client = new MockRestClient(200, JsonLoader::getContactJson());
		
		$contact_service = new ContactService("apikey", $rest_client);
		$contact = $contact_service->updateContact('access_token', new Contact());
		
		$this->assertEquals(238, $contact->id);
		$this->assertEquals("ACTIVE", $contact->status);
		$this->assertEquals("555-1212", $contact->fax);
		$this->assertEquals("Mr.", $contact->prefix_name);
		$this->assertEquals("John", $contact->first_name);
		$this->assertEquals("S", $contact->middle_name);
		$this->assertEquals("Smith", $contact->last_name);
		$this->assertEquals("Software Engineer", $contact->job_title);
		$this->assertEquals("Constant Contact", $contact->company_name);
		$this->assertEquals("555-1212", $contact->home_phone);
		$this->assertEquals("555-1213", $contact->work_phone);
		$this->assertEquals("555-1214", $contact->cell_phone);
		$this->assertEquals("69f9d72b-0a5e-479d-b844-722b1da9595f", $contact->source_details);
		$this->assertEquals("ACTION_BY_VISITOR", $contact->action_by);
		$this->assertEquals(false, $contact->confirmed);
		$this->assertEquals("API", $contact->source);
		
		// custom fields
		$this->assertEquals("CustomField1", $contact->custom_fields[0]->name);
		$this->assertEquals("3/28/2011 11:09 AM EDT", $contact->custom_fields[0]->value);
		$this->assertEquals("CustomField2", $contact->custom_fields[1]->name);
		$this->assertEquals("Site owner", $contact->custom_fields[1]->value);
		
		//addresses
		$this->assertEquals("1601 Trapelo Rd", $contact->addresses[0]->line1);
		$this->assertEquals("Suite 329", $contact->addresses[0]->line2);
		$this->assertEquals("Line 3", $contact->addresses[0]->line3);
		$this->assertEquals("Waltham", $contact->addresses[0]->city);
		$this->assertEquals("PERSONAL", $contact->addresses[0]->address_type);
		$this->assertEquals("MA", $contact->addresses[0]->state_code);
		$this->assertEquals("us", $contact->addresses[0]->country_code);
		$this->assertEquals("01720", $contact->addresses[0]->postal_code);
		$this->assertEquals("7885", $contact->addresses[0]->sub_postal_code);
		
		//notes
		$this->assertEquals(1, $contact->notes[0]->id);
		$this->assertEquals("Here are some cool notes to add", $contact->notes[0]->note);
		$this->assertEquals("2012-12-03T17:09:22.702Z", $contact->notes[0]->created_date);
		
		//lists
		$this->assertEquals(9, $contact->lists[0]->id);
		$this->assertEquals("ACTIVE", $contact->lists[0]->status);

		// EmailAddress		
		$this->assertEquals("ACTIVE", $contact->email_addresses[0]->status);
		$this->assertEquals("NO_CONFIRMATION_REQUIRED", $contact->email_addresses[0]->confirm_status);
		$this->assertEquals("ACTION_BY_VISITOR", $contact->email_addresses[0]->opt_in_source);
		$this->assertEquals("2012-09-17T14:40:41.271Z", $contact->email_addresses[0]->opt_in_date);
		$this->assertEquals("2012-03-29T14:59:25.427Z", $contact->email_addresses[0]->opt_out_date);
		$this->assertEquals("john+smith@gmail.com", $contact->email_addresses[0]->email_address);
	}

}
