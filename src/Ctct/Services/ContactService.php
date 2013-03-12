<?php
namespace Ctct\Services;

use Ctct\Util\Config;
use Ctct\Util\RestClient;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\ResultSet;

/**
 * Performs all actions pertaining to Constant Contact Contacts
 *
 * @package     Services
 * @author         Constant Contact
 */
class ContactService extends BaseService
{

    /**
     * Get a ResultSet of contacts
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param array $params - array of query parameters to be appended to the url
     * @return ResultSet
     */
    public function getContacts($accessToken, Array $params = null)
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.contacts');
        $url = $this->buildUrl($baseUrl, $params);

        $response = parent::getRestClient()->get($url, parent::getHeaders($accessToken));
        $body = json_decode($response->body, true);
        $contacts = array();
        foreach ($body['results'] as $contact) {
            $contacts[] = Contact::create($contact);
        }
        return new ResultSet($contacts, $body['meta']);
    }
    
    /**
     * Get contact details for a specific contact
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $contact_id - Unique contact id
     * @return Contact
     */
    public function getContact($accessToken, $contact_id)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.contact'), $contact_id);
        $url = $this->buildUrl($baseUrl);
        $response = parent::getRestClient()->get($url, parent::getHeaders($accessToken));
        return Contact::create(json_decode($response->body, true));
    }
    
    /**
     * Add a new contact to the Constant Contact account
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param Contact $contact - Contact to add
     * @param boolean $actionByVisitor - is the action being taken by the visitor
     * @return Contact
     */
    public function addContact($accessToken, Contact $contact, $actionByVisitor = false)
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.contacts');
        $params = array();

        if ($actionByVisitor == true) {
            $params['action_by'] = "ACTION_BY_VISITOR";
        }
        
        $url = $this->buildUrl($baseUrl, $params);
        $response = parent::getRestClient()->post($url, parent::getHeaders($accessToken), $contact->toJson());
        return Contact::create(json_decode($response->body, true));
    }
    
    /**
     * Delete contact details for a specific contact
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $contact_id - Unique contact id
     * @return boolean
     */
    public function deleteContact($accessToken, $contact_id)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.contact'), $contact_id);
        $url = $this->buildUrl($baseUrl);
        $response = parent::getRestClient()->delete($url, parent::getHeaders($accessToken));
        return ($response->info['http_code'] == 204) ? true : false;
    }
    
    /**
     * Delete a contact from all contact lists
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $contact_id - Contact id to be removed from lists
     * @return boolean
     */
    public function deleteContactFromLists($accessToken, $contact_id)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.contact_lists'), $contact_id);
        $url = $this->buildUrl($baseUrl);
        $response = parent::getRestClient()->delete($url, parent::getHeaders($accessToken));
        return ($response->info['http_code'] == 204) ? true : false;
    }
    
    /**
     * Delete a contact from a specific contact list
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $contact_id - Contact id to be removed
     * @param int $list_id - ContactList to remove the contact from
     * @return boolean
     */
    public function deleteContactFromList($accessToken, $contact_id, $list_id)
    {
        $baseUrl = Config::get('endpoints.base_url') .
            sprintf(Config::get('endpoints.contact_list'), $contact_id, $list_id);
        $url = $this->buildUrl($baseUrl);
        $response = parent::getRestClient()->delete($url, parent::getHeaders($accessToken));
        return ($response->info['http_code'] == 204) ? true : false;
    }
    
    /**
     * Update contact details for a specific contact
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param Contact $contact - Contact to be updated
     * @param boolean $actionByVisitor - is the action being taken by the visitor 
     * @return Contact
     */
    public function updateContact($accessToken, Contact $contact, $actionByVisitor = false)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.contact'), $contact->id);
        $params = array();
        if ($actionByVisitor == true) {
            $params['action_by'] = "ACTION_BY_VISITOR";
        }
        $url = $this->buildUrl($baseUrl, $params);
        $response = parent::getRestClient()->put($url, parent::getHeaders($accessToken), $contact->toJson());
        return Contact::create(json_decode($response->body, true));
    }
}
