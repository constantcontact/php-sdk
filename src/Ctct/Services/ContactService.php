<?php
namespace Ctct\Services;

use Ctct\Util\Config;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\ResultSet;
use GuzzleHttp\Stream\Stream;

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
    public function getContacts($accessToken, Array $params)
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.contacts');

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

    /**
     * Get contact details for a specific contact
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $contactId - Unique contact id
     * @return Contact
     */
    public function getContact($accessToken, $contactId)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.contact'), $contactId);

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);
        $response = parent::getClient()->send($request);

        return Contact::create($response->json());
    }

    /**
     * Add a new contact to the Constant Contact account
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param Contact $contact - Contact to add
     * @param array $params - query params to be appended to the request
     * @return Contact
     */
    public function addContact($accessToken, Contact $contact, Array $params)
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.contacts');

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);
        if ($params) {
            $query = $request->getQuery();
            foreach ($params as $name => $value) {
                $query->add($name, $value);
            }
        }
        $stream = Stream::factory(json_encode($contact));
        $request->setBody($stream);
        $response = parent::getClient()->send($request);

        return Contact::create($response->json());
    }

    /**
     * Opt out a contact
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $contactId - Unique contact id
     * @return boolean
     */
    public function unsubscribeContact($accessToken, $contactId) {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.contact'), $contactId);

        $request = parent::createBaseRequest($accessToken, 'DELETE', $baseUrl);
        $response = parent::getClient()->send($request);

        return ($response->getStatusCode() == 204) ? true : false;
    }

    /**
     * Update contact details for a specific contact
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param Contact $contact - Contact to be updated
     * @param array $params - query params to be appended to the request
     * @return Contact
     */
    public function updateContact($accessToken, Contact $contact, Array $params)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.contact'), $contact->id);

        $request = parent::createBaseRequest($accessToken, 'PUT', $baseUrl);
        if ($params) {
            $query = $request->getQuery();
            foreach ($params as $name => $value) {
                $query->add($name, $value);
            }
        }
        $response = parent::getClient()->send($request);

        return Contact::create($response->json());
    }
}
