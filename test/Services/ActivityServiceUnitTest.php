<?php

use Ctct\Services\ActivityService;
use Ctct\Util\RestClient;
use Ctct\Components\Contacts\Address;
use Ctct\Components\Contacts\CustomField;
use Ctct\Components\Activities\ExportContacts;
use Ctct\Components\Activities\AddContacts;
use Ctct\Components\Activities\AddContactsImportData;
use Ctct\Util\CurlResponse;

class ActivityServiceUnitTest extends PHPUnit_Framework_TestCase
{

    private $restClient;
    private $activityService;

    public function setUp()
    {
        $this->restClient = $this->getMock('Ctct\Util\RestClientInterface');
        $this->activityService = new ActivityService("apikey", $this->restClient);
    }

    public function testGetActivity()
    {
        $curlResponse = CurlResponse::create(JsonLoader::getActivity(), array('http_code' => 200));
        $this->restClient->expects($this->once())
            ->method('get')
            ->with()
            ->will($this->returnValue($curlResponse));

        $activity = $this->activityService->getActivity("accessToken", "a07e1ikxwuphd4nwjxl");
        $this->assertInstanceOf('Ctct\Components\Activities\Activity', $activity);
        $this->assertEquals("a07e1ikxyomhd4la0o9", $activity->id);
        $this->assertEquals("REMOVE_CONTACTS_FROM_LISTS", $activity->type);
        $this->assertEquals("COMPLETE", $activity->status);
        $this->assertEquals("2013-02-13T14:43:01.635Z", $activity->start_date);
        $this->assertEquals("2013-02-13T14:43:01.662Z", $activity->finish_date);
        $this->assertEquals("2013-02-13T14:42:44.073Z", $activity->created_date);
        $this->assertEquals(2, $activity->error_count);
        $this->assertEquals(2, $activity->contact_count);
        $this->assertEquals("test@roving.com (not found in subscriber list)", $activity->errors[0]->message);
        $this->assertEquals(0, $activity->errors[0]->line_number);
    }

    public function testGetActivities()
    {
        $curlResponse = CurlResponse::create(JsonLoader::getActivities(), array('http_code' => 200));
        $this->restClient->expects($this->once())
            ->method('get')
            ->with()
            ->will($this->returnValue($curlResponse));

        $activities = $this->activityService->getActivities('access_token');
        $activity = $activities[0];
        $this->assertInstanceOf('Ctct\Components\Activities\Activity', $activity);
        $this->assertEquals("a07e1ikxwuphd4nwjxl", $activity->id);
        $this->assertEquals("EXPORT_CONTACTS", $activity->type);
        $this->assertEquals("COMPLETE", $activity->status);
        $this->assertEquals("2013-02-13T15:57:03.627Z", $activity->start_date);
        $this->assertEquals("2013-02-13T15:57:03.649Z", $activity->finish_date);
        $this->assertEquals("2013-02-13T15:56:14.697Z", $activity->created_date);
        $this->assertEquals(0, $activity->error_count);
        $this->assertEquals(0, $activity->contact_count);
    }

    public function testGetActivitiesWithType()
    {
        $curlResponse = CurlResponse::create(JsonLoader::getActivities(), array('http_code' => 200));
        $this->restClient->expects($this->once())
            ->method('get')
            ->with()
            ->will($this->returnValue($curlResponse));

        $activities = $this->activityService->getActivities('access_token', array('type' => 'EXPORT_CONTACTS'));
        $activity = $activities[0];
        $this->assertInstanceOf('Ctct\Components\Activities\Activity', $activity);
        $this->assertEquals("a07e1ikxwuphd4nwjxl", $activity->id);
        $this->assertEquals("EXPORT_CONTACTS", $activity->type);
        $this->assertEquals("COMPLETE", $activity->status);
        $this->assertEquals("2013-02-13T15:57:03.627Z", $activity->start_date);
        $this->assertEquals("2013-02-13T15:57:03.649Z", $activity->finish_date);
        $this->assertEquals("2013-02-13T15:56:14.697Z", $activity->created_date);
        $this->assertEquals(0, $activity->error_count);
        $this->assertEquals(0, $activity->contact_count);
    }

    public function testAddClearListsActivity()
    {
        $curlResponse = CurlResponse::create(JsonLoader::getClearListsActivity(), array('http_code' => 201));
        $this->restClient->expects($this->once())
            ->method('post')
            ->with()
            ->will($this->returnValue($curlResponse));

        $activity = $this->activityService->addClearListsActivity("access_token", array("1", "2"));
        $this->assertInstanceOf('Ctct\Components\Activities\Activity', $activity);
        $this->assertEquals("a07e1il69fwhd7uan9h", $activity->id);
        $this->assertEquals("CLEAR_CONTACTS_FROM_LISTS", $activity->type);
        $this->assertEquals(0, $activity->error_count);
        $this->assertEquals(0, $activity->contact_count);
    }

    public function testAddExportContactsActivity()
    {
        $curlResponse = CurlResponse::create(JsonLoader::getExportContactsActivity(), array('http_code' => 201));
        $this->restClient->expects($this->once())
            ->method('post')
            ->with()
            ->will($this->returnValue($curlResponse));

        $exportContacts = new ExportContacts(array("1", "2"));
        $activity = $this->activityService->addExportContactsActivity("access_token", $exportContacts);
        $this->assertInstanceOf('Ctct\Components\Activities\Activity', $activity);
        $this->assertEquals("a07e1i5nqamhcfeuu0h", $activity->id);
        $this->assertEquals("EXPORT_CONTACTS", $activity->type);
        $this->assertEquals(0, $activity->error_count);
        $this->assertEquals(0, $activity->contact_count);
    }

    public function testAddRemoveContactsFromListsActivity()
    {
        $curlResponse = CurlResponse::create(
            JsonLoader::getRemoveContactsFromListsActivity(),
            array('http_code' => 201)
        );

        $this->restClient->expects($this->once())
            ->method('post')
            ->with()
            ->will($this->returnValue($curlResponse));

        $emailAddresses = array("djellesma@roving.com", "djellesma@constantcontact.com");
        $lists = array("1", "2");
        $activity = $this->activityService->addRemoveContactsFromListsActivity("access_token", $emailAddresses, $lists);
        $this->assertInstanceOf('Ctct\Components\Activities\Activity', $activity);
        $this->assertEquals("a07e1i5nqamhcfeuu0h", $activity->id);
        $this->assertEquals("REMOVE_CONTACTS_FROM_LISTS", $activity->type);
        $this->assertEquals(0, $activity->error_count);
        $this->assertEquals(0, $activity->contact_count);
    }

    public function testAddRemoveContactsFromListsActivityFromFile()
    {
        $curlResponse = CurlResponse::create(
            JsonLoader::getRemoveContactsFromListsActivity(),
            array('http_code' => 201)
        );

        $this->restClient->expects($this->once())
            ->method('post')
            ->with()
            ->will($this->returnValue($curlResponse));

        $activity = $this->activityService->addRemoveContactsFromListsActivityFromFile(
            'access_token',
            'contacts.txt',
            JsonLoader::getRemoveContactsTextContents(),
            '7'
        );


        $this->assertInstanceOf('Ctct\Components\Activities\Activity', $activity);
        $this->assertEquals("a07e1i5nqamhcfeuu0h", $activity->id);
        $this->assertEquals("REMOVE_CONTACTS_FROM_LISTS", $activity->type);
        $this->assertEquals(0, $activity->error_count);
        $this->assertEquals(0, $activity->contact_count);
    }

    public function testAddCreateContactsActivity()
    {
        $curlResponse = CurlResponse::create(JsonLoader::getAddContactsActivity(), array('http_code' => 201));
        $this->restClient->expects($this->once())
            ->method('post')
            ->with()
            ->will($this->returnValue($curlResponse));

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

        $activity = $this->activityService->createAddContactsActivity("access_token", $addContacts);
        $this->assertInstanceOf('Ctct\Components\Activities\Activity', $activity);
        $this->assertEquals("a07e1il69qzhdby44ro", $activity->id);
        $this->assertEquals("ADD_CONTACTS", $activity->type);
        $this->assertEquals(0, $activity->error_count);
        $this->assertEquals(1, $activity->contact_count);
    }

    public function testAddCreateContactsActivityFromFile()
    {

        $curlResponse = CurlResponse::create(JsonLoader::getAddContactsActivity(), array('http_code' => 201));
        $this->restClient->expects($this->once())
            ->method('post')
            ->with()
            ->will($this->returnValue($curlResponse));

        $activity = $this->activityService->createAddContactsActivityFromFile(
            "access_token",
            'contacts.txt',
            JsonLoader::getContactsTextContents(),
            '7'
        );

        $this->assertInstanceOf('Ctct\Components\Activities\Activity', $activity);
        $this->assertEquals("a07e1il69qzhdby44ro", $activity->id);
        $this->assertEquals("ADD_CONTACTS", $activity->type);
        $this->assertEquals(0, $activity->error_count);
        $this->assertEquals(1, $activity->contact_count);
    }
}
