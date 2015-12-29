<?php
use Ctct\Components\Activities\Activity;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class ActivityServiceUnitTest extends PHPUnit_Framework_TestCase {
    /**
     * @var Client
     */
    private static $client;

    public static function setUpBeforeClass() {
        $mock = new MockHandler([
            new Response(200, array(), JsonLoader::getActivity()),
            new Response(200, array(), JsonLoader::getActivities()),
            new Response(201, array(), JsonLoader::getClearListsActivity()),
            new Response(201, array(), JsonLoader::getExportContactsActivity()),
            new Response(201, array(), JsonLoader::getRemoveContactsFromListsActivity()),
            new Response(201, array(), JsonLoader::getAddContactsActivity())
        ]);
        $handler = HandlerStack::create($mock);
        self::$client = new Client(['handler' => $handler]);
    }

    public function testGetActivity() {
        $response = self::$client->request('GET', '/');

        $activity = Activity::create(json_decode($response->getBody(), true));
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

    public function testGetActivities() {
        $response = self::$client->request('GET', '/');
        $activities = array();
        foreach (json_decode($response->getBody(), true) as $activityResponse) {
            $activities[] = Activity::create($activityResponse);
        }
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

    public function testAddClearListsActivity() {
        $response = self::$client->request('POST', '/');

        $activity = Activity::create(json_decode($response->getBody(), true));
        $this->assertInstanceOf('Ctct\Components\Activities\Activity', $activity);
        $this->assertEquals("a07e1il69fwhd7uan9h", $activity->id);
        $this->assertEquals("CLEAR_CONTACTS_FROM_LISTS", $activity->type);
        $this->assertEquals(0, $activity->error_count);
        $this->assertEquals(0, $activity->contact_count);
    }

    public function testAddExportContactsActivity() {
        $response = self::$client->request('POST', '/');

        $activity = Activity::create(json_decode($response->getBody(), true));
        $this->assertInstanceOf('Ctct\Components\Activities\Activity', $activity);
        $this->assertEquals("a07e1i5nqamhcfeuu0h", $activity->id);
        $this->assertEquals("EXPORT_CONTACTS", $activity->type);
        $this->assertEquals(0, $activity->error_count);
        $this->assertEquals(0, $activity->contact_count);
    }

    public function testAddRemoveContactsFromListsActivity() {
        $response = self::$client->request('POST', '/');

        $activity = Activity::create(json_decode($response->getBody(), true));
        $this->assertInstanceOf('Ctct\Components\Activities\Activity', $activity);
        $this->assertEquals("a07e1i5nqamhcfeuu0h", $activity->id);
        $this->assertEquals("REMOVE_CONTACTS_FROM_LISTS", $activity->type);
        $this->assertEquals(0, $activity->error_count);
        $this->assertEquals(0, $activity->contact_count);
    }

    public function testAddCreateContactsActivity() {
        $response = self::$client->request('POST', '/');

        $activity = Activity::create(json_decode($response->getBody(), true));
        $this->assertInstanceOf('Ctct\Components\Activities\Activity', $activity);
        $this->assertEquals("a07e1il69qzhdby44ro", $activity->id);
        $this->assertEquals("ADD_CONTACTS", $activity->type);
        $this->assertEquals(0, $activity->error_count);
        $this->assertEquals(1, $activity->contact_count);
    }
}
