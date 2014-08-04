<?php

use Ctct\Util\CurlResponse;
use Ctct\Services\ListService;
use Ctct\Util\RestClient;
use Ctct\Components\Contacts\ContactList;

class ListServiceUnitTest extends PHPUnit_Framework_TestCase
{
    private $restClient;
    private $listService;

    public function setUp()
    {
        $this->restClient = $this->getMock('Ctct\Util\RestClientInterface');
        $this->listService = new ListService("apikey", $this->restClient);
    }

    public function testGetLists()
    {
        $curlResponse = CurlResponse::create(JsonLoader::getListsJson(), array('http_code' => 200));
        $this->restClient->expects($this->once())
            ->method('get')
            ->with()
            ->will($this->returnValue($curlResponse));

        $response = $this->listService->getLists('access_token');

        $this->assertInstanceOf("Ctct\Components\Contacts\ContactList", $response[0]);
        $this->assertEquals(1, $response[0]->id);
        $this->assertEquals("General Interest", $response[0]->name);
        $this->assertEquals("ACTIVE", $response[0]->status);
        $this->assertEquals(17, $response[0]->contact_count);

        $this->assertEquals(3, $response[1]->id);
        $this->assertEquals("mod_Test List 1", $response[1]->name);
        $this->assertEquals("HIDDEN", $response[1]->status);
        $this->assertEquals(18, $response[1]->contact_count);
    }

    public function testGetListsModifiedSince()
    {
        $curlResponse = CurlResponse::create(JsonLoader::getListsJson(), array('http_code' => 200));
        $this->restClient->expects($this->once())
            ->method('get')
            ->with()
            ->will($this->returnValue($curlResponse));

        $response = $this->listService->getLists('access_token', array('modified_since' => '2013-01-12T20:04:59.436Z'));

        $this->assertInstanceOf("Ctct\Components\Contacts\ContactList", $response[0]);
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
        $curlResponse = CurlResponse::create(JsonLoader::getListJson(), array('http_code' => 200));
        $this->restClient->expects($this->once())
            ->method('get')
            ->with()
            ->will($this->returnValue($curlResponse));

        $list = $this->listService->getList('access_token', 6);
        $this->assertInstanceOf("Ctct\Components\Contacts\ContactList", $list);
        $this->assertEquals(6, $list->id);
        $this->assertEquals("Test List 4", $list->name);
        $this->assertEquals("HIDDEN", $list->status);
        $this->assertEquals(19, $list->contact_count);
    }

    public function testAddList()
    {
        $curlResponse = CurlResponse::create(JsonLoader::getListJson(), array('http_code' => 204));
        $this->restClient->expects($this->once())
            ->method('post')
            ->with()
            ->will($this->returnValue($curlResponse));

        $list = $this->listService->addList('access_token', new ContactList());
        $this->assertInstanceOf("Ctct\Components\Contacts\ContactList", $list);
        $this->assertEquals(6, $list->id);
        $this->assertEquals("Test List 4", $list->name);
        $this->assertEquals("HIDDEN", $list->status);
        $this->assertEquals(19, $list->contact_count);
    }

    public function testUpdateList()
    {
        $curlResponse = CurlResponse::create(JsonLoader::getListJson(), array('http_code' => 200));
        $this->restClient->expects($this->once())
            ->method('put')
            ->with()
            ->will($this->returnValue($curlResponse));

        $list = $this->listService->updateList('access_token', new ContactList());
        $this->assertInstanceOf("Ctct\Components\Contacts\ContactList", $list);
        $this->assertEquals(6, $list->id);
        $this->assertEquals("Test List 4", $list->name);
        $this->assertEquals("HIDDEN", $list->status);
        $this->assertEquals(19, $list->contact_count);
    }

    public function testGetContactsFromList()
    {
        $curlResponse = CurlResponse::create(JsonLoader::getContactsJson(), array('http_code' => 200));
        $this->restClient->expects($this->once())
            ->method('get')
            ->with()
            ->will($this->returnValue($curlResponse));

        $response = $this->listService->getContactsFromList('access_token', 1);
        $this->assertInstanceOf("Ctct\Components\ResultSet", $response);

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
