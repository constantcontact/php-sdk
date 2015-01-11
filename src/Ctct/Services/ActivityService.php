<?php
namespace Ctct\Services;

use Ctct\Util\Config;
use Ctct\Components\Activities\Activity;
use Ctct\Components\Activities\AddContacts;
use Ctct\Components\Activities\ExportContacts;
use GuzzleHttp\Client;
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
     * @param array $params - array of query parameters to be appended to the url
     * @return array - Array of all ActivitySummaryReports
     */
    public function getActivities($accessToken, Array $params)
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.activities');

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);
        if ($params) {
            $query = $request->getQuery();
            foreach ($params as $name => $value) {
                $query->add($name, $value);
            }
        }
        $response = parent::getClient()->send($request);

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
     */
    public function getActivity($accessToken, $activityId)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.activity'), $activityId);

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);
        $response = parent::getClient()->send($request);

        return Activity::create($response->json());
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

        $request = parent::createBaseRequest($accessToken, 'POST', $baseUrl);
        $stream = Stream::factory(json_encode($addContacts));
        $request->setBody($stream);
        $response = parent::getClient()->send($request);

        return Activity::create($response->json());
    }

    /**
     * Create an Add Contacts Activity from a file. Valid file types are txt, csv, xls, xlsx
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param string $fileName - The name of the file (ie: contacts.csv)
     * @param string $fileLocation - The location of the file on the server, this method uses fopen()
     * @param string $lists - Comma separated list of ContactList id's to add the contacts to
     * @return Activity
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

        $response = parent::getClient()->send($request);
        return Activity::create($response->json());
    }

    /**
     * Create a clear lists activity. This removes all contacts on the selected lists while keeping
     * the list itself intact.
     * @param $accessToken - Constant Cotnact OAuth2 access token
     * @param array $lists - Array of list ID's to be cleared
     * @return Activity
     */
    public function addClearListsActivity($accessToken, Array $lists)
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.clear_lists_activity');
        $request = parent::createBaseRequest($accessToken, "POST", $baseUrl);
        $stream = Stream::factory(json_encode(array("lists" => $lists)));
        $request->setBody($stream);
        $response = parent::getClient()->send($request);
        return Activity::create($response->json());
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

        $request = parent::createBaseRequest($accessToken, 'POST', $baseUrl);
        $stream = Stream::factory(json_encode($exportContacts));
        $request->setBody($stream);
        $response = parent::getClient()->send($request);

        return Activity::create($response->json());
    }

    /**
     * Create a Remove Contacts from Lists Activity
     * @param $accessToken - Constant Contact OAuth2 access token
     * @param array $emailAddresses - array of email addresses to remove
     * @param array $lists - array of list ID's to remove the provided email addresses from
     * @return Activity
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
        $response = parent::getClient()->send($request);
        return Activity::create($response->json());
    }

    /**
     * Create a Remove Contacts Activity from a file. Valid file types are txt, csv, xls, xlsx
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param string $fileName - The name of the file (ie: contacts.csv)
     * @param string $fileLocation - The location of the file on the server, this method uses fopen()
     * @param string $lists - Comma separated list of ContactList id's to add the contacts to
     * @return Activity
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

        $response = parent::getClient()->send($request);
        return Activity::create($response->json());
    }
}
