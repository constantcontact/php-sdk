<?php
namespace Ctct\Services;

use Ctct\Util\RestClient;
use Ctct\Util\Config;
use Ctct\Components\EmailMarketing\Schedule;
use Ctct\Components\EmailMarketing\TestSend;
use Ctct\Components\Activities\Activity;
use Ctct\Components\Activities\AddContacts;
use Ctct\Components\Activities\RemoveFromLists;
use Ctct\Components\Activities\ExportContacts;
use Ctct\Components\Activities\ClearLists;

/**
 * Performs all actions pertaining to scheduling Constant Contact Activities
 *
 * @package Services
 * @author ConstantContact
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
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.activities');
        $url = $this->buildUrl($baseUrl);
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
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.activity'), $activityId);
        $url = $this->buildUrl($baseUrl);
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
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.add_contacts_activity');
        $url = $this->buildUrl($baseUrl);
        $response = parent::getRestClient()->post($url, parent::getHeaders($accessToken), $addContacts->toJson());
        return Activity::create(json_decode($response->body, true));
    }

    /**
    * Create a Clear Lists Activity
    * @param string $accessToken - Constant Contact OAuth2 access token
    * @param array $clearLists - Array of list id's to be cleared
    * @return array - Array of all Activity
    */
    public function addClearListsActivity($accessToken, Array $lists)
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.clear_lists_activity');
        $url = $this->buildUrl($baseUrl);
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
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.export_contacts_activity');
        $url = $this->buildUrl($baseUrl);
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
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.remove_from_lists_activity');
        $url = $this->buildUrl($baseUrl);
        $payload = array(
            'import_data'    => array(),
            'lists'          => $lists
        );

        foreach ($emailAddresses as $emailAddress) {
            $payload['import_data'][] = array('email_addresses' => array($emailAddress));
        }

        $response = parent::getRestClient()->post($url, parent::getHeaders($accessToken), json_encode($payload));
        return Activity::create(json_decode($response->body, true));
    }
}
