<?php
namespace Ctct\Services;

use Ctct\Util\RestClient;
use Ctct\Util\Config;
use Ctct\Components\Campaigns\Schedule;
use Ctct\Components\Campaigns\TestSend;

/**
 * Performs all actions pertaining to scheduling Constant Contact Campaigns
 *
 * @package 	Services
 * @author 		Constant Contact
 */
class CampaignScheduleService extends BaseService{

	/**
	 * Create a new schedule for a campaign
	 * @param string $access_token - Constant Contact OAuth2 access token
	 * @param int $campaign_id - Campaign id to be scheduled
	 * @param Schedule $schedule - Schedule to be created
	 * @return Campaign
	 */
	 public static function addSchedule($access_token, $campaign_id, Schedule $schedule)
	 {
	 	$url = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign_schedules'), $campaign_id);
		$response = parent::getRestClient()->post($url, parent::getHeaders($access_token), $schedule->to_json());
		return Schedule::create(json_decode($response->body, true));
	 }
	 
	 /**
	 * Get a list of schedules for a campaign
	 * @param string $access_token - Constant Contact OAuth2 access token
	 * @param int $campaign_id - Campaign id to be scheduled
	 * @return array 
	 */
	 public static function getSchedules($access_token, $campaign_id)
	 {
	 	$url = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign_schedules'), $campaign_id);
		$response = parent::getRestClient()->get($url, parent::getHeaders($access_token));
		
		$schedules = array();
		
		foreach(json_decode($response->body, true) as $schedule)
		{
			$schedules[] = Schedule::create($schedule);
		}
		
		return $schedules;
	 }
	 
	 /**
	 * Get a specific schedule for a campaign
	 * @param string $access_token - Constant Contact OAuth2 access token
	 * @param int $campaign_id - Campaign id to be get a schedule for
	 * @param int $scheudle_id - Schedule id to retrieve 
	 * @return Schedule 
	 */
	 public static function getSchedule($access_token, $campaign_id, $schedule_id)
	 {
	 	$url = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign_schedule'), $campaign_id, $schedule_id);
		$response = parent::getRestClient()->get($url, parent::getHeaders($access_token));
		return Schedule::create(json_decode($response->body, true));
	 }
	 
	/**
	 * Update a specific schedule for a campaign
	 * @param string $access_token - Constant Contact OAuth2 access token
	 * @param int $campaign_id - Campaign id to be scheduled
	 * @param Schedule $schedule - Schedule to retrieve 
	 * @return Schedule 
	 */
	 public static function updateSchedule($access_token, $campaign_id, Schedule $schedule)
	 {
	 	$url = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign_schedule'), $campaign_id, $schedule->schedule_id);
		$response = parent::getRestClient()->put($url, parent::getHeaders($access_token), $schedule->to_json());
		return Schedule::create(json_decode($response->body, true));
	 }
	 
	 /**
	 * Get a specific schedule for a campaign
	 * @param string $access_token - Constant Contact OAuth2 access token
	 * @param int $campaign_id - Campaign id
	 * @param int $schedule_id - Schedule id to delete
	 * @return Schedule 
	 */
	 public static function deleteSchedule($access_token, $campaign_id, $schedule_id)
	 {
	 	$url = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign_schedule'), $campaign_id, $schedule_id);
		$response = parent::getRestClient()->delete($url, parent::getHeaders($access_token));
		return ($response->info['http_code'] == 204) ? true : false;
	 }

    /**
     * Send a test send of a campaign
     * @param string $access_token - Constant Contact OAuth2 access token
     * @param int $campaign_id - Id of campaign to send test of
     * @param TestSend $test_send - Test send details
     * @return TestSend
     */
    public static function sendTest($access_token, $campaign_id, TestSend $test_send)
    {
        $url = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign_test_sends'), $campaign_id);
        $response = parent::getRestClient()->post($url, parent::getHeaders($access_token), $test_send->to_json());
        return TestSend::create(json_decode($response->body, true));
    }
}
