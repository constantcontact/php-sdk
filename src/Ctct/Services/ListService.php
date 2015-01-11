<?php
namespace Ctct\Services;

use Ctct\Util\Config;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\ResultSet;
use GuzzleHttp\Stream\Stream;

/**
 * Performs all actions pertaining to Constant Contact Lists
 *
 * @package     Services
 * @author         Constant Contact
 */
class ListService extends BaseService
{
    /**
     * Get lists within an account
     * @param $accessToken - Constant Contact OAuth2 access token
     * @param array $params - array of query parameters to be appended to the request
     * @return Array - ContactLists
     */
    public function getLists($accessToken, Array $params)
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.lists');

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);
        if ($params) {
            $query = $request->getQuery();
            foreach ($params as $name => $value) {
                $query->add($name, $value);
            }
        }
        $response = parent::getClient()->send($request);

        $lists = array();
        foreach ($response->json() as $contact) {
            $lists[] = ContactList::create($contact);
        }

        return $lists;
    }

    /**
     * Create a new Contact List
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param ContactList $list
     * @return ContactList
     */
    public function addList($accessToken, ContactList $list)
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.lists');

        $request = parent::createBaseRequest($accessToken, 'POST', $baseUrl);
        $stream = Stream::factory(json_encode($list));
        $request->setBody($stream);
        $response = parent::getClient()->send($request);

        return ContactList::create($response->json());
    }

    /**
     * Update a Contact List
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param ContactList $list - ContactList to be updated
     * @return ContactList
     */
    public function updateList($accessToken, ContactList $list)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.list'), $list->id);

        $request = parent::createBaseRequest($accessToken, 'PUT', $baseUrl);
        $stream = Stream::factory(json_encode($list));
        $request->setBody($stream);
        $response = parent::getClient()->send($request);

        return ContactList::create($response->json());
    }

    /**
     * Delete a Contact List
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param $listId - list id
     * @return ContactList
     */
    public function deleteList($accessToken, $listId)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.list'), $listId);

        $request = parent::createBaseRequest($accessToken, 'DELETE', $baseUrl);
        $response = parent::getClient()->send($request);

        return ($response->getStatusCode() == 204) ? true : false;
    }

    /**
     * Get an individual contact list
     * @param $accessToken - Constant Contact OAuth2 access token
     * @param $listId - list id
     * @return ContactList
     */
    public function getList($accessToken, $listId)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.list'), $listId);

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);
        $response = parent::getClient()->send($request);

        return ContactList::create($response->json());
    }

    /**
     * Get all contacts from an individual list
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param string $listId - list id to retrieve contacts for
     * @param array $params - query params to attach to request
     * @return ResultSet
     */
    public function getContactsFromList($accessToken, $listId, Array $params)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.list_contacts'), $listId);

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);
        if ($params) {
            $query = $request->getQuery();
            foreach ($params as $name => $value) {
                $query->add($name, $value);
            }
        }
        $response = parent::getClient()->send($request);

        $body = $response->json();
        $contacts = array();
        foreach ($body['results'] as $contact) {
            $contacts[] = Contact::create($contact);
        }
        return new ResultSet($contacts, $body['meta']);
    }
}
