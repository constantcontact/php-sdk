<?php
namespace Ctct\Services;

use Ctct\Util\RestClient;
use Ctct\Util\Config;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\ResultSet;

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
     * @return Array - ContactLists
     */
    public function getLists($accessToken)
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.lists');
        $url = $this->buildUrl($baseUrl);
        $response = parent::getRestClient()->get($url, parent::getHeaders($accessToken));
        
        $lists = array();
        foreach (json_decode($response->body, true) as $contact) {
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
        $url = $this->buildUrl($baseUrl);
        $response = parent::getRestClient()->post($url, parent::getHeaders($accessToken), $list->toJson());
        return ContactList::create(json_decode($response->body, true));
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
        $url = $this->buildUrl($baseUrl);
        $response = parent::getRestClient()->put($url, parent::getHeaders($accessToken), $list->toJson());
        return ContactList::create(json_decode($response->body, true));
    }

    /**
     * Get an individual contact list
     * @param $accessToken - Constant Contact OAuth2 access token
     * @param $list_id - list id
     * @return ContactList
     */
    public function getList($accessToken, $list_id)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.list'), $list_id);
        $url = $this->buildUrl($baseUrl);
        $response = parent::getRestClient()->get($url, parent::getHeaders($accessToken));
        return ContactList::create(json_decode($response->body, true));
    }

    /**
     * Get all contacts from an individual list
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param string $list_id - list id to retrieve contacts for
     * @param array $params - query params to attach to request
     * @return ResultSet
     */
    public function getContactsFromList($accessToken, $list_id, $params = null)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.list_contacts'), $list_id);
        $url = $this->buildUrl($baseUrl, $params);
        
        $response = parent::getRestClient()->get($url, parent::getHeaders($accessToken));
        $body = json_decode($response->body, true);
        $contacts = array();
        foreach ($body['results'] as $contact) {
            $contacts[] = Contact::create($contact);
        }
        return new ResultSet($contacts, $body['meta']);
    }
}
