<?php
namespace Ctct\Services;

use Ctct\Util\RestClient;
use Ctct\Util\Config;
use Ctct\Components\EmailCampaigns\Schedule;
use Ctct\Components\EmailCampaigns\TestSend;

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
     * @param int $campaign_id - Campaign id to be scheduled
     * @param Schedule $schedule - Schedule to be created
     * @return Campaign
     */
    public static function addSchedule($accessToken, $campaign_id, Schedule $schedule)
    {
        $url = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign_schedules'), $campaign_id);
        $response = parent::getRestClient()->post($url, parent::getHeaders($accessToken), $schedule->toJson());
        return Schedule::create(json_decode($response->body, true));
    }
     
     /**
     * Get a list of schedules for a campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $campaign_id - Campaign id to be scheduled
     * @return array 
     */
    public static function getSchedules($accessToken, $campaign_id)
    {
        $url = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign_schedules'), $campaign_id);
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
     * @param int $campaign_id - Campaign id to be get a schedule for
     * @param int $scheudle_id - Schedule id to retrieve 
     * @return Schedule 
     */
    public static function getSchedule($accessToken, $campaign_id, $schedule_id)
    {
         $url = Config::get('endpoints.base_url') .
            sprintf(Config::get('endpoints.campaign_schedule'), $campaign_id, $schedule_id);
        $response = parent::getRestClient()->get($url, parent::getHeaders($accessToken));
        return Schedule::create(json_decode($response->body, true));
    }
     
    /**
     * Update a specific schedule for a campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $campaign_id - Campaign id to be scheduled
     * @param Schedule $schedule - Schedule to retrieve 
     * @return Schedule 
     */
    public static function updateSchedule($accessToken, $campaign_id, Schedule $schedule)
    {
         $url = Config::get('endpoints.base_url') .
            sprintf(Config::get('endpoints.campaign_schedule'), $campaign_id, $schedule->schedule_id);
        $response = parent::getRestClient()->put($url, parent::getHeaders($accessToken), $schedule->toJson());
        return Schedule::create(json_decode($response->body, true));
    }
     
     /**
     * Get a specific schedule for a campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $campaign_id - Campaign id
     * @param int $schedule_id - Schedule id to delete
     * @return Schedule 
     */
    public static function deleteSchedule($accessToken, $campaign_id, $schedule_id)
    {
        $url = Config::get('endpoints.base_url') .
            sprintf(Config::get('endpoints.campaign_schedule'), $campaign_id, $schedule_id);
        $response = parent::getRestClient()->delete($url, parent::getHeaders($accessToken));
        return ($response->info['http_code'] == 204) ? true : false;
    }

    /**
     * Send a test send of a campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $campaign_id - Id of campaign to send test of
     * @param TestSend $test_send - Test send details
     * @return TestSend
     */
    public static function sendTest($accessToken, $campaign_id, TestSend $test_send)
    {
        $url = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign_test_sends'), $campaign_id);
        $response = parent::getRestClient()->post($url, parent::getHeaders($accessToken), $test_send->toJson());
        return TestSend::create(json_decode($response->body, true));
    }
}
