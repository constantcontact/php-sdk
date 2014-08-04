<?php
namespace Ctct\Services;

use Ctct\Util\Config;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\ResultSet;

/**
 * Performs all actions pertaining to Constant Contact Contacts
 *
 * @package Services
 * @author ContactContact
 */
class ContactService extends BaseService
{

    /**
     * Get a ResultSet of contacts
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param array $params - array of query parameters to be appended to the url
     * @return ResultSet
     */
    public function getContacts($accessToken, array $params = array())
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
     * @param int $contactId - Unique contact id
     * @return Contact
     */
    public function getContact($accessToken, $contactId)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.contact'), $contactId);
        $url = $this->buildUrl($baseUrl);
        $response = parent::getRestClient()->get($url, parent::getHeaders($accessToken));
        return Contact::create(json_decode($response->body, true));
    }

    /**
     * Add a new contact to the Constant Contact account
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param Contact $contact - Contact to add
     * @param array $params - query params to be appended to the request
     * @return Contact
     */
    public function addContact($accessToken, Contact $contact, array $params = array())
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.contacts');
        $url = $this->buildUrl($baseUrl, $params);
        $response = parent::getRestClient()->post($url, parent::getHeaders($accessToken), $contact->toJson());
        return Contact::create(json_decode($response->body, true));
    }

    /**
     * Delete contact details for a specific contact
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $contactId - Unique contact id
     * @return boolean
     */
    public function deleteContact($accessToken, $contactId)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.contact'), $contactId);
        $url = $this->buildUrl($baseUrl);
        $response = parent::getRestClient()->delete($url, parent::getHeaders($accessToken));
        return ($response->info['http_code'] == 204) ? true : false;
    }

    /**
     * Delete a contact from all contact lists
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $contactId - Contact id to be removed from lists
     * @return boolean
     */
    public function deleteContactFromLists($accessToken, $contactId)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.contact_lists'), $contactId);
        $url = $this->buildUrl($baseUrl);
        $response = parent::getRestClient()->delete($url, parent::getHeaders($accessToken));
        return ($response->info['http_code'] == 204) ? true : false;
    }

    /**
     * Delete a contact from a specific contact list
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $contactId - Contact id to be removed
     * @param int $listId - ContactList to remove the contact from
     * @return boolean
     */
    public function deleteContactFromList($accessToken, $contactId, $listId)
    {
        $baseUrl = Config::get('endpoints.base_url') .
            sprintf(Config::get('endpoints.contact_list'), $contactId, $listId);
        $url = $this->buildUrl($baseUrl);
        $response = parent::getRestClient()->delete($url, parent::getHeaders($accessToken));
        return ($response->info['http_code'] == 204) ? true : false;
    }

    /**
     * Update contact details for a specific contact
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param Contact $contact - Contact to be updated
     * @param array $params - query params to be appended to the request
     * @return Contact
     */
    public function updateContact($accessToken, Contact $contact, array $params = array())
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.contact'), $contact->id);
        $url = $this->buildUrl($baseUrl, $params);
        $response = parent::getRestClient()->put($url, parent::getHeaders($accessToken), $contact->toJson());
        return Contact::create(json_decode($response->body, true));
    }
}
