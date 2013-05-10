<?php

use Ctct\Services\CampaignScheduleService;
use Ctct\Util\RestClient;
use Ctct\Util\CurlResponse;
use Ctct\Components\EmailMarketing\Schedule;
use Ctct\Components\EmailMarketing\TestSend;

class CampaignScheduleServiceUnitTest extends PHPUnit_Framework_TestCase
{

    private $restClient;
    private $campaignScheduleService;

    public function setUp()
    {
        $this->restClient = $this->getMock('Ctct\Util\RestClientInterface');
        $this->campaignScheduleService = new CampaignScheduleService("apikey", $this->restClient);
    }

    public function testGetSchedules()
    {
        $curlResponse = CurlResponse::create(JsonLoader::getCampaignSchedulesJson(), array('http_code' => 200));
        $this->restClient->expects($this->once())
            ->method('get')
            ->with()
            ->will($this->returnValue($curlResponse));

        $schedules = $this->campaignScheduleService->getSchedules('access_token', 9100367935463);
        $this->assertInstanceOf('Ctct\Components\EmailMarketing\Schedule', $schedules[0]);
        $this->assertEquals(1, $schedules[0]->id);
        $this->assertEquals("2012-12-16T11:07:43.626Z", $schedules[0]->scheduled_date);

        $this->assertEquals(2, $schedules[1]->id);
        $this->assertEquals("2012-12-17T11:08:00.000Z", $schedules[1]->scheduled_date);
    }

    public function testGetSchedule()
    {
        $curlResponse = CurlResponse::create(JsonLoader::getCampaignScheduleJson(), array('http_code' => 200));
        $this->restClient->expects($this->once())
            ->method('get')
            ->with()
            ->will($this->returnValue($curlResponse));

        $schedule = $this->campaignScheduleService->getSchedule('access_token', 9100367935463, 1);
        $this->assertInstanceOf('Ctct\Components\EmailMarketing\Schedule', $schedule);
        $this->assertEquals(1, $schedule->id);
        $this->assertEquals("2012-12-16T11:07:43.626Z", $schedule->scheduled_date);
    }

    public function testAddSchedule()
    {
        $curlResponse = CurlResponse::create(JsonLoader::getCampaignScheduleJson(), array('http_code' => 200));
        $this->restClient->expects($this->once())
            ->method('post')
            ->with()
            ->will($this->returnValue($curlResponse));

        $createdSchedule = $this->campaignScheduleService->addSchedule('access_token', 9100367935463, new Schedule());
        $this->assertInstanceOf('Ctct\Components\EmailMarketing\Schedule', $createdSchedule);
        $this->assertEquals(1, $createdSchedule->id);
        $this->assertEquals("2012-12-16T11:07:43.626Z", $createdSchedule->scheduled_date);
    }

    public function testUpdateSchedule()
    {
        $curlResponse = CurlResponse::create(JsonLoader::getCampaignScheduleJson(), array('http_code' => 200));
        $this->restClient->expects($this->once())
            ->method('put')
            ->with()
            ->will($this->returnValue($curlResponse));

        $updatedSchedule = $this->campaignScheduleService->updateSchedule(
            'access_token',
            "9100367935463",
            new Schedule()
        );

        $this->assertInstanceOf('Ctct\Components\EmailMarketing\Schedule', $updatedSchedule);
        $this->assertEquals(1, $updatedSchedule->id);
        $this->assertEquals("2012-12-16T11:07:43.626Z", $updatedSchedule->scheduled_date);
    }

    public function testDeleteSchedule()
    {
        $curlResponse = CurlResponse::create("", array('http_code' => 204), null);
        $this->restClient->expects($this->once())
            ->method('delete')
            ->with()
            ->will($this->returnValue($curlResponse));

        $this->assertTrue($this->campaignScheduleService->deleteSchedule('access_token', "9100367935463", 1));
    }

    public function testDeleteScheduleFailed()
    {
        $curlResponse = CurlResponse::create("", array('http_code' => 400));
        $this->restClient->expects($this->once())
            ->method('delete')
            ->with()
            ->will($this->returnValue($curlResponse));

        $this->assertFalse($this->campaignScheduleService->deleteSchedule('access_token', "9100367935463", 1));
    }

    public function testSendTest()
    {
        $curlResponse = CurlResponse::create(JsonLoader::getTestSendJson(), array('http_code' => 201));
        $this->restClient->expects($this->once())
            ->method('post')
            ->with()
            ->will($this->returnValue($curlResponse));

        $testSend = $this->campaignScheduleService->sendTest('access_token', "9100367935463", new TestSend());
        $this->assertInstanceOf('Ctct\Components\EmailMarketing\TestSend', $testSend);
        $this->assertEquals("HTML", $testSend->format);
        $this->assertEquals("oh hai there", $testSend->personal_message);
        $this->assertEquals("test@roving.com", $testSend->email_addresses[0]);
    }
}
