<?php
namespace Ctct\Services;

use Ctct\Components\EventSpot\Registrant\Promocode;
use Ctct\Components\EventSpot\Registrant\Registrant;
use Ctct\Services;
use Ctct\Components\ResultSet;
use Ctct\Components\EventSpot\EventSpot;
use Ctct\Components\EventSpot\EventFee;
use Ctct\Components\EventSpot\EventSpotList;
use Ctct\Exceptions\CtctException;
use Ctct\Util\Config;
use Ctct\Components\Contacts\ContactList;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;

/**
 * Performs all actions pertaining to Constant Contact EventSpot Events
 *
 * @package     Services
 * @author         Constant Contact
 */
class EventSpotService extends BaseService
{

	/**
	 * Get lists within an account
	 * @param $accessToken - Constant Contact OAuth2 access token
	 * @param array $params - associative array of query parameters and values to append to the request.
	 *      Allowed parameters include:
	 *      limit - Default: 50, up to 500
	 * @return EventSpot[]
	 * @throws CtctException
	 */
	public function getEvents($accessToken, Array $params = array())
	{
		$baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.events');

		$request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);
		if ($params) {
			$query = $request->getQuery();
			foreach ($params as $name => $value) {
				$query->add($name, $value);
			}
		}

		try {
			/** @var \GuzzleHttp\Message\Response $response */
			$response = parent::getClient()->send($request);
		} catch (ClientException $e) {
			throw parent::convertException($e);
		}

		$body = $response->json();

		$events = array();
		if( ! empty( $body['results'] ) ) {
			foreach ( $body['results'] as $event ) {
				$events[] = EventSpot::create( $event );
			}
		}

		return new ResultSet($events, $body['meta']);
	}

	/**
	 * Create a new Event
	 * @param string $accessToken - Constant Contact OAuth2 access token
	 * @param EventSpot $event
	 * @return EventSpot
	 * @throws CtctException
	 */
	public function addEvent($accessToken, EventSpot $event)
	{
		$baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.events');

		$request = parent::createBaseRequest($accessToken, 'POST', $baseUrl);
		$stream = Stream::factory(json_encode($event));
		$request->setBody($stream);

		try {
			$response = parent::getClient()->send($request);
		} catch (ClientException $e) {
			throw parent::convertException($e);
		}

		return EventSpot::create($response->json());
	}

	/**
	 * Update a Contact List
	 * @param string $accessToken - Constant Contact OAuth2 access token
	 * @param ContactList $event - ContactList to be updated
	 * @return ContactList
	 * @throws CtctException
	 */
	public function updateEvent($accessToken, EventSpot $event)
	{
		$baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.event'), $event->id);

		$request = parent::createBaseRequest($accessToken, 'PUT', $baseUrl);
		$stream = Stream::factory(json_encode($event));
		$request->setBody($stream);

		try {
			$response = parent::getClient()->send($request);
		} catch (ClientException $e) {
			throw parent::convertException($e);
		}

		return EventSpot::create($response->json());
	}

	/**
	 * Delete an Event
	 * @param string $accessToken - Constant Contact OAuth2 access token
	 * @param $eventId - event id
	 * @return boolean
	 * @throws CtctException
	 */
	public function deleteEvent($accessToken, $eventId)
	{
		$baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.event'), $eventId);

		$request = parent::createBaseRequest($accessToken, 'DELETE', $baseUrl);

		try {
			$response = parent::getClient()->send($request);
		} catch (ClientException $e) {
			throw parent::convertException($e);
		}

		return ($response->getStatusCode() == 204) ? true : false;
	}

	/**
	 * Get an individual Event
	 * @param $accessToken - Constant Contact OAuth2 access token
	 * @param $eventId - event id
	 * @return EventSpot
	 * @throws CtctException
	 */
	public function getEvent($accessToken, $eventId)
	{
		$baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.event'), $eventId);

		$request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);

		try {
			$response = parent::getClient()->send($request);
		} catch (ClientException $e) {
			throw parent::convertException($e);
		}

		$event_array = $response->json();
		$event_array['id'] = $eventId;

		return EventSpot::create($event_array);
	}

	/**
	 * Get lists within an account
	 * @param $accessToken - Constant Contact OAuth2 access token
	 * @param string $eventId ID of the event
	 * @param array $params - associative array of query parameters and values to append to the request.
	 *      Allowed parameters include:
	 *      limit - Default: 50, up to 500
	 * @return EventSpot[]
	 * @throws CtctException
	 */
	public function getRegistrants($accessToken, $eventId, Array $params = array())
	{
		$baseUrl = Config::get('endpoints.base_url') . sprintf( Config::get('endpoints.event_registrants'), $eventId );

		$request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);
		if ($params) {
			$query = $request->getQuery();
			foreach ($params as $name => $value) {
				$query->add($name, $value);
			}
		}

		try {
			/** @var \GuzzleHttp\Message\Response $response */
			$response = parent::getClient()->send($request);
		} catch (ClientException $e) {
			throw parent::convertException($e);
		}

		$body = $response->json();
		$registrants = array();
		foreach ($body['results'] as $registrant) {
			$registrants[] = Registrant::create($registrant);
		}

		return new ResultSet($registrants, $body['meta']);
	}

	/**
	 * Create a new Event
	 * @param string $accessToken - Constant Contact OAuth2 access token
	 * @param string $eventId ID of the event
	 * @param string $registrantId ID of the registrant
	 * @return Registrant
	 * @throws CtctException
	 */
	public function getRegistrant($accessToken, $eventId, $registrantId )
	{
		$baseUrl = Config::get('endpoints.base_url') . sprintf( Config::get('endpoints.event_registrant'), $eventId, $registrantId );

		$request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);

		try {
			$response = parent::getClient()->send($request);
		} catch (ClientException $e) {
			throw parent::convertException($e);
		}

		return Registrant::create($response->json());
	}

	/**
	 * Create a new Event
	 * @param string $accessToken - Constant Contact OAuth2 access token
	 * @param string $eventId ID of the event
	 * @param EventFee $eventFee
	 * @return EventFee Created fee object
	 * @throws CtctException
	 */
	public function addFee($accessToken, $eventId, $eventFee )
	{
		$baseUrl = Config::get('endpoints.base_url') . sprintf( Config::get('endpoints.event_fees'), $eventId );

		$request = parent::createBaseRequest($accessToken, 'POST', $baseUrl);
		$stream = Stream::factory(json_encode($eventFee));
		$request->setBody($stream);

		try {
			$response = parent::getClient()->send($request);
		} catch (ClientException $e) {
			throw parent::convertException($e);
		}

		return EventFee::create($response->json());
	}

	/**
	 * Get fees for an event
	 * @param $accessToken - Constant Contact OAuth2 access token
	 * @param string $eventId ID of the event
	 * @return EventFee[]
	 * @throws CtctException
	 */
	public function getFees($accessToken, $eventId )
	{
		$baseUrl = Config::get('endpoints.base_url') . sprintf( Config::get('endpoints.event_fees'), $eventId );

		$request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);

		try {
			/** @var \GuzzleHttp\Message\Response $response */
			$response = parent::getClient()->send($request);
		} catch (ClientException $e) {
			throw parent::convertException($e);
		}

		$body = $response->json();
		$fees = array();
		foreach ($body['results'] as $fee) {
			$fees[] = EventFee::create($fee);
		}

		return $fees;
	}

	/**
	 * Get an individual Event fee
	 * @param $accessToken - Constant Contact OAuth2 access token
	 * @param $eventId - event id
	 * @param $feeId - Fee id
	 * @return EventSpot
	 * @throws CtctException
	 */
	public function getFee($accessToken, $eventId, $feeId)
	{
		$baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.event_fee'), $eventId, $feeId);

		$request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);

		try {
			$response = parent::getClient()->send($request);
		} catch (ClientException $e) {
			throw parent::convertException($e);
		}

		return EventFee::create($response->json());
	}

	/**
	 * Create a new Promocode
	 * @param string $accessToken Constant Contact OAuth2 access token
	 * @param string $eventId Unique ID of the event for which to retrieve the promocode
	 * @param Promocode $promoCode Promocode object to add
	 * @return Promocode Created promocode object
	 * @throws CtctException
	 */
	public function addPromocode($accessToken, $eventId, $promoCode )
	{
		$baseUrl = Config::get('endpoints.base_url') . sprintf( Config::get('endpoints.event_promocodes'), $eventId );

		$request = parent::createBaseRequest($accessToken, 'POST', $baseUrl);
		$stream = Stream::factory(json_encode($promoCode));
		$request->setBody($stream);

		try {
			$response = parent::getClient()->send($request);
		} catch (ClientException $e) {
			throw parent::convertException($e);
		}

		return Promocode::create($response->json());
	}

	/**
	 * Get promocodes for an event
	 * @param string $accessToken Constant Contact OAuth2 access token
	 * @param string $eventId Unique ID of the event for which to retrieve the promocode
	 * @return Promocode[]
	 * @throws CtctException
	 */
	public function getPromocodes($accessToken, $eventId )
	{
		$baseUrl = Config::get('endpoints.base_url') . sprintf( Config::get('endpoints.event_promocodes'), $eventId );

		$request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);

		try {
			/** @var \GuzzleHttp\Message\Response $response */
			$response = parent::getClient()->send($request);
		} catch (ClientException $e) {
			throw parent::convertException($e);
		}

		$body = $response->json();
		$promocodes = array();
		foreach ($body['results'] as $promocode) {
			$promocodes[] = Promocode::create($promocode);
		}

		return $promocodes;
	}

	/**
	 * Get an individual Promocode fee
	 * @param string $accessToken Constant Contact OAuth2 access token
	 * @param string $eventId Unique ID of the event for which to retrieve the promocode
	 * @param string $promocodeId Unique ID of the promocode to retrieve
	 * @return Promocode
	 * @throws CtctException
	 */
	public function getPromocode($accessToken, $eventId, $promocodeId)
	{
		$baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.event_promocode'), $eventId, $promocodeId);

		$request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);

		try {
			$response = parent::getClient()->send($request);
		} catch (ClientException $e) {
			throw parent::convertException($e);
		}

		return Promocode::create($response->json());
	}

}
