<?php
namespace Ctct\Services;

use Ctct\Util\RestClient;
use Ctct\Util\Config;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\Contacts\Contact;

/**
 * Performs all actions pertaining to Constant Contact Lists
 * 
 * @package 	Services
 * @author 		Constant Contact
 */
class ListService extends BaseService{

    /**
     * Get lists within an account
     * @param $access_token - Constant Contact OAuth2 access token
     * @return Array - ContactLists
     */
	public static function getLists($access_token)
	{
		$url = Config::get('endpoints.base_url') . Config::get('endpoints.lists');
		$response = parent::getRestClient()->get($url, parent::getHeaders($access_token));
		
		$lists = array();
		foreach(json_decode($response->body, true) as $contact)
		{
			$lists[] = ContactList::create($contact);
		}		
		return $lists;
	}

    /**
	 * Create a new Contact List
     * @param string $access_token - Constant Contact OAuth2 access token
     * @param ContactList $list
     * @return ContactList
     */
    public static function addList($access_token, ContactList $list)
	{
		$url = Config::get('endpoints.base_url') . Config::get('endpoints.lists');
		$response = parent::getRestClient()->post($url, parent::getHeaders($access_token), $list->to_json());
		return ContactList::create(json_decode($response->body, true));
	}
	
	/**
	 * Update a Contact List
	 * @param string $access_token - Constant Contact OAuth2 access token
	 * @param ContactList $list - ContactList to be updated
	 * @return ContactList
	 */
	public static function updateList($access_token, ContactList $list)
	{
		$url = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.list'), $list->id);
		$response = parent::getRestClient()->put($url, parent::getHeaders($access_token), $list->to_json());
		return ContactList::create(json_decode($response->body, true));
	}

    /**
     * Get an individual contact list
     * @param $access_token - Constant Contact OAuth2 access token
     * @param $list_id - list id
     * @return ContactList
     */
    public static function getList($access_token, $list_id)
	{
		$url = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.list'), $list_id);
		$response = parent::getRestClient()->get($url, parent::getHeaders($access_token));
		return ContactList::create(json_decode($response->body, true));
	}

    /**
     * Get all contacts from an individual list
     * @param $access_token - Constant Contact OAuth2 access token
     * @param $list_id - list id to retrieve contacts for
     * @return array - array of Contact
     */
    public static function getContactsFromList($access_token, $list_id)
    {
        $url = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.list_contacts'), $list_id);
        $response = parent::getRestClient()->get($url, parent::getHeaders($access_token));

        $contacts = array();
        foreach(json_decode($response->body, true) as $contact)
        {
            $contacts[] = Contact::create($contact);
        }
        return $contacts;
    }
}
