<?php
namespace Ctct;

use Ctct\Services\ContactService;
use Ctct\Services\ListService;
use Ctct\Services\CampaignService;
use Ctct\Services\CampaignScheduleService;
use Ctct\Services\CampaignTrackingService;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\Campaigns\Campaign;
use Ctct\Components\Campaigns\Schedule;
use Ctct\Components\Campaigns\TestSend;
use Ctct\Components\Tracking\TrackingSummary;
use Ctct\Components\Tracking\TrackingActivity;
use Ctct\Exceptions\CtctException;
use Ctct\Exceptions\IllegalArgumentException;
use Ctct\Util\Config;

/**
 *
 * Exposes all implemented Constant Contact API functionality
 * @package 	Ctct
 * @author 		Constant Contact
 */
class ConstantContact{

    /**
     * Constant Contact API Key
     * @var string
     */
    private $api_key;
	
	
	/**
	 * Class constructor
	 * @param string $api_key - Constant Contact API Key
	 */
	public function __construct($api_key)
	{
		$this->api_key = $api_key;
	}
	
	/**
	 * Get a set of contacts
	 * @param string $access_token - Valid access token
     * @param int $offset - denotes the starting number for the result set
     * @param int $limit - denotes the number of results per set, limited to 50
     * @throws CtctException - if there is an error with the request
	 * @return array
	 */
	public function getContacts($access_token, $offset = null, $limit = null)
	{
		return ContactService::getContacts($access_token, $offset, $limit);
	}
	
	/**
	 * Get an individual contact
	 * @param string $access_token - Valid access token
	 * @param int $contact_id - Id of the contact to retrieve
	 * @return Contact
	 */
	public function getContact($access_token, $contact_id)
	{
		return ContactService::getContact($access_token, $contact_id);
	}
	
	/**
	 * Get contacts with a specified email eaddress
	 * @param string $access_token - Constant Contact OAuth2 access token
	 * @param string $email - contact email address to search for
	 * @return array
	 */
	public function getContactByEmail($access_token, $email)
	{
		return ContactService::getContactByEmail($access_token, $email);
	}
	
	/**
	 * Add a new contact to an account
	 * @param string $access_token - Valid access token
	 * @param Contact $contact - Contact to add
	 * @return Contact
	 */
	public function addContact($access_token, Contact $contact)
	{
		return ContactService::addContact($access_token, $contact);
	}
	
	/**
	 * Sets an individual contact to 'REMOVED' status
	 * @param string $access_token - Valid access token
	 * @param mixed $contact - Either a Contact id or the Contact itself
     * @throws IllegalArgumentException - if an int or Contact object is not provided
	 * @return boolean
	 */
	public function deleteContact($access_token, $contact)
	{
		$contact_id = self::getArgumentId($contact, 'Contact');
		return ContactService::deleteContact($access_token, $contact_id);
	}
	
	/**
	 * Delete a contact from all contact lists
	 * @param string $access_token - Constant Contact OAuth2 access token
	 * @param mixed $contact - Contact id or the Contact object itself
	 * @throws IllegalArgumentException - if an int or Contact object is not provided
	 * @return boolean
	 */
	public function deleteContactFromLists($access_token, $contact)
	{
		$contact_id = self::getArgumentId($contact, 'Contact');
		return ContactService::deleteContactFromLists($access_token, $contact_id);
	}

	/**
	 * Delete a contact from all contact lists
	 * @param string $access_token - Constant Contact OAuth2 access token
	 * @param mixed $contact - Contact id or a Contact object
	 * @param mixed $list - ContactList id or a ContactList object
     * @throws IllegalArgumentException - if an int or Contact object is not provided, as well as an int or ContactList object
	 * @return boolean
	 */
	public function deleteContactFromList($access_token, $contact, $list)
	{
		$contact_id = self::getArgumentId($contact, 'Contact');
		$list_id = self::getArgumentId($list, 'ContactList');
		
		return ContactService::deleteContactFromList($access_token, $contact_id, $list_id);
	}
	
	/**
	 * Update an individual contact
	 * @param string $access_token - Valid access token
	 * @param Contact $contact - Contact to update
	 * @return Contact
	 */
	public function updateContact($access_token, Contact $contact)
	{
		return ContactService::updateContact($access_token, $contact);
	}
	
	/**
	 * Get lists
	 * @param string $access_token - Valid access token
	 * @return array
	 */
	public function getLists($access_token)
	{
		return ListService::getLists($access_token);
	}
	
	/**
	 * Get an individual list
	 * @param string $access_token - Valid access token
	 * @param int $list_id - Id of the list to retrieve
	 * @return ContactList
	 */
	public function getList($access_token, $list_id)
	{
		return ListService::getList($access_token, $list_id);
	}
	
	/**
	 * Add a new contact list to an account
	 * @param string $access_token - Valid access token
	 * @param ContactList $list - List to add
	 * @return ContactList
	 */
	public function addList($access_token, ContactList $list)
	{
		return ListService::addList($access_token, $list);
	}
	
	/**
	 * Update a contact list
	 * @param string $access_token - Valid access token
	 * @param ContactList $list - ContactList to update
	 * @return ContactList
	 */
	public function updateList($access_token, ContactList $list)
	{
		return ListService::updateList($access_token, $list);
	}

    /**
     * Get contact that belong to a specific list
     * @param string $access_token - Constant Contact OAuth2 access token
     * @param mixed $list - Id of the list or a ContactList object
     * @return array
     * @throws IllegalArgumentException - if a ContactList object or id is not passed
     */
    public function getContactsFromList($access_token, $list)
    {
    	$list_id = self::getArgumentId($list, 'ContactList');
		return ListService::getContactsFromList($access_token, $list_id);
    }
	
	/**
	 * Get a set of campaigns
	 * @param string $access_token - Constant Contact OAuth2 access token
	 * @return Campaign
	 */
	public function getCampaigns($access_token)
	{
		return CampaignService::getCampaigns($access_token);
	}
	
	/**
	 * Get an individual campaign
	 * @param string $access_token - Constant Contact OAuth2 access token
	 * @param int $campaign_id - Valid campaign id
	 */
	public function getCampaign($access_token, $campaign_id)
	{
		return CampaignService::getCampaign($access_token, $campaign_id);
	}
	
	/**
	 * Delete an individual campaign
	 * @param string $access_token - Constant Contact OAuth2 access token
	 * @param mixed $campaign - Id of a campaign or a Campaign object itself
	 * @throws IllegalArgumentException - if a Campaign object or campaign id is not passed
	 * @return boolean 
	 */
	public function deleteCampaign($access_token, $campaign)
	{
		$campaign_id = self::getArgumentId($campaign, 'Campaign');
		return CampaignService::deleteCampaign($access_token, $campaign_id);
	}
	
	/**
	 * Create a new campaign
	 * @param string $access_token - Constant Contact OAuth2 access token
	 * @param Campaign $campaign - Campaign to be created
	 * @return Campaign - created campaign
	 */
	public function addCampaign($access_token, Campaign $campaign)
	{
		return CampaignService::addCampaign($access_token, $campaign);
	}
	
	/**
	 * Update a specific campaign
	 * @param string $access_token - Constant Contact OAuth2 access token
	 * @param Campaign $campaign - Campaign to be updated
	 * @return Campaign - updated campaign
	 */
	public function updateCampaign($access_token, Campaign $campaign)
	{
		return CampaignService::updateCampaign($access_token, $campaign);
	}
	
	/**
	 * Schedule a campaign to be sent
	 * @param string $access_token - Constant Contact OAuth2 access token
	 * @param mixed $campaign - Campaign to be updated
	 * @param Schedule $schedule - Schedule to be associated with the provided campaign
	 * @return Campaign - updated campaign
	 */
	public function addCampaignSchedule($access_token, $campaign, Schedule $schedule)
	{
		$campaign_id = self::getArgumentId($campaign, 'Campaign');
		return CampaignScheduleService::addSchedule($access_token, $campaign_id, $schedule);
	}
	
	/**
	 * Get an array of schedules associated with a given campaign
	 * @param string $access_token - Constant Contact OAuth2 access token
	 * @param mixed $campaign - Campaign id  or Campaign object itself
	 * @return array
	 */
	public function getCampaignSchedules($access_token, $campaign)
	{
		$campaign_id = self::getArgumentId($campaign, 'Campaign');
		return CampaignScheduleService::getSchedules($access_token, $campaign_id);
	}
	
	/**
	 * Get a specific schedule associated with a given campaign
	 * @param string $access_token - Constant Contact OAuth2 access token
	 * @param mixed $campaign - Campaign id or Campaign object itself
	 * @param mixed $schedule - Schedule id or Schedule object itself
     * @throws IllegalArgumentException
	 * @return array
	 */
	public function getCampaignSchedule($access_token, $campaign, $schedule)
	{
		$campaign_id = self::getArgumentId($campaign, 'Campaign');
		
		$schedule_id = null;

		if ($schedule instanceof Schedule) {
			$schedule_id = $schedule->schedule_id;
		} elseif (is_numeric($schedule)) {
			$schedule_id = $schedule;
		} else {
			throw new IllegalArgumentException(sprintf(Config::get('errors.id_or_object'), 'Schedule'));
		}
		
		return CampaignScheduleService::getSchedule($access_token, $campaign_id, $schedule_id);
	}
	
	/**
	 * Update a specific schedule associated with a given campaign
	 * @param string $access_token - Constant Contact OAuth2 access token
	 * @param mixed $campaign - Campaign id or Campaign object itself
	 * @param Schedule $schedule - Schedule to be updated
	 * @return array
	 */
	public function updateCampaignSchedule($access_token, $campaign, Schedule $schedule)
	{
		$campaign_id = self::getArgumentId($campaign, 'Campaign');
		
		return CampaignScheduleService::updateSchedule($access_token, $campaign_id, $schedule);
	}
	
	/**
	 * Delete a specific schedule associated with a given campaign
	 * @param string $access_token - Constant Contact OAuth2 access token
	 * @param mixed $campaign - Campaign id or Campaign object itself
	 * @param mixed $schedule - Schedule id or Schedule object itself
     * @throws IllegalArgumentException
	 * @return array
	 */
	public function deleteCampaignSchedule($access_token, $campaign, $schedule)
	{
		$campaign_id = self::getArgumentId($campaign, 'Campaign');
		$schedule_id = null;
		
		if ($schedule instanceof Schedule) {
			$schedule_id = $schedule->schedule_id;
		} elseif (is_numeric($schedule)) {
			$schedule_id = $schedule;
		} else {
			throw new IllegalArgumentException(sprintf(Config::get('errors.id_or_object'), 'Schedule'));
		}
		
		return CampaignScheduleService::deleteSchedule($access_token, $campaign_id, $schedule_id);
	}

    /**
     * Send a test send of a campaign
     * @param string $access_token - Constant Contact OAuth2 access token
     * @param mixed $campaign  - Campaign id or Campaign object itself
     * @param TestSend $test_send - test send details
     * @return TestSend
     */
    public function sendCampaignTest($access_token, $campaign, TestSend $test_send)
    {
        $campaign_id = self::getArgumentId($campaign, 'Campaign');
        return CampaignScheduleService::sendTest($access_token, $campaign_id, $test_send);
    }

    /**
     * Get sends for a campaign
     * @param string $access_token - Constant Contact OAuth2 access token
     * @param mixed $campaign  - Campaign id or Campaign object itself
     * @param string $next - next value returned from a previous request (used in pagination)
     * @param int $limit - number of results to return per page
     * @return TrackingActivity - Containing a results array of {@link Ctct\Components\CampaignTracking\SendActivity}
     */
    public function getCampaignSends($access_token, $campaign, $next = null, $limit = null)
    {
        $campaign_id = self::getArgumentId($campaign, 'Campaign');
        return CampaignTrackingService::getSends($access_token, $campaign_id, $next, $limit);
    }

    /**
     * Get bounces for a campaign
     * @param string $access_token - Constant Contact OAuth2 access token
     * @param mixed $campaign  - Campaign id or Campaign object itself
     * @param string $next - next value returned from a previous request (used in pagination)
     * @param int $limit - number of results to return per page
     * @return TrackingActivity - Containing a results array of {@link Ctct\Components\CampaignTracking\BounceActivity}
     */
    public function getCampaignBounces($access_token, $campaign, $next = null, $limit = null)
    {
        $campaign_id = self::getArgumentId($campaign, 'Campaign');
        return CampaignTrackingService::getBounces($access_token, $campaign_id, $next, $limit);
    }

    /**
     * Get clicks for a campaign
     * @param string $access_token - Constant Contact OAuth2 access token
     * @param mixed $campaign  - Campaign id or Campaign object itself
     * @param string $next - next value returned from a previous request (used in pagination)
     * @param int $limit - number of results to return per page
     * @return TrackingActivity - Containing a results array of {@link Ctct\Components\CampaignTracking\ClickActivity}
     */
    public function getCampaignClicks($access_token, $campaign, $next = null, $limit = null)
    {
        $campaign_id = self::getArgumentId($campaign, 'Campaign');
        return CampaignTrackingService::getClicks($access_token, $campaign_id, $next, $limit);
    }

    /**
     * Get opens for a campaign
     * @param string $access_token - Constant Contact OAuth2 access token
     * @param mixed $campaign  - Campaign id or Campaign object itself
     * @param string $next - next value returned from a previous request (used in pagination)
     * @param int $limit - number of results to return per page
     * @return TrackingActivity - Containing a results array of {@link Ctct\Components\CampaignTracking\OpenActivity}
     */
    public function getCampaignOpens($access_token, $campaign, $next = null, $limit = null)
    {
        $campaign_id = self::getArgumentId($campaign, 'Campaign');
        return CampaignTrackingService::getOpens($access_token, $campaign_id, $next, $limit);
    }

    /**
     * Get forwards for a campaign
     * @param string $access_token - Constant Contact OAuth2 access token
     * @param mixed $campaign  - Campaign id or Campaign object itself
     * @param string $next - next value returned from a previous request (used in pagination)
     * @param int $limit - number of results to return per page
     * @return TrackingActivity - Containing a results array of {@link Ctct\Components\CampaignTracking\ForwardActivity}
     */
    public function getCampaignForwards($access_token, $campaign, $next = null, $limit = null)
    {
        $campaign_id = self::getArgumentId($campaign, 'Campaign');
        return CampaignTrackingService::getForwards($access_token, $campaign_id, $next, $limit);
    }

    /**
     * Get opt outs for a campaign
     * @param string $access_token - Constant Contact OAuth2 access token
     * @param mixed $campaign  - Campaign id or Campaign object itself
     * @param string $next - next value returned from a previous request (used in pagination)
     * @param int $limit - number of results to return per page
     * @return TrackingActivity - Containing a results array of {@link Ctct\Components\CampaignTracking\OptOutActivity}
     */
    public function getCampaignOptOuts($access_token, $campaign, $next = null, $limit = null)
    {
        $campaign_id = self::getArgumentId($campaign, 'Campaign');
        return CampaignTrackingService::getOptOuts($access_token, $campaign_id, $next, $limit);
    }

    /**
     * Get a reporting summary for a campaign
     * @param string $access_token - Constant Contact OAuth2 access token
     * @param mixed $campaign  - Campaign id or Campaign object itself
     * @param string $next - next value returned from a previous request (used in pagination)
     * @param int $limit - number of results to return per page
     * @return TrackingSummary
     */
    public function getCampaignSummaryReport($access_token, $campaign)
    {
        $campaign_id = self::getArgumentId($campaign, 'Campaign');
        return CampaignTrackingService::getSummary($access_token, $campaign_id);
    }

    /**
     * Get sends for a Contact
     * @param string $access_token - Constant Contact OAuth2 access token
     * @param mixed $contact  - Contact id or Contact object itself
     * @param string $next - next value returned from a previous request (used in pagination)
     * @param int $limit - number of results to return per page
     * @return TrackingActivity - Containing a results array of {@link Ctct\Components\Tracking\SendActivity}
     */
    public function getContactSends($access_token, $contact, $next = null, $limit = null)
    {
        $contact_id = self::getArgumentId($contact, 'Contact');
        return ContactTrackingService::getSends($access_token, $contact_id, $next, $limit);
    }

    /**
     * Get bounces for a Contact
     * @param string $access_token - Constant Contact OAuth2 access token
     * @param mixed $contact  - Contact id or Contact object itself
     * @param string $next - next value returned from a previous request (used in pagination)
     * @param int $limit - number of results to return per page
     * @return TrackingActivity - Containing a results array of {@link Ctct\Components\Tracking\BounceActivity}
     */
    public function getContactBounces($access_token, $contact, $next = null, $limit = null)
    {
        $contact_id = self::getArgumentId($contact, 'Contact');
        return ContactTrackingService::getBounces($access_token, $contact_id, $next, $limit);
    }

    /**
     * Get clicks for a Contact
     * @param string $access_token - Constant Contact OAuth2 access token
     * @param mixed $contact  - Contact id or Contact object itself
     * @param string $next - next value returned from a previous request (used in pagination)
     * @param int $limit - number of results to return per page
     * @return TrackingActivity - Containing a results array of {@link Ctct\Components\Tracking\ClickActivity}
     */
    public function getContactClicks($access_token, $contact, $next = null, $limit = null)
    {
        $contact_id = self::getArgumentId($contact, 'Contact');
        return ContactTrackingService::getClicks($access_token, $contact_id, $next, $limit);
    }

    /**
     * Get opens for a Contact
     * @param string $access_token - Constant Contact OAuth2 access token
     * @param mixed $contact  - Contact id or Contact object itself
     * @param string $next - next value returned from a previous request (used in pagination)
     * @param int $limit - number of results to return per page
     * @return TrackingActivity - Containing a results array of {@link Ctct\Components\Tracking\OpenActivity}
     */
    public function getContactOpens($access_token, $contact, $next = null, $limit = null)
    {
        $contact_id = self::getArgumentId($contact, 'Contact');
        return ContactTrackingService::getOpens($access_token, $contact_id, $next, $limit);
    }

    /**
     * Get forwards for a Contact
     * @param string $access_token - Constant Contact OAuth2 access token
     * @param mixed $contact  - Contact id or Contact object itself
     * @param string $next - next value returned from a previous request (used in pagination)
     * @param int $limit - number of results to return per page
     * @return TrackingActivity - Containing a results array of {@link Ctct\Components\Tracking\ForwardActivity}
     */
    public function getContactForwards($access_token, $contact, $next = null, $limit = null)
    {
        $contact_id = self::getArgumentId($contact, 'Contact');
        return ContactTrackingService::getForwards($access_token, $contact_id, $next, $limit);
    }

    /**
     * Get opt outs for a Contact
     * @param string $access_token - Constant Contact OAuth2 access token
     * @param mixed $contact  - Contact id or Contact object itself
     * @param string $next - next value returned from a previous request (used in pagination)
     * @param int $limit - number of results to return per page
     * @return TrackingActivity - Containing a results array of {@link Ctct\Components\Tracking\OptOutActivity}
     */
    public function getContactOptOuts($access_token, $contact, $next = null, $limit = null)
    {
        $contact_id = self::getArgumentId($contact, 'Contact');
        return ContactTrackingService::getOptOuts($access_token, $contact_id, $next, $limit);
    }

    /**
     * Get a reporting summary for a Contact
     * @param string $access_token - Constant Contact OAuth2 access token
     * @param mixed $contact  - Contact id or Contact object itself
     * @param string $next - next value returned from a previous request (used in pagination)
     * @param int $limit - number of results to return per page
     * @return TrackingSummary
     */
    public function getContactSummaryReport($access_token, $contact)
    {
        $contact_id = self::getArgumentId($contact, 'Contact');
        return ContactTrackingService::getSummary($access_token, $contact_id);
    }
	
	/**
	 * Get the id of object, or attempt to convert the argument to an int
	 * @param mixed $item - object or a numeric value
	 * @param string $class_name - class name to test the given object against
	 * @throws IllegalArgumentException - if the item is not an instance of the class name given, or cannot be
     * converted to a numeric value
	 * @return int
	 */
	private static function getArgumentId($item, $class_name)
	{
		$id = null;

		if (is_numeric($item)) {
			$id = $item;
		} elseif (join('', array_slice(explode('\\', get_class($item)), -1)) == $class_name) {
			$id = $item->id;
		} else {
			throw new IllegalArgumentException(sprintf(Config::get('errors.id_or_object'), $class_name));
		}

		return $id;
	}
	
}
