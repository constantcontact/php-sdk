<?php

use Ctct\Services\CampaignScheduleService;
use Ctct\Util\RestClient;
use Ctct\Components\EmailCampaigns\Schedule;
use Ctct\Components\EmailCampaigns\TestSend;

class CampaignScheduleServiceUnitTest extends PHPUnit_Framework_TestCase{
	
	public function testGetSchedules()
	{
        $rest_client = new MockRestClient(200, JsonLoader::getCampaignSchedulesJson());

        $campaignSchedule_service = new CampaignScheduleService("apikey", $rest_client);
        $schedules = $campaignSchedule_service->getSchedules('access_token', 9100367935463);

		$this->assertEquals(1, $schedules[0]->schedule_id);
        $this->assertEquals("2012-12-16T11:07:43.626Z", $schedules[0]->scheduled_date);

        $this->assertEquals(2, $schedules[1]->schedule_id);
        $this->assertEquals("2012-12-17T11:08:00.000Z", $schedules[1]->scheduled_date);
	}

    public function testGetSchedule()
    {
        $rest_client = new MockRestClient(200, JsonLoader::getCampaignScheduleJson());

        $campaignSchedule_service = new CampaignScheduleService("apikey", $rest_client);
        $schedule = $campaignSchedule_service->getSchedule('access_token', 9100367935463, 1);

        $this->assertEquals(1, $schedule->schedule_id);
        $this->assertEquals("2012-12-16T11:07:43.626Z", $schedule->scheduled_date);
    }

    public function testAddSchedule()
    {
        $rest_client = new MockRestClient(201, JsonLoader::getCampaignScheduleJson());

        $campaignSchedule_service = new CampaignScheduleService("apikey", $rest_client);
        $createdSchedule = $campaignSchedule_service->addSchedule('access_token', 9100367935463, new Schedule());

        $this->assertEquals(1, $createdSchedule->schedule_id);
        $this->assertEquals("2012-12-16T11:07:43.626Z", $createdSchedule->scheduled_date);
    }

    public function testUpdateSchedule()
    {
        $rest_client = new MockRestClient(200, JsonLoader::getCampaignScheduleJson());

        $campaignSchedule_service = new CampaignScheduleService("apikey", $rest_client);
        $updatedSchedule = $campaignSchedule_service->updateSchedule('access_token', "9100367935463", new Schedule());

        $this->assertEquals(1, $updatedSchedule->schedule_id);
        $this->assertEquals("2012-12-16T11:07:43.626Z", $updatedSchedule->scheduled_date);
    }

    public function testDeleteSchedule()
    {
        $rest_client = new MockRestClient(204, "");

        $campaignSchedule_service = new CampaignScheduleService("apikey", $rest_client);
        $response = $campaignSchedule_service->deleteSchedule('access_token', "9100367935463", 1);

        $this->assertTrue($response);
    }

    public function testDeleteSchedule_failed()
    {
        $rest_client = new MockRestClient(400, "");

        $campaignSchedule_service = new CampaignScheduleService("apikey", $rest_client);
        $response = $campaignSchedule_service->deleteSchedule('access_token', "9100367935463", 1);

        $this->assertFalse($response);
    }

    public function testSendTest()
    {
        $rest_client = new MockRestClient(201, JsonLoader::getTestSendJson());

        $campaignSchedule_service = new CampaignScheduleService("apikey", $rest_client);
        $test_send = $campaignSchedule_service->sendTest('access_token', "9100367935463", new TestSend());

        $this->assertEquals("HTML", $test_send->format);
        $this->assertEquals("oh hai there", $test_send->personal_message);
        $this->assertEquals("test@roving.com", $test_send->email_addresses[0]);
    }
	
	
}
