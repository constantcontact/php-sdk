<?php
namespace Ctct\Services;

use Ctct\Components\Activities\Activity;
use Ctct\Components\Activities\AddContacts;
use Ctct\Components\Activities\ExportContacts;
use Ctct\Exceptions\CtctException;
use Ctct\Util\Config;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7\Request;

/**
 * Performs all actions pertaining to scheduling Constant Contact Activities
 *
 * @package Services
 * @author ConstantContact
 */
class ActivityService extends BaseService {
    /**
     * Get an array of activities
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param array $params - associative array of query parameters and values to append to the request.
     *      Allowed parameters include:
     *      status - Status of the activity, must be one of UNCONFIRMED, PENDING, QUEUED, RUNNING, COMPLETE, ERROR
     *      type - Type of activity, must be one of ADD_CONTACTS, REMOVE_CONTACTS_FROM_LISTS, CLEAR_CONTACTS_FROM_LISTS,
     *             EXPORT_CONTACTS
     * @return array - Array of all ActivitySummaryReports
     * @throws CtctException
     */
    public function getActivities($accessToken, Array $params = array()) {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.activities');

        try {
            $response = parent::sendRequestWithoutBody($accessToken, 'GET', $baseUrl, $params);
        } catch (TransferException $e) {
            throw parent::convertException($e);
        }

        $activities = array();
        foreach (json_decode($response->getBody(), true) as $activity) {
            $activities[] = Activity::create($activity);
        }
        return $activities;
    }

    /**
     * Get an array of activities
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param string $activityId - Activity id
     * @return array - Array of all ActivitySummaryReports
     * @throws CtctException
     */
    public function getActivity($accessToken, $activityId) {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.activity'), $activityId);

        try {
            $response = parent::sendRequestWithoutBody($accessToken, 'GET', $baseUrl);
        } catch (TransferException $e) {
            throw parent::convertException($e);
        }

        return Activity::create(json_decode($response->getBody(), true));
    }

    /**
     * Create an Add Contacts Activity
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param AddContacts $addContacts
     * @return array - Array of all ActivitySummaryReports
     * @throws CtctException
     */
    public function createAddContactsActivity($accessToken, AddContacts $addContacts) {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.add_contacts_activity');

        try {
            $response = parent::sendRequestWithBody($accessToken, 'POST', $baseUrl, $addContacts);
        } catch (TransferException $e) {
            throw parent::convertException($e);
        }

        return Activity::create(json_decode($response->getBody(), true));
    }

    /**
     * Create an Add Contacts Activity from a file. Valid file types are txt, csv, xls, xlsx
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param string $fileName - The name of the file (ie: contacts.csv)
     * @param string $fileLocation - The location of the file on the server, this method uses fopen()
     * @param string $lists - Comma separated list of ContactList id's to add the contacts to
     * @return Activity
     * @throws CtctException
     */
    public function createAddContactsActivityFromFile($accessToken, $fileName, $fileLocation, $lists) {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.add_contacts_activity');
        $request = new Request('POST', $baseUrl, [
            parent::getHeadersForMultipart($accessToken)
        ]);

        try {
            $response = $this->getClient()->send($request, [
                'multipart' => [
                    [
                        'lists' => $lists,
                        'file_name' => $fileName,
                        'data' => fopen($fileLocation, 'r')
                    ]
                ]
            ]);
        } catch (TransferException $e) {
            throw parent::convertException($e);
        }

        return Activity::create(json_decode($response->getBody(), true));
    }

    /**
     * Create a clear lists activity. This removes all contacts on the selected lists while keeping
     * the list itself intact.
     * @param $accessToken - Constant Contact OAuth2 access token
     * @param array $lists - Array of list ID's to be cleared
     * @return Activity
     * @throws CtctException
     */
    public function addClearListsActivity($accessToken, Array $lists) {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.clear_lists_activity');

        try {
            $response = parent::sendRequestWithBody($accessToken, "POST", $baseUrl, array("lists" => $lists));
        } catch (TransferException $e) {
            throw parent::convertException($e);
        }

        return Activity::create(json_decode($response->getBody(), true));
    }

    /**
     * Create an Export Contacts Activity
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param ExportContacts $exportContacts
     * @return array - Array of all ActivitySummaryReports
     * @throws CtctException
     */
    public function addExportContactsActivity($accessToken, ExportContacts $exportContacts) {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.export_contacts_activity');

        try {
            $response = parent::sendRequestWithBody($accessToken, 'POST', $baseUrl, $exportContacts);
        } catch (TransferException $e) {
            throw parent::convertException($e);
        }

        return Activity::create(json_decode($response->getBody(), true));
    }

    /**
     * Create a Remove Contacts from Lists Activity
     * @param $accessToken - Constant Contact OAuth2 access token
     * @param array $emailAddresses - array of email addresses to remove
     * @param array $lists - array of list ID's to remove the provided email addresses from
     * @return Activity
     * @throws CtctException
     */
    public function addRemoveContactsFromListsActivity($accessToken, Array $emailAddresses, Array $lists) {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.remove_from_lists_activity');
        $payload = array(
            'import_data' => array(),
            'lists' => $lists
        );
        foreach ($emailAddresses as $emailAddress) {
            $payload['import_data'][] = array('email_addresses' => array($emailAddress));
        }

        try {
            $response = parent::sendRequestWithBody($accessToken, "POST", $baseUrl, $payload);
        } catch (TransferException $e) {
            throw parent::convertException($e);
        }

        return Activity::create(json_decode($response->getBody(), true));
    }

    /**
     * Create a Remove Contacts Activity from a file. Valid file types are txt, csv, xls, xlsx
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param string $fileName - The name of the file (ie: contacts.csv)
     * @param string $fileLocation - The location of the file on the server, this method uses fopen()
     * @param string $lists - Comma separated list of ContactList id's to add the contacts to
     * @return Activity
     * @throws CtctException
     */
    public function addRemoveContactsFromListsActivityFromFile($accessToken, $fileName, $fileLocation, $lists) {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.remove_from_lists_activity');
        $request = new Request('POST', $baseUrl, [
            parent::getHeadersForMultipart($accessToken)
        ]);

        try {
            $response = $this->getClient()->send($request, [
                'multipart' => [
                    [
                        'lists' => $lists,
                        'file_name' => $fileName,
                        'data' => fopen($fileLocation, 'r')
                    ]
                ]
            ]);;
        } catch (TransferException $e) {
            throw parent::convertException($e);
        }

        return Activity::create(json_decode($response->getBody(), true));
    }
}
