<?php
namespace Ctct\Services;

use Ctct\Exceptions\CtctException;
use Ctct\Util\Config;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\ResultSet;
use GuzzleHttp\Exception\ClientException;
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
     * @param array $params - associative array of query parameters and values to append to the request.
     *      Allowed parameters include:
     *      limit - Specifies the number of results displayed per page of output, from 1 - 500, default = 50.
     *      modified_since - ISO-8601 formatted timestamp.
     *      next - the next link returned from a previous paginated call. May only be used by itself.
     *      email - full email address string to restrict results by
     *      status - a contact status to filter results by. Must be one of ACTIVE, OPTOUT, REMOVED, UNCONFIRMED.
     * @return ResultSet
     * @throws CtctException
     */
    public function getContacts($accessToken, Array $params = array())
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.contacts');

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

        $body = $response->json();
        $contacts = array();
        foreach ($body['results'] as $contact) {
            $contacts[] = Contact::create($contact);
        }
        return new ResultSet($contacts, $body['meta']);
    }

    /**
     * Get all contacts from an individual list
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param string $listId - {@link ContactList} id to retrieve contacts for
     * @param array $params - associative array of query parameters and values to append to the request.
     *      Allowed parameters include:
     *      limit - Specifies the number of results displayed per page of output, from 1 - 500, default = 50.
     *      modified_since - ISO-8601 formatted timestamp.
     *      next - the next link returned from a previous paginated call. May only be used by itself.
     *      email - full email address string to restrict results by
     *      status - a contact status to filter results by. Must be one of ACTIVE, OPTOUT, REMOVED, UNCONFIRMED.
     * @return ResultSet
     * @throws CtctException
     */
    public function getContactsFromList($accessToken, $listId, Array $params = array())
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.list_contacts'), $listId);

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
     * @throws CtctException
     */
    public function getContact($accessToken, $contactId)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.contact'), $contactId);

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

        return Contact::create($response->json());
    }

    /**
     * Add a new contact to the Constant Contact account
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param Contact $contact - Contact to add
     * @param array $params - associative array of query parameters and values to append to the request.
     *      Allowed parameters include:
     *      action_by - Whether the contact is taking the action, or the account owner. Must be one of
     *                  ACTION_BY_OWNER or ACTION_BY_VISITOR
     * @return Contact
     * @throws CtctException
     */
    public function addContact($accessToken, Contact $contact, Array $params = array())
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.contacts');

        $request = parent::createBaseRequest($accessToken, 'POST', $baseUrl);
        if ($params) {
            $query = $request->getQuery();
            foreach ($params as $name => $value) {
                $query->add($name, $value);
            }
        }
        $stream = Stream::factory(json_encode($contact));
        $request->setBody($stream);

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

        return Contact::create($response->json());
    }

    /**
     * Opt out a contact
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $contactId - Unique contact id
     * @return boolean
     * @throws CtctException
     */
    public function unsubscribeContact($accessToken, $contactId) {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.contact'), $contactId);

        $request = parent::createBaseRequest($accessToken, 'DELETE', $baseUrl);

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

        return ($response->getStatusCode() == 204) ? true : false;
    }

    /**
     * Update contact details for a specific contact
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param Contact $contact - Contact to be updated
     * @param array $params - associative array of query parameters and values to append to the request.
     *      Allowed parameters include:
     *      action_by - Whether the contact is taking the action, or the account owner. Must be one of
     *                  ACTION_BY_OWNER or ACTION_BY_VISITOR
     * @return Contact
     * @throws CtctException
     */
    public function updateContact($accessToken, Contact $contact, Array $params = array())
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.contact'), $contact->id);

        $request = parent::createBaseRequest($accessToken, 'PUT', $baseUrl);
        if ($params) {
            $query = $request->getQuery();
            foreach ($params as $name => $value) {
                $query->add($name, $value);
            }
        }
        $stream = Stream::factory(json_encode($contact));
        $request->setBody($stream);

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

        return Contact::create($response->json());
    }
}
