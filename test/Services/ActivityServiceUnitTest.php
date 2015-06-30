<?php
use Ctct\Components\Activities\Activity;
use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Message\Response;

class ActivityServiceUnitTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    private static $client;

    public static function setUpBeforeClass()
    {
        self::$client = new Client();
        $activityStream = Stream::factory(JsonLoader::getActivity());
        $activitiesStream = Stream::factory(JsonLoader::getActivities());
        $clearListActivityStream = Stream::factory(JsonLoader::getClearListsActivity());
        $exportActivityStream = Stream::factory(JsonLoader::getExportContactsActivity());
        $removeContactsFromListStream = Stream::factory(JsonLoader::getRemoveContactsFromListsActivity());
        $addContactsStream = Stream::factory(JsonLoader::getAddContactsActivity());
        $mock = new Mock([
            new Response(200, array(), $activityStream),
            new Response(200, array(), $activitiesStream),
            new Response(201, array(), $clearListActivityStream),
            new Response(201, array(), $exportActivityStream),
            new Response(201, array(), $removeContactsFromListStream),
            new Response(201, array(), $addContactsStream)
        ]);
        self::$client->getEmitter()->attach($mock);
    }

    public function testGetActivity()
    {
        $response = self::$client->get('/');

        $activity = Activity::create($response->json());
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
        $response = self::$client->get('/');
        $activities = array();
        foreach ($response->json() as $activityResponse) {
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

    public function testAddClearListsActivity()
    {
        $response = self::$client->post('/');

        $activity = Activity::create($response->json());
        $this->assertInstanceOf('Ctct\Components\Activities\Activity', $activity);
        $this->assertEquals("a07e1il69fwhd7uan9h", $activity->id);
        $this->assertEquals("CLEAR_CONTACTS_FROM_LISTS", $activity->type);
        $this->assertEquals(0, $activity->error_count);
        $this->assertEquals(0, $activity->contact_count);
    }

    public function testAddExportContactsActivity()
    {
        $response = self::$client->post('/');

        $activity = Activity::create($response->json());
        $this->assertInstanceOf('Ctct\Components\Activities\Activity', $activity);
        $this->assertEquals("a07e1i5nqamhcfeuu0h", $activity->id);
        $this->assertEquals("EXPORT_CONTACTS", $activity->type);
        $this->assertEquals(0, $activity->error_count);
        $this->assertEquals(0, $activity->contact_count);
    }

    public function testAddRemoveContactsFromListsActivity()
    {
        $response = self::$client->post('/');

        $activity = Activity::create($response->json());
        $this->assertInstanceOf('Ctct\Components\Activities\Activity', $activity);
        $this->assertEquals("a07e1i5nqamhcfeuu0h", $activity->id);
        $this->assertEquals("REMOVE_CONTACTS_FROM_LISTS", $activity->type);
        $this->assertEquals(0, $activity->error_count);
        $this->assertEquals(0, $activity->contact_count);
    }

    public function testAddCreateContactsActivity()
    {
        $response = self::$client->post('/');

        $activity = Activity::create($response->json());
        $this->assertInstanceOf('Ctct\Components\Activities\Activity', $activity);
        $this->assertEquals("a07e1il69qzhdby44ro", $activity->id);
        $this->assertEquals("ADD_CONTACTS", $activity->type);
        $this->assertEquals(0, $activity->error_count);
        $this->assertEquals(1, $activity->contact_count);
    }
}
