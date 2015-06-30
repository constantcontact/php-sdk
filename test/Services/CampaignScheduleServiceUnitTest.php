<?php

use Ctct\Components\EmailMarketing\Schedule;
use Ctct\Components\EmailMarketing\TestSend;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Message\Response;

class CampaignScheduleServiceUnitTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    private static $client;

    public static function setUpBeforeClass()
    {
        self::$client = new Client();
        $schedulesStream = Stream::factory(JsonLoader::getCampaignSchedulesJson());
        $scheduleStream = Stream::factory(JsonLoader::getCampaignScheduleJson());
        $testSendStream = Stream::factory(JsonLoader::getTestSendJson());
        $mock = new Mock([
            new Response(200, array(), $schedulesStream),
            new Response(200, array(), $scheduleStream),
            new Response(201, array(), $scheduleStream),
            new Response(200, array(), $scheduleStream),
            new Response(204, array()),
            new Response(400, array()),
            new Response(200, array(), $testSendStream)
        ]);
        self::$client->getEmitter()->attach($mock);
    }

    public function testGetSchedules()
    {
        $response = self::$client->get('/');

        $schedules = array();
        foreach ($response->json() as $responseSchedule) {
            $schedules[] = $responseSchedule;
        }

        $schedule1 = Schedule::create($schedules[0]);
        $this->assertInstanceOf('Ctct\Components\EmailMarketing\Schedule', $schedule1);
        $this->assertEquals(1, $schedule1->id);
        $this->assertEquals("2012-12-16T11:07:43.626Z", $schedule1->scheduled_date);

        $schedule2 = Schedule::create($schedules[1]);
        $this->assertEquals(2, $schedule2->id);
        $this->assertEquals("2012-12-17T11:08:00.000Z", $schedule2->scheduled_date);
    }

    public function testGetSchedule()
    {
        $response = self::$client->get('/');

        $schedule = Schedule::create($response->json());
        $this->assertInstanceOf('Ctct\Components\EmailMarketing\Schedule', $schedule);
        $this->assertEquals(1, $schedule->id);
        $this->assertEquals("2012-12-16T11:07:43.626Z", $schedule->scheduled_date);
    }

    public function testAddSchedule()
    {
        $response = self::$client->post('/');

        $createdSchedule = Schedule::create($response->json());
        $this->assertInstanceOf('Ctct\Components\EmailMarketing\Schedule', $createdSchedule);
        $this->assertEquals(1, $createdSchedule->id);
        $this->assertEquals("2012-12-16T11:07:43.626Z", $createdSchedule->scheduled_date);
    }

    public function testUpdateSchedule()
    {
        $response = self::$client->put('/');

        $updatedSchedule = Schedule::create($response->json());
        $this->assertInstanceOf('Ctct\Components\EmailMarketing\Schedule', $updatedSchedule);
        $this->assertEquals(1, $updatedSchedule->id);
        $this->assertEquals("2012-12-16T11:07:43.626Z", $updatedSchedule->scheduled_date);
    }

    public function testDeleteSchedule()
    {
        $response = self::$client->delete('/');

        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testDeleteScheduleFailed()
    {
        try {
            self::$client->delete('/');
            $this->fail("Call did not fail");
        } catch (ClientException $e) {
            $this->assertEquals(400, $e->getCode());
        }
    }

    public function testSendTest()
    {
        $response = self::$client->post('/');

        $testSend = TestSend::create($response->json());
        $this->assertInstanceOf('Ctct\Components\EmailMarketing\TestSend', $testSend);
        $this->assertEquals("HTML", $testSend->format);
        $this->assertEquals("oh hai there", $testSend->personal_message);
        $this->assertEquals("test@roving.com", $testSend->email_addresses[0]);
    }
}
