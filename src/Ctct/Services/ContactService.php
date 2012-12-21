<?php
namespace Ctct\Services;

use Ctct\Util\Config;
use Ctct\Util\RestClient;
use Ctct\Components\Contacts\Contact;

/**
 * Performs all actions pertaining to Constant Contact Contacts
 *
 * @package 	Services
 * @author 		Constant Contact
 */
class ContactService extends BaseService{
	
	
	/**
	 * Get an array of contacts
	 * @param string $access_token - Constant Contact OAuth2 access token
     * @param int $offset - denotes the starting number for the result set
     * @param int $limit - denotes the number of results per set
	 * @return array
	 */
	public static function getContacts($access_token, $offset = null, $limit = null)
	{
		$url = parent::paginateUrl(
            Config::get('endpoints.base_url') . Config::get('endpoints.contacts'), $offset, $limit
        );

		$response = parent::getRestClient()->get($url, parent::getHeaders($access_token));

		$contacts = array();
		foreach (json_decode($response->body, true) as $contact) {
			$contacts[] = Contact::create($contact);
		}		
		return $contacts;
	}
	
	/**
	 * Get contact details for a specific contact
	 * @param string $access_token - Constant Contact OAuth2 access token
	 * @param int $contact_id - Unique contact id
	 * @return Contact
	 */
	public static function getContact($access_token, $contact_id)
	{
		$url = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.contact'), $contact_id);
		$response = parent::getRestClient()->get($url, parent::getHeaders($access_token));
		return Contact::create(json_decode($response->body, true));
	}
	
	/**
	 * Get contacts with a specified email eaddress
	 * @param string $access_token - Constant Contact OAuth2 access token
	 * @param string $email - contact email address to search for
	 * @return array
	 */
	public static function getContactByEmail($access_token, $email)
	{
		$url = Config::get('endpoints.base_url') . Config::get('endpoints.contacts') . '?email=' . $email;
		$response = parent::getRestClient()->get($url, parent::getHeaders($access_token));
		
		$contacts = array();
		foreach (json_decode($response->body, true) as $contact) {
			$contacts[] = Contact::create($contact);
		}

		return $contacts; 
	}
	
	/**
	 * Add a new contact to the Constant Contact account
	 * @param string $access_token - Constant Contact OAuth2 access token
	 * @param Contact $contact - Contact to add
	 * @return Contact
	 */
	public static function addContact($access_token, Contact $contact)
	{
		$url = Config::get('endpoints.base_url') . Config::get('endpoints.contacts');
		$response = parent::getRestClient()->post($url, parent::getHeaders($access_token), $contact->to_json());
		return Contact::create(json_decode($response->body, true));
	}
	
	/**
	 * Delete contact details for a specific contact
	 * @param string $access_token - Constant Contact OAuth2 access token
	 * @param int $contact_id - Unique contact id
	 * @return boolean
	 */
	public static function deleteContact($access_token, $contact_id)
	{
		$url = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.contact'), $contact_id);
		$response = parent::getRestClient()->delete($url, parent::getHeaders($access_token));
		return ($response->info['http_code'] == 204) ? true : false;
	}
	
	/**
	 * Delete a contact from all contact lists
	 * @param string $access_token - Constant Contact OAuth2 access token
	 * @param int $contact_id - Contact id to be removed from lists
	 * @return boolean
	 */
	public static function deleteContactFromLists($access_token, $contact_id)
	{
		$url = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.contact_lists'), $contact_id);
		$response = parent::getRestClient()->delete($url, parent::getHeaders($access_token));
		return ($response->info['http_code'] == 204) ? true : false;
	}
	
	/**
	 * Delete a contact from a specific contact list
	 * @param string $access_token - Constant Contact OAuth2 access token
	 * @param int $contact_id - Contact id to be removed
	 * @param int $list_id - ContactList to remove the contact from
	 * @return boolean
	 */
	public static function deleteContactFromList($access_token, $contact_id, $list_id)
	{
		$url = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.contact_list'), $contact_id, $list_id);
		$response = parent::getRestClient()->delete($url, parent::getHeaders($access_token));
		return ($response->info['http_code'] == 204) ? true : false;
	}
	
	/**
	 * Update contact details for a specific contact
	 * @param string $access_token - Constant Contact OAuth2 access token
	 * @param Contact $contact - Contact to be updated
	 * @return Contact
	 */
	public static function updateContact($access_token, Contact $contact)
	{
		$url = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.contact'), $contact->id);
		$response = parent::getRestClient()->put($url, parent::getHeaders($access_token), $contact->to_json());
		return Contact::create(json_decode($response->body, true));
	}
}
