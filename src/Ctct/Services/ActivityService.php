<?php
namespace Ctct\Services;

use Ctct\Exceptions\CtctException;
use Ctct\Util\Config;
use Ctct\Components\Activities\Activity;
use Ctct\Components\Activities\AddContacts;
use Ctct\Components\Activities\ExportContacts;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Post\PostBody;
use GuzzleHttp\Post\PostFile;
use GuzzleHttp\Stream\Stream;

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
     * @param array $params - associative array of query parameters and values to append to the request.
     *      Allowed parameters include:
     *      status - Status of the activity, must be one of UNCONFIRMED, PENDING, QUEUED, RUNNING, COMPLETE, ERROR
     *      type - Type of activity, must be one of ADD_CONTACTS, REMOVE_CONTACTS_FROM_LISTS, CLEAR_CONTACTS_FROM_LISTS,
     *             EXPORT_CONTACTS
     * @return array - Array of all ActivitySummaryReports
     * @throws CtctException
     */
    public function getActivities($accessToken, Array $params = array())
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.activities');

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);
        if ($params) {
            $query = $request->getQuery();
            foreach ($params as $name => $value) {
                $query->add($name, $value);
            }
        }

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

        $activities = array();
        foreach ($response->json() as $activity) {
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
    public function getActivity($accessToken, $activityId)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.activity'), $activityId);

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

        return Activity::create($response->json());
    }

    /**
     * Create an Add Contacts Activity
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param AddContacts $addContacts
     * @return array - Array of all ActivitySummaryReports
     * @throws CtctException
     */
    public function createAddContactsActivity($accessToken, AddContacts $addContacts)
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.add_contacts_activity');

        $request = parent::createBaseRequest($accessToken, 'POST', $baseUrl);
        $stream = Stream::factory(json_encode($addContacts));
        $request->setBody($stream);

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

        return Activity::create($response->json());
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
    public function createAddContactsActivityFromFile($accessToken, $fileName, $fileLocation, $lists)
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.add_contacts_activity');
        $request = parent::createBaseRequest($accessToken, "POST", $baseUrl);
        $request->setHeader("Content-Type", "multipart/form-data");

        $body = new PostBody();
        $body->setField("lists", $lists);
        $body->setField("file_name", $fileName);
        $body->addFile(new PostFile("data", fopen($fileLocation, 'r'), $fileName));
        $request->setBody($body);

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

        return Activity::create($response->json());
    }

    /**
     * Create a clear lists activity. This removes all contacts on the selected lists while keeping
     * the list itself intact.
     * @param $accessToken - Constant Contact OAuth2 access token
     * @param array $lists - Array of list ID's to be cleared
     * @return Activity
     * @throws CtctException
     */
    public function addClearListsActivity($accessToken, Array $lists)
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.clear_lists_activity');
        $request = parent::createBaseRequest($accessToken, "POST", $baseUrl);
        $stream = Stream::factory(json_encode(array("lists" => $lists)));
        $request->setBody($stream);

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

        return Activity::create($response->json());
    }

    /**
     * Create an Export Contacts Activity
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param ExportContacts $exportContacts
     * @return array - Array of all ActivitySummaryReports
     * @throws CtctException
     */
    public function addExportContactsActivity($accessToken, ExportContacts $exportContacts)
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.export_contacts_activity');

        $request = parent::createBaseRequest($accessToken, 'POST', $baseUrl);
        $stream = Stream::factory(json_encode($exportContacts));
        $request->setBody($stream);

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

        return Activity::create($response->json());
    }

    /**
     * Create a Remove Contacts from Lists Activity
     * @param $accessToken - Constant Contact OAuth2 access token
     * @param array $emailAddresses - array of email addresses to remove
     * @param array $lists - array of list ID's to remove the provided email addresses from
     * @return Activity
     * @throws CtctException
     */
    public function addRemoveContactsFromListsActivity($accessToken, Array $emailAddresses, Array $lists)
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.remove_from_lists_activity');
        $request = parent::createBaseRequest($accessToken, "POST", $baseUrl);

        $payload = array(
            'import_data' => array(),
            'lists' => $lists
        );
        foreach($emailAddresses as $emailAddress) {
            $payload['import_data'][] = array('email_addresses' => array($emailAddress));
        }

        $stream = Stream::factory(json_encode($payload));
        $request->setBody($stream);

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

        return Activity::create($response->json());
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
    public function addRemoveContactsFromListsActivityFromFile($accessToken, $fileName, $fileLocation, $lists)
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.remove_from_lists_activity');
        $request = parent::createBaseRequest($accessToken, "POST", $baseUrl);
        $request->setHeader("Content-Type", "multipart/form-data");

        $body = new PostBody();
        $body->setField("lists", $lists);
        $body->setField("file_name", $fileName);
        $body->addFile(new PostFile("data", fopen($fileLocation, 'r'), $fileName));
        $request->setBody($body);

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

        return Activity::create($response->json());
    }
}
