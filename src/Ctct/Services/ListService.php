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
    public static function getLists($accessToken)
    {
        $url = Config::get('endpoints.base_url') . Config::get('endpoints.lists');
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
    public static function addList($accessToken, ContactList $list)
    {
        $url = Config::get('endpoints.base_url') . Config::get('endpoints.lists');
        $response = parent::getRestClient()->post($url, parent::getHeaders($accessToken), $list->toJson());
        return ContactList::create(json_decode($response->body, true));
    }
    
    /**
     * Update a Contact List
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param ContactList $list - ContactList to be updated
     * @return ContactList
     */
    public static function updateList($accessToken, ContactList $list)
    {
        $url = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.list'), $list->id);
        $response = parent::getRestClient()->put($url, parent::getHeaders($accessToken), $list->toJson());
        return ContactList::create(json_decode($response->body, true));
    }

    /**
     * Get an individual contact list
     * @param $accessToken - Constant Contact OAuth2 access token
     * @param $list_id - list id
     * @return ContactList
     */
    public static function getList($accessToken, $list_id)
    {
        $url = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.list'), $list_id);
        $response = parent::getRestClient()->get($url, parent::getHeaders($accessToken));
        return ContactList::create(json_decode($response->body, true));
    }

    /**
     * Get all contacts from an individual list
     * @param $accessToken - Constant Contact OAuth2 access token
     * @param $list_id - list id to retrieve contacts for
     * @param $param - query param to attach to request
     * @return ResultSet
     */
    public static function getContactsFromList($accessToken, $list_id, $param = null)
    {
        $url = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.list_contacts'), $list_id);
        if ($param) {
            $url .= $param;
        }
        
        $response = parent::getRestClient()->get($url, parent::getHeaders($accessToken));
        $body = json_decode($response->body, true);
        $contacts = array();
        foreach ($body['results'] as $contact) {
            $contacts[] = Contact::create($contact);
        }
        return new ResultSet($contacts, $body['meta']);
    }
}
