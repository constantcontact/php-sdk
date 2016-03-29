<?php

use Ctct\Components\Contacts\Contact;
use Ctct\ConstantContact;
use Ctct\Exceptions\CtctException;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class ContactServiceUnitTest extends PHPUnit_Framework_TestCase {
    /**
     * @var ConstantContact
     */
    private static $constantContact;

    public static function setUpBeforeClass() {
        $contactJson = JsonLoader::getContactJson();
        $mock = new MockHandler([
            new Response(200, array(), JsonLoader::getContactsJson()),
            new Response(200, array(), JsonLoader::getContactsNoNextJson()),
            new Response(200, array(), $contactJson),
            new Response(201, array(), $contactJson),
            new Response(204, array()),
            new Response(400, array(), JsonLoader::getErrorResponseJson()),
            new Response(200, array(), $contactJson)
        ]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        self::$constantContact = new ConstantContact('API_KEY', $client);
    }

    public function testGetContacts() {
        $result = self::$constantContact->contactService->getContacts('ACCESS_TOKEN');
        $this->assertInstanceOf('Ctct\Components\ResultSet', $result);
        $this->assertEquals('c3RhcnRBdD0zJmxpbWl0PTI', $result->next);

        $contact = $result->results[1];
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
        $customField = $contact->custom_fields[0];
        $this->assertEquals("CustomField1", $customField->name);
        $this->assertInstanceOf('Ctct\Components\Contacts\CustomField', $customField);
        $this->assertEquals("1", $customField->value);

        //addresses
        $address = $contact->addresses[0];
        $this->assertInstanceOf('Ctct\Components\Contacts\Address', $address);
        $this->assertEquals("Suite 101", $address->line1);
        $this->assertEquals("line2", $address->line2);
        $this->assertEquals("line3", $address->line3);
        $this->assertEquals("Brookfield", $address->city);
        $this->assertEquals("PERSONAL", $address->address_type);
        $this->assertEquals("WI", $address->state_code);
        $this->assertEquals("us", $address->country_code);
        $this->assertEquals("53027", $address->postal_code);
        $this->assertEquals("", $address->sub_postal_code);

        //notes
        $this->assertEquals(0, count($contact->notes));

        //lists
        $this->assertEquals(1, $contact->lists[0]->id);
        $this->assertEquals("ACTIVE", $contact->lists[0]->status);

        // EmailAddress
        $emailAddress = $contact->email_addresses[0];
        $this->assertInstanceOf('Ctct\Components\Contacts\EmailAddress', $emailAddress);
        $this->assertEquals("ACTIVE", $emailAddress->status);
        $this->assertEquals("NO_CONFIRMATION_REQUIRED", $emailAddress->confirm_status);
        $this->assertEquals("ACTION_BY_OWNER", $emailAddress->opt_in_source);
        $this->assertEquals("2012-06-22T10:29:09.976Z", $emailAddress->opt_in_date);
        $this->assertEquals("", $emailAddress->opt_out_date);
        $this->assertEquals("anothertest@roving.com", $emailAddress->email_address);
    }

    public function testGetContactsNoNextLink() {
        $result = self::$constantContact->contactService->getContacts('ACCESS_TOKEN');

        $this->assertInstanceOf('Ctct\Components\ResultSet', $result);
        $this->assertEquals(null, $result->next);

        $contact = $result->results[1];
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
        $customField = $contact->custom_fields[0];
        $this->assertEquals("CustomField1", $customField->name);
        $this->assertInstanceOf('Ctct\Components\Contacts\CustomField', $customField);
        $this->assertEquals("1", $customField->value);

        //addresses
        $address = $contact->addresses[0];
        $this->assertInstanceOf('Ctct\Components\Contacts\Address', $address);
        $this->assertEquals("Suite 101", $address->line1);
        $this->assertEquals("line2", $address->line2);
        $this->assertEquals("line3", $address->line3);
        $this->assertEquals("Brookfield", $address->city);
        $this->assertEquals("PERSONAL", $address->address_type);
        $this->assertEquals("WI", $address->state_code);
        $this->assertEquals("us", $address->country_code);
        $this->assertEquals("53027", $address->postal_code);
        $this->assertEquals("", $address->sub_postal_code);

        //notes
        $this->assertEquals(0, count($contact->notes));

        //lists
        $this->assertEquals(1, $contact->lists[0]->id);
        $this->assertEquals("ACTIVE", $contact->lists[0]->status);

        // EmailAddress
        $emailAddress = $contact->email_addresses[0];
        $this->assertInstanceOf('Ctct\Components\Contacts\EmailAddress', $emailAddress);
        $this->assertEquals("ACTIVE", $emailAddress->status);
        $this->assertEquals("NO_CONFIRMATION_REQUIRED", $emailAddress->confirm_status);
        $this->assertEquals("ACTION_BY_OWNER", $emailAddress->opt_in_source);
        $this->assertEquals("2012-06-22T10:29:09.976Z", $emailAddress->opt_in_date);
        $this->assertEquals("", $emailAddress->opt_out_date);
        $this->assertEquals("anothertest@roving.com", $emailAddress->email_address);
    }

    public function testGetContact() {
        $contact = self::$constantContact->contactService->getContact('ACCESS_TOKEN', 238);

        $this->assertInstanceOf('Ctct\Components\Contacts\Contact', $contact);
        $this->assertEquals(238, $contact->id);
        $this->assertEquals("ACTIVE", $contact->status);
        $this->assertEquals("555-1212", $contact->fax);
        $this->assertEquals("Mr.", $contact->prefix_name);
        $this->assertEquals("John", $contact->first_name);
        $this->assertEquals("Smith", $contact->last_name);
        $this->assertEquals("Software Engineer", $contact->job_title);
        $this->assertEquals("Constant Contact", $contact->company_name);
        $this->assertEquals("555-1212", $contact->home_phone);
        $this->assertEquals("555-1213", $contact->work_phone);
        $this->assertEquals("555-1214", $contact->cell_phone);
        $this->assertEquals("69f9d72b-0a5e-479d-b844-722b1da9595f", $contact->source_details);
        $this->assertEquals(false, $contact->confirmed);
        $this->assertEquals("API", $contact->source);

        // custom fields
        $this->assertInstanceOf('Ctct\Components\Contacts\CustomField', $contact->custom_fields[0]);
        $this->assertEquals("CustomField1", $contact->custom_fields[0]->name);
        $this->assertEquals("3/28/2011 11:09 AM EDT", $contact->custom_fields[0]->value);
        $this->assertEquals("CustomField2", $contact->custom_fields[1]->name);
        $this->assertEquals("Site owner", $contact->custom_fields[1]->value);

        //addresses
        $this->assertInstanceOf('Ctct\Components\Contacts\Address', $contact->addresses[0]);
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
        $this->assertInstanceOf('Ctct\Components\Contacts\Note', $contact->notes[0]);
        $this->assertEquals(1, $contact->notes[0]->id);
        $this->assertEquals("Here are some cool notes to add", $contact->notes[0]->note);
        $this->assertEquals("2012-12-03T17:09:22.702Z", $contact->notes[0]->created_date);

        //lists
        $this->assertInstanceOf('Ctct\Components\Contacts\ContactList', $contact->lists[0]);
        $this->assertEquals(9, $contact->lists[0]->id);
        $this->assertEquals("ACTIVE", $contact->lists[0]->status);

        // EmailAddress
        $this->assertInstanceOf('Ctct\Components\Contacts\EmailAddress', $contact->email_addresses[0]);
        $this->assertEquals("ACTIVE", $contact->email_addresses[0]->status);
        $this->assertEquals("NO_CONFIRMATION_REQUIRED", $contact->email_addresses[0]->confirm_status);
        $this->assertEquals("ACTION_BY_VISITOR", $contact->email_addresses[0]->opt_in_source);
        $this->assertEquals("2012-09-17T14:40:41.271Z", $contact->email_addresses[0]->opt_in_date);
        $this->assertEquals("2012-03-29T14:59:25.427Z", $contact->email_addresses[0]->opt_out_date);
        $this->assertEquals("john+smith@gmail.com", $contact->email_addresses[0]->email_address);
    }

    public function testAddContact() {
        $sourceContact = Contact::create((array) JsonLoader::getContactJson());
        $contact = self::$constantContact->contactService->addContact('ACCESS_TOKEN', $sourceContact, true);

        $this->assertInstanceOf('Ctct\Components\Contacts\Contact', $contact);
        $this->assertEquals(238, $contact->id);
        $this->assertEquals("ACTIVE", $contact->status);
        $this->assertEquals("555-1212", $contact->fax);
        $this->assertEquals("Mr.", $contact->prefix_name);
        $this->assertEquals("John", $contact->first_name);
        $this->assertEquals("Smith", $contact->last_name);
        $this->assertEquals("Software Engineer", $contact->job_title);
        $this->assertEquals("Constant Contact", $contact->company_name);
        $this->assertEquals("555-1212", $contact->home_phone);
        $this->assertEquals("555-1213", $contact->work_phone);
        $this->assertEquals("555-1214", $contact->cell_phone);
        $this->assertEquals("69f9d72b-0a5e-479d-b844-722b1da9595f", $contact->source_details);
        $this->assertEquals(false, $contact->confirmed);
        $this->assertEquals("API", $contact->source);

        // custom fields
        $this->assertInstanceOf('Ctct\Components\Contacts\CustomField', $contact->custom_fields[0]);
        $this->assertEquals("CustomField1", $contact->custom_fields[0]->name);
        $this->assertEquals("3/28/2011 11:09 AM EDT", $contact->custom_fields[0]->value);
        $this->assertEquals("CustomField2", $contact->custom_fields[1]->name);
        $this->assertEquals("Site owner", $contact->custom_fields[1]->value);

        //addresses
        $this->assertInstanceOf('Ctct\Components\Contacts\Address', $contact->addresses[0]);
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
        $this->assertInstanceOf('Ctct\Components\Contacts\Note', $contact->notes[0]);
        $this->assertEquals(1, $contact->notes[0]->id);
        $this->assertEquals("Here are some cool notes to add", $contact->notes[0]->note);
        $this->assertEquals("2012-12-03T17:09:22.702Z", $contact->notes[0]->created_date);

        //lists
        $this->assertInstanceOf('Ctct\Components\Contacts\ContactList', $contact->lists[0]);
        $this->assertEquals(9, $contact->lists[0]->id);
        $this->assertEquals("ACTIVE", $contact->lists[0]->status);

        // EmailAddress
        $this->assertInstanceOf('Ctct\Components\Contacts\EmailAddress', $contact->email_addresses[0]);
        $this->assertEquals("ACTIVE", $contact->email_addresses[0]->status);
        $this->assertEquals("NO_CONFIRMATION_REQUIRED", $contact->email_addresses[0]->confirm_status);
        $this->assertEquals("ACTION_BY_VISITOR", $contact->email_addresses[0]->opt_in_source);
        $this->assertEquals("2012-09-17T14:40:41.271Z", $contact->email_addresses[0]->opt_in_date);
        $this->assertEquals("2012-03-29T14:59:25.427Z", $contact->email_addresses[0]->opt_out_date);
        $this->assertEquals("john+smith@gmail.com", $contact->email_addresses[0]->email_address);
    }

    public function testDeleteContact() {
        $success = self::$constantContact->contactService->unsubscribeContact('ACCESS_TOKEN', 238);

        $this->assertTrue($success);
    }

    public function testDeleteContactFailed() {
        try {
            self::$constantContact->contactService->unsubscribeContact('ACCESS_TOKEN', 238);
            $this->fail("Delete call didn't fail");
        } catch (CtctException $exception) {
            $this->assertEquals(400, $exception->getCode());
        }
    }

    public function testUpdateContact() {
        $sourceContact = Contact::create((array) JsonLoader::getContactJson());
        $contact = self::$constantContact->contactService->updateContact('ACCESS_TOKEN', $sourceContact, true);

        $this->assertInstanceOf('Ctct\Components\Contacts\Contact', $contact);
        $this->assertEquals(238, $contact->id);
        $this->assertEquals("ACTIVE", $contact->status);
        $this->assertEquals("555-1212", $contact->fax);
        $this->assertEquals("Mr.", $contact->prefix_name);
        $this->assertEquals("John", $contact->first_name);
        $this->assertEquals("Smith", $contact->last_name);
        $this->assertEquals("Software Engineer", $contact->job_title);
        $this->assertEquals("Constant Contact", $contact->company_name);
        $this->assertEquals("555-1212", $contact->home_phone);
        $this->assertEquals("555-1213", $contact->work_phone);
        $this->assertEquals("555-1214", $contact->cell_phone);
        $this->assertEquals("69f9d72b-0a5e-479d-b844-722b1da9595f", $contact->source_details);
        $this->assertEquals(false, $contact->confirmed);
        $this->assertEquals("API", $contact->source);

        // custom fields
        $this->assertInstanceOf('Ctct\Components\Contacts\CustomField', $contact->custom_fields[0]);
        $this->assertEquals("CustomField1", $contact->custom_fields[0]->name);
        $this->assertEquals("3/28/2011 11:09 AM EDT", $contact->custom_fields[0]->value);
        $this->assertEquals("CustomField2", $contact->custom_fields[1]->name);
        $this->assertEquals("Site owner", $contact->custom_fields[1]->value);

        //addresses
        $this->assertInstanceOf('Ctct\Components\Contacts\Address', $contact->addresses[0]);
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
        $this->assertInstanceOf('Ctct\Components\Contacts\Note', $contact->notes[0]);
        $this->assertEquals(1, $contact->notes[0]->id);
        $this->assertEquals("Here are some cool notes to add", $contact->notes[0]->note);
        $this->assertEquals("2012-12-03T17:09:22.702Z", $contact->notes[0]->created_date);

        //lists
        $this->assertInstanceOf('Ctct\Components\Contacts\ContactList', $contact->lists[0]);
        $this->assertEquals(9, $contact->lists[0]->id);
        $this->assertEquals("ACTIVE", $contact->lists[0]->status);

        // EmailAddress
        $this->assertInstanceOf('Ctct\Components\Contacts\EmailAddress', $contact->email_addresses[0]);
        $this->assertEquals("ACTIVE", $contact->email_addresses[0]->status);
        $this->assertEquals("NO_CONFIRMATION_REQUIRED", $contact->email_addresses[0]->confirm_status);
        $this->assertEquals("ACTION_BY_VISITOR", $contact->email_addresses[0]->opt_in_source);
        $this->assertEquals("2012-09-17T14:40:41.271Z", $contact->email_addresses[0]->opt_in_date);
        $this->assertEquals("2012-03-29T14:59:25.427Z", $contact->email_addresses[0]->opt_out_date);
        $this->assertEquals("john+smith@gmail.com", $contact->email_addresses[0]->email_address);
    }
}
