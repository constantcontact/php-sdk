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
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7;

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
	 * @param string $accessToken Constant Contact OAuth2 access token
	 * @param array $params - associative array of query parameters and values to append to the request.
	 *      Allowed parameters include:
	 *      limit - Default: 50, up to 500
	 * @return ResultSet
	 * @throws CtctException
	 */
	public function getEvents($accessToken, Array $params = array())
	{
		$baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.events');

		try {
			$response = parent::sendRequestWithoutBody($accessToken, 'GET', $baseUrl, $params);
		} catch (ClientException $e) {
			throw parent::convertException($e);
		}

		$body = json_decode($response->getBody(), true);

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
	 * @param string $accessToken Constant Contact OAuth2 access token
	 * @param EventSpot $event
	 * @return EventSpot
	 * @throws CtctException
	 */
	public function addEvent($accessToken, EventSpot $event)
	{
		$baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.events');

		try {
			$response = parent::sendRequestWithBody($accessToken, 'POST', $baseUrl, $event);
		} catch (ClientException $e) {
			throw parent::convertException($e);
		}

		return EventSpot::create(json_decode($response->getBody(), true));
	}

	/**
	 * Update an EventSpot Event
	 * @param string $accessToken Constant Contact OAuth2 access token
	 * @param EventSpot $event - EventSpot to be updated
	 * @return EventSpot
	 * @throws CtctException
	 */
	public function updateEvent($accessToken, EventSpot $event)
	{
		$baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.event'), $event->id);

		try {
			$response = parent::sendRequestWithBody($accessToken, 'PUT', $baseUrl, $event);
		} catch (TransferException $e) {
			throw parent::convertException($e);
		}

		return EventSpot::create(json_decode($response->getBody(), true));
	}

	/**
	 * Delete an Event
	 * @param string $accessToken Constant Contact OAuth2 access token
	 * @param string|int $eventId - event id
	 * @return boolean
	 * @throws CtctException
	 */
	public function deleteEvent($accessToken, $eventId)
	{
		$baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.event'), $eventId);

		try {
			$response = parent::sendRequestWithoutBody($accessToken, 'DELETE', $baseUrl);
		} catch (TransferException $e) {
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

		try {
			$response = parent::sendRequestWithoutBody($accessToken, 'GET', $baseUrl);
		} catch (TransferException $e) {
			throw parent::convertException($e);
		}

		$event_array = json_decode($response->getBody(), true);
		$event_array['id'] = $eventId;

		return EventSpot::create($event_array);
	}

	/**
	 * Get lists within an account
	 * @param string $accessToken Constant Contact OAuth2 access token
	 * @param string $eventId Unique ID of the event for which to retrieve the promocode
	 * @param array $params - associative array of query parameters and values to append to the request.
	 *      Allowed parameters include:
	 *      limit - Default: 50, up to 500
	 * @return EventSpot[]
	 * @throws CtctException
	 */
	public function getRegistrants($accessToken, $eventId, Array $params = array())
	{
		$baseUrl = Config::get('endpoints.base_url') . sprintf( Config::get('endpoints.event_registrants'), $eventId );

		try {
			$response = parent::sendRequestWithoutBody($accessToken, 'GET', $baseUrl, $params);
		} catch (TransferException $e) {
			throw parent::convertException($e);
		}

		$body = json_decode($response->getBody(), true);
		$registrants = array();
		foreach ($body['results'] as $registrant) {
			$registrants[] = Registrant::create($registrant);
		}

		return new ResultSet($registrants, $body['meta']);
	}

	/**
	 * Create a new Event
	 * @param string $accessToken Constant Contact OAuth2 access token
	 * @param string $eventId Unique ID of the event for which to retrieve the promocode
	 * @param string $registrantId ID of the registrant
	 * @return Registrant
	 * @throws CtctException
	 */
	public function getRegistrant($accessToken, $eventId, $registrantId )
	{
		$baseUrl = Config::get('endpoints.base_url') . sprintf( Config::get('endpoints.event_registrant'), $eventId, $registrantId );

		try {
			$response = parent::sendRequestWithoutBody($accessToken, 'GET', $baseUrl);
		} catch (TransferException $e) {
			throw parent::convertException($e);
		}

		return Registrant::create(json_decode($response->getBody(), true));
	}

	/**
	 * Create a new Event
	 * @param string $accessToken Constant Contact OAuth2 access token
	 * @param string $eventId Unique ID of the event for which to retrieve the promocode
	 * @param EventFee $eventFee EventFee object to create
	 * @return EventFee Created EventFee object
	 * @throws CtctException
	 */
	public function addFee($accessToken, $eventId, $eventFee )
	{
		$baseUrl = Config::get('endpoints.base_url') . sprintf( Config::get('endpoints.event_fees'), $eventId );

		try {
			$response = parent::sendRequestWithBody($accessToken, 'POST', $baseUrl, $eventFee);
		} catch (TransferException $e) {
			throw parent::convertException($e);
		}

		return EventFee::create(json_decode($response->getBody(), true));
	}

	/**
	 * Get fees for an event
	 * @param string $accessToken Constant Contact OAuth2 access token
	 * @param string $eventId Unique ID of the event for which to retrieve the promocode
	 * @return EventFee[]
	 * @throws CtctException
	 */
	public function getFees($accessToken, $eventId )
	{
		$baseUrl = Config::get('endpoints.base_url') . sprintf( Config::get('endpoints.event_fees'), $eventId );

		try {
			$response = parent::sendRequestWithoutBody($accessToken, 'GET', $baseUrl);
		} catch (TransferException $e) {
			throw parent::convertException($e);
		}

		$body = json_decode($response->getBody(), true);
		$fees = array();
		foreach ($body['results'] as $fee) {
			$fees[] = EventFee::create($fee);
		}

		return $fees;
	}

	/**
	 * Get an individual Event fee
	 * @param string $accessToken Constant Contact OAuth2 access token
	 * @param string $eventId Unique ID of the event for which to retrieve the promocode
	 * @param string $feeId Unique ID of the fee to retrieve
	 * @return EventSpot
	 * @throws CtctException
	 */
	public function getFee($accessToken, $eventId, $feeId)
	{
		$baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.event_fee'), $eventId, $feeId);


		try {
			$response = parent::sendRequestWithoutBody($accessToken, 'GET', $baseUrl);
		} catch (TransferException $e) {
			throw parent::convertException($e);
		}

		return EventFee::create(json_decode($response->getBody(), true));
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

		try {
			$response = parent::sendRequestWithBody($accessToken, 'POST', $baseUrl, $promoCode);
		} catch (TransferException $e) {
			throw parent::convertException($e);
		}

		return Promocode::create(json_decode($response->getBody(), true));
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

		try {
			$response = parent::sendRequestWithoutBody($accessToken, 'GET', $baseUrl);
		} catch (TransferException $e) {
			throw parent::convertException($e);
		}

		$body = json_decode($response->getBody(), true);
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


		try {
			$response = parent::sendRequestWithoutBody($accessToken, 'GET', $baseUrl);
		} catch (TransferException $e) {
			throw parent::convertException($e);
		}

		return Promocode::create(json_decode($response->getBody(), true));
	}

}
