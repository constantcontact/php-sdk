<?php
namespace Ctct\Services;

use Ctct\Util\Config;
use Ctct\Components\Activities\Activity;
use Ctct\Components\Activities\AddContacts;
use Ctct\Components\Activities\ExportContacts;

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
     * @param array $params - array of query parameters to be appended to the url
     * @return array - Array of all ActivitySummaryReports
     */
    public function getActivities($accessToken, array $params = array())
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.activities');
        $url = $this->buildUrl($baseUrl, $params);
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
     * @param AddContacts $addContacts
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
     * Create an Add Contacts Activity from a file. Valid file types are txt, csv, xls, xlsx
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param string $fileName - The name of the file (ie: contacts.csv)
     * @param string $contents - The contents of the file
     * @param string $lists - Comma separated list of ContactList id's to add the contacts to
     * @return \Ctct\Components\Activities\Activity
     */
    public function createAddContactsActivityFromFile($accessToken, $fileName, $contents, $lists)
    {
        $eol = "\r\n";
        $data = '';
        $boundary=md5(time());

        $data .= '--' . $boundary . $eol;
        $data .= 'Content-Disposition: form-data; name="file_name"' . $eol;
        $data .= 'Content-Type: text/plain' . $eol . $eol;
        $data .= $fileName . $eol;

        $data .= '--' . $boundary . $eol;
        $data .= 'Content-Disposition: form-data; name="lists"' . $eol;
        $data .= 'Content-Type: text/plain' . $eol . $eol;
        $data .= $lists . $eol;

        $data .= '--' . $boundary . $eol;
        $data .= 'Content-Disposition: form-data; name="data"' . $eol . $eol;
        $data .= $contents . $eol;
        $data .= "--" . $boundary . "--" . $eol;

        $headers = array(
            "Authorization: Bearer {$accessToken}",
            "Content-Type: multipart/form-data; boundary={$boundary}"
        );

        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.add_contacts_activity');
        $url = $this->buildUrl($baseUrl);

        $response = parent::getRestClient()->post($url, $headers, $data);
        return Activity::create(json_decode($response->body, true));
    }

    /**
     * Create a Clear Lists Activity
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param array $lists - Array of list id's to be cleared
     * @return array - Array of all Activity
     */
    public function addClearListsActivity($accessToken, array $lists)
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
     * @param array $emailAddresses - array of email addresses to remove
     * @param array $lists - array of lists to remove the provided email addresses from
     * @return array - Array of all ActivitySummaryReports
     */
    public function addRemoveContactsFromListsActivity($accessToken, array $emailAddresses, array $lists)
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.remove_from_lists_activity');
        $url = $this->buildUrl($baseUrl);
        $payload = array(
            'import_data' => array(),
            'lists' => $lists
        );

        foreach ($emailAddresses as $emailAddress) {
            $payload['import_data'][] = array('email_addresses' => array($emailAddress));
        }

        $response = parent::getRestClient()->post($url, parent::getHeaders($accessToken), json_encode($payload));
        return Activity::create(json_decode($response->body, true));
    }

    /**
     * Create an Remove Contacts Activity from a file. Valid file types are txt, csv, xls, xlsx
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param string $fileName - The name of the file (ie: contacts.csv)
     * @param string $contents - The contents of the file
     * @param string $lists - Comma separated list of ContactList id' to add the contacts too
     * @return \Ctct\Components\Activities\Activity
     */
    public function addRemoveContactsFromListsActivityFromFile($accessToken, $fileName, $contents, $lists)
    {
        $eol = "\r\n";
        $data = '';
        $boundary=md5(time());

        $data .= '--' . $boundary . $eol;
        $data .= 'Content-Disposition: form-data; name="file_name"' . $eol;
        $data .= 'Content-Type: text/plain' . $eol . $eol;
        $data .= $fileName . $eol;

        $data .= '--' . $boundary . $eol;
        $data .= 'Content-Disposition: form-data; name="lists"' . $eol;
        $data .= 'Content-Type: text/plain' . $eol . $eol;
        $data .= $lists . $eol;

        $data .= '--' . $boundary . $eol;
        $data .= 'Content-Disposition: form-data; name="data"' . $eol . $eol;
        $data .= $contents . $eol;
        $data .= "--" . $boundary . "--" . $eol;

        $headers = array(
            "Authorization: Bearer {$accessToken}",
            "Content-Type: multipart/form-data; boundary={$boundary}"
        );

        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.remove_from_lists_activity');
        $url = $this->buildUrl($baseUrl);

        $response = parent::getRestClient()->post($url, $headers, $data);
        return Activity::create(json_decode($response->body, true));
    }
}
