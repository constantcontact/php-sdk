<?php
namespace Ctct\Services;

use Ctct\Util\Config;
use Ctct\Components\EmailMarketing\Schedule;
use Ctct\Components\EmailMarketing\TestSend;
use GuzzleHttp\Stream\Stream;

/**
 * Performs all actions pertaining to scheduling Constant Contact Campaigns
 *
 * @package Services
 * @author Constant Contact
 */
class CampaignScheduleService extends BaseService
{
    /**
     * Create a new schedule for a campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $campaignId - Campaign id to be scheduled
     * @param Schedule $schedule - Schedule to be created
     * @return Schedule
     */
    public function addSchedule($accessToken, $campaignId, Schedule $schedule)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign_schedules'), $campaignId);

        $request = parent::createBaseRequest($accessToken, 'POST', $baseUrl);
        $stream = Stream::factory(json_encode($schedule));
        $request->setBody($stream);
        $response = parent::getClient()->send($request);

        return Schedule::create($response->json());
    }

    /**
     * Get a list of schedules for a campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $campaignId - Campaign id to be scheduled
     * @return array
     */
    public function getSchedules($accessToken, $campaignId)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign_schedules'), $campaignId);

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);
        $response = parent::getClient()->send($request);

        $schedules = array();
        foreach ($response->json() as $schedule) {
            $schedules[] = Schedule::create($schedule);
        }
        return $schedules;
    }

    /**
     * Get a specific schedule for a campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $campaignId - Campaign id to be get a schedule for
     * @param int $scheduleId - Schedule id to retrieve
     * @return Schedule
     */
    public function getSchedule($accessToken, $campaignId, $scheduleId)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign_schedule'), $campaignId, $scheduleId);

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);
        $response = parent::getClient()->send($request);

        return Schedule::create($response->json());
    }

    /**
     * Update a specific schedule for a campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $campaignId - Campaign id to be scheduled
     * @param Schedule $schedule - Schedule to retrieve
     * @return Schedule
     */
    public function updateSchedule($accessToken, $campaignId, Schedule $schedule)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign_schedule'), $campaignId, $schedule->id);

        $request = parent::createBaseRequest($accessToken, 'PUT', $baseUrl);
        $stream = Stream::factory(json_encode($schedule));
        $request->setBody($stream);
        $response = parent::getClient()->send($request);

        return Schedule::create($response->json());
    }

    /**
     * Delete a specific schedule for a campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $campaignId - Campaign id
     * @param int $scheduleId - Schedule id to delete
     * @return True if successful
     */
    public function deleteSchedule($accessToken, $campaignId, $scheduleId)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign_schedule'), $campaignId, $scheduleId);

        $request = parent::createBaseRequest($accessToken, 'DELETE', $baseUrl);
        $response = parent::getClient()->send($request);
        return ($response->getStatusCode() == 204) ? true : false;
    }

    /**
     * Send a test send of a campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $campaignId - Id of campaign to send test of
     * @param TestSend $testSend - Test send details
     * @return TestSend
     */
    public function sendTest($accessToken, $campaignId, TestSend $testSend)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign_test_sends'), $campaignId);

        $request = parent::createBaseRequest($accessToken, 'POST', $baseUrl);
        $stream = Stream::factory(json_encode($testSend));
        $request->setBody($stream);
        $response = parent::getClient()->send($request);

        return TestSend::create($response->json());
    }
}
