<?php

use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\ResultSet;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class ListServiceUnitTest extends PHPUnit_Framework_TestCase {
    /**
     * @var Client
     */
    private static $client;

    public static function setUpBeforeClass() {
        self::$client = new Client();
        $getListStream = JsonLoader::getListJson();
        $mock = new MockHandler([
            new Response(200, array(), JsonLoader::getListsJson()),
            new Response(200, array(), $getListStream),
            new Response(201, array(), $getListStream),
            new Response(200, array(), $getListStream),
            new Response(200, array(), JsonLoader::getContactsJson())
        ]);
        $handler = HandlerStack::create($mock);
        self::$client = new Client(['handler' => $handler]);
    }

    public function testGetLists() {
        $response = self::$client->request('GET', '/');

        $lists = array();
        foreach (json_decode($response->getBody(), true) as $list) {
            $lists[] = ContactList::create($list);
        }
        $this->assertInstanceOf('Ctct\Components\Contacts\ContactList', $lists[0]);
        $this->assertEquals(1, $lists[0]->id);
        $this->assertEquals("General Interest", $lists[0]->name);
        $this->assertEquals("ACTIVE", $lists[0]->status);
        $this->assertEquals(17, $lists[0]->contact_count);

        $this->assertEquals(3, $lists[1]->id);
        $this->assertEquals("mod_Test List 1", $lists[1]->name);
        $this->assertEquals("HIDDEN", $lists[1]->status);
        $this->assertEquals(18, $lists[1]->contact_count);
    }

    public function testGetList() {
        $response = self::$client->request('GET', '/');
        $list = ContactList::create(json_decode($response->getBody(), true));
        $this->assertInstanceOf('Ctct\Components\Contacts\ContactList', $list);
        $this->assertEquals(6, $list->id);
        $this->assertEquals("Test List 4", $list->name);
        $this->assertEquals("HIDDEN", $list->status);
        $this->assertEquals(19, $list->contact_count);
    }

    public function testAddList() {
        $response = self::$client->request('POST', '/');
        $list = ContactList::create(json_decode($response->getBody(), true));
        $this->assertInstanceOf('Ctct\Components\Contacts\ContactList', $list);
        $this->assertEquals(6, $list->id);
        $this->assertEquals("Test List 4", $list->name);
        $this->assertEquals("HIDDEN", $list->status);
        $this->assertEquals(19, $list->contact_count);
    }

    public function testUpdateList() {
        $response = self::$client->request('PUT', '/');
        $list = ContactList::create(json_decode($response->getBody(), true));
        $this->assertInstanceOf('Ctct\Components\Contacts\ContactList', $list);
        $this->assertEquals(6, $list->id);
        $this->assertEquals("Test List 4", $list->name);
        $this->assertEquals("HIDDEN", $list->status);
        $this->assertEquals(19, $list->contact_count);
    }

    public function testGetContactsFromList() {
        $response = json_decode(self::$client->request('GET', '/')->getBody(), true);
        $result = new ResultSet($response['results'], $response['meta']);
        $this->assertInstanceOf('Ctct\Components\ResultSet', $result);

        $contact = Contact::create($result->results[1]);

        $this->assertInstanceOf('Ctct\Components\Contacts\Contact', $contact);
        $this->assertEquals(231, $contact->id);
        $this->assertEquals("ACTIVE", $contact->status);
        $this->assertEquals("", $contact->fax);
        $this->assertEquals("", $contact->prefix_name);
        $this->assertEquals("Jimmy", $contact->first_name);
        $this->assertEquals("Roving", $contact->last_name);
        $this->assertEquals("Bear Tamer", $contact->job_title);
        $this->assertEquals("Animal Trainer Pro", $contact->company_name);
        $this->assertEquals("details", $contact->source_details);
        $this->assertEquals(false, $contact->confirmed);
        $this->assertEquals("", $contact->source);

        // custom fields
        $this->assertInstanceOf('Ctct\Components\Contacts\CustomField', $contact->custom_fields[0]);
        $this->assertEquals("CustomField1", $contact->custom_fields[0]->name);
        $this->assertEquals("1", $contact->custom_fields[0]->value);

        //addresses
        $this->assertInstanceOf('Ctct\Components\Contacts\Address', $contact->addresses[0]);
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
        $this->assertInstanceOf('Ctct\Components\Contacts\ContactList', $contact->lists[0]);
        $this->assertEquals(1, $contact->lists[0]->id);
        $this->assertEquals("ACTIVE", $contact->lists[0]->status);

        // EmailAddress
        $this->assertInstanceOf('Ctct\Components\Contacts\EmailAddress', $contact->email_addresses[0]);
        $this->assertEquals("ACTIVE", $contact->email_addresses[0]->status);
        $this->assertEquals("NO_CONFIRMATION_REQUIRED", $contact->email_addresses[0]->confirm_status);
        $this->assertEquals("ACTION_BY_OWNER", $contact->email_addresses[0]->opt_in_source);
        $this->assertEquals("2012-06-22T10:29:09.976Z", $contact->email_addresses[0]->opt_in_date);
        $this->assertEquals("", $contact->email_addresses[0]->opt_out_date);
        $this->assertEquals("anothertest@roving.com", $contact->email_addresses[0]->email_address);
    }
}
