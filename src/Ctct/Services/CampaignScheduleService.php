<?php
namespace Ctct\Services;

use Ctct\Util\RestClient;
use Ctct\Util\Config;
use Ctct\Components\EmailMarketing\Schedule;
use Ctct\Components\EmailMarketing\TestSend;

/**
 * Performs all actions pertaining to scheduling Constant Contact Campaigns
 *
 * @package     Services
 * @author         Constant Contact
 */
class CampaignScheduleService extends BaseService
{
    /**
     * Create a new schedule for a campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $campaignId - Campaign id to be scheduled
     * @param Schedule $schedule - Schedule to be created
     * @return Campaign
     */
    public function addSchedule($accessToken, $campaignId, Schedule $schedule)
    {
        $baseUrl = Config::get('endpoints.base_url') .
            sprintf(Config::get('endpoints.campaign_schedules'), $campaignId);
        $url = $this->buildUrl($baseUrl);
        $response = parent::getRestClient()->post($url, parent::getHeaders($accessToken), $schedule->toJson());
        return Schedule::create(json_decode($response->body, true));
    }
     
     /**
     * Get a list of schedules for a campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $campaignId - Campaign id to be scheduled
     * @return array 
     */
    public function getSchedules($accessToken, $campaignId)
    {
        $baseUrl = Config::get('endpoints.base_url') .
            sprintf(Config::get('endpoints.campaign_schedules'), $campaignId);
        $url = $this->buildUrl($baseUrl);
        $response = parent::getRestClient()->get($url, parent::getHeaders($accessToken));
        
        $schedules = array();
        
        foreach (json_decode($response->body, true) as $schedule) {
            $schedules[] = Schedule::create($schedule);
        }
        
        return $schedules;
    }
     
     /**
     * Get a specific schedule for a campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $campaignId - Campaign id to be get a schedule for
     * @param int $scheudle_id - Schedule id to retrieve 
     * @return Schedule 
     */
    public function getSchedule($accessToken, $campaignId, $scheduleId)
    {
        $baseUrl = Config::get('endpoints.base_url') .
            sprintf(Config::get('endpoints.campaign_schedule'), $campaignId, $scheduleId);
        $url = $this->buildUrl($baseUrl);
        $response = parent::getRestClient()->get($url, parent::getHeaders($accessToken));
        return Schedule::create(json_decode($response->body, true));
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
        $baseUrl = Config::get('endpoints.base_url') .
            sprintf(Config::get('endpoints.campaign_schedule'), $campaignId, $schedule->id);
        $url = $this->buildUrl($baseUrl);
        $response = parent::getRestClient()->put($url, parent::getHeaders($accessToken), $schedule->toJson());
        return Schedule::create(json_decode($response->body, true));
    }
     
     /**
     * Get a specific schedule for a campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $campaignId - Campaign id
     * @param int $scheduleId - Schedule id to delete
     * @return Schedule 
     */
    public function deleteSchedule($accessToken, $campaignId, $scheduleId)
    {
        $baseUrl = Config::get('endpoints.base_url') .
            sprintf(Config::get('endpoints.campaign_schedule'), $campaignId, $scheduleId);
        $url = $this->buildUrl($baseUrl);
        $response = parent::getRestClient()->delete($url, parent::getHeaders($accessToken));
        return ($response->info['http_code'] == 204) ? true : false;
    }

    /**
     * Send a test send of a campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $campaignId - Id of campaign to send test of
     * @param TestSend $test_send - Test send details
     * @return TestSend
     */
    public function sendTest($accessToken, $campaignId, TestSend $test_send)
    {
        $baseUrl = Config::get('endpoints.base_url') .
            sprintf(Config::get('endpoints.campaign_test_sends'), $campaignId);
        $url = $this->buildUrl($baseUrl);
        $response = parent::getRestClient()->post($url, parent::getHeaders($accessToken), $test_send->toJson());
        return TestSend::create(json_decode($response->body, true));
    }
}
