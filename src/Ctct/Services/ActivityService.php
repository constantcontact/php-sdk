<?php
namespace Ctct\Services;

use Ctct\Util\RestClient;
use Ctct\Util\Config;
use Ctct\Components\EmailCampaigns\Schedule;
use Ctct\Components\EmailCampaigns\TestSend;
use Ctct\Components\Activities\Activity;
use Ctct\Components\Activities\AddContacts;
use Ctct\Components\Activities\RemoveFromLists;
use Ctct\Components\Activities\ExportContacts;
use Ctct\Components\Activities\ClearLists;

/**
 * Performs all actions pertaining to scheduling Constant Contact Activities
 *
 * @package     Services
 * @author         Constant Contact
 */
class ActivityService extends BaseService
{

    /**
     * Get an array of activities
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @return array - Array of all ActivitySummaryReports
     */
    public function getActivities($accessToken)
    {
        $url = Config::get('endpoints.base_url') . Config::get('endpoints.activities');
        $response = parent::getRestClient()->get($url, parent::getHeaders($accessToken));
        $jsonResponse = json_decode($response->body, true);
        $activities = array();

        foreach ($jsonResponse as $activity) {
            $activities[] = Activity::create($activity);
        }
        return $activities;
    }

    /**
    * Get an array of activities
    * @param string $accessToken - Constant Contact OAuth2 access token
    * @param string $activityId - Activity id
    * @return array - Array of all ActivitySummaryReports
    */
    public function getActivity($accessToken, $activityId)
    {
        $url = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.activity'), $activityId);
        $response = parent::getRestClient()->get($url, parent::getHeaders($accessToken));
        return Activity::create(json_decode($response->body, true));
    }

    /**
    * Create an Add Contacts Activity
    * @param string $accessToken - Constant Contact OAuth2 access token
    * @param AddContacts $addContact
    * @return array - Array of all ActivitySummaryReports
    */
    public function createAddContactsActivity($accessToken, AddContacts $addContacts)
    {
        $url = Config::get('endpoints.base_url') . Config::get('endpoints.add_contacts_activity');
        $response = parent::getRestClient()->post($url, parent::getHeaders($accessToken));
        return Activity::create(json_decode($response->body, true));
    }

    /**
    * Create a Clear Lists Activity
    * @param string $accessToken - Constant Contact OAuth2 access token
    * @param array $clearLists - Array of list id's to be cleared
    * @return array - Array of all ActivitySummaryReports
    */
    public function addClearListsActivity($accessToken, Array $lists)
    {
        $url = Config::get('endpoints.base_url') . Config::get('endpoints.clear_lists_activity');
        $payload = array('lists' => $lists);
        $response = parent::getRestClient()->post($url, parent::getHeaders($accessToken), json_encode($payload));
        return Activity::create(json_decode($response->body, true));
    }

    /**
    * Create an Export Contacts Activity
    * @param string $accessToken - Constant Contact OAuth2 access token
    * @param ExportContacts $exportContacts
    * @return array - Array of all ActivitySummaryReports
    */
    public function addExportContactsActivity($accessToken, ExportContacts $exportContacts)
    {
        $url = Config::get('endpoints.base_url') . Config::get('endpoints.export_contacts_activity');
        $response = parent::getRestClient()->post($url, parent::getHeaders($accessToken), json_encode($exportContacts));
        return Activity::create(json_decode($response->body, true));
    }

    /**
    * Create a Remove Contacts From Lists Activity
    * @param string $accessToken - Constant Contact OAuth2 access token
    * @param RemoveFromLists $removeFromLists
    * @return array - Array of all ActivitySummaryReports
    */
    public function addRemoveContactsFromListsActivity($accessToken, Array $emailAddresses, Array $lists)
    {
        $url = Config::get('endpoints.base_url') . Config::get('endpoints.remove_from_lists_activity');
        $payload = array(
            'import_data'    => array(),
            'lists'             => $lists
        );

        foreach ($emailAddresses as $emailAddress) {
            $payload['import_data'][] = array('email_addresses' => array($emailAddress));
        }

        $response = parent::getRestClient()->post($url, parent::getHeaders($accessToken), json_encode($payload));
        return Activity::create(json_decode($response->body, true));
    }
}
