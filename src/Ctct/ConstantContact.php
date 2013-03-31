<?php
namespace Ctct;

use Ctct\Services\BaseService;
use Ctct\Services\AccountService;
use Ctct\Services\ContactService;
use Ctct\Services\ListService;
use Ctct\Services\EmailMarketingService;
use Ctct\Services\CampaignScheduleService;
use Ctct\Services\CampaignTrackingService;
use Ctct\Services\ContactTrackingService;
use Ctct\Services\ActivityService;
use Ctct\Components\Account\VerifiedEmailAddress;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\EmailMarketing\Campaign;
use Ctct\Components\EmailMarketing\Schedule;
use Ctct\Components\EmailMarketing\TestSend;
use Ctct\Components\Tracking\TrackingSummary;
use Ctct\Components\Tracking\TrackingActivity;
use Ctct\Components\Activities\AddContacts;
use Ctct\Components\Activities\ExportContacts;
use Ctct\Exceptions\CtctException;
use Ctct\Exceptions\IllegalArgumentException;
use Ctct\Util\Config;

/**
 * Exposes all implemented Constant Contact API functionality
 * @package Ctct
 * @version 1.0.0
 * @author Constant Contact
 */
class ConstantContact
{
    /**
     * Constant Contact API Key
     * @var string
     */
    private $apiKey;

    /**
     * ContactService
     * @var ContactService
     */
    private $contactService;

    /**
     * CampaignService
     * @var CampaignService
     */
    private $emailMarketingService;

    /**
     * ListService
     * @var ListService
     */
    private $listService;

    /**
     * ActivityService
     * @var ActivityService
     */
    private $activityService;

    /**
     * CampaignTrackingService
     * @var CampaignTrackingService
     */
    private $campaignTrackingService;

    /**
     * ContactTrackingService
     * @var ContactTrackingService
     */
    private $contactTrackingService;

    /**
     * CampaignScheduleService
     * @var CampaignScheduleService
     */
    private $campaignScheduleService;

    /**
     * AccountService
     * @var AccountService
     */
    private $accountService;

    /**
     * Class constructor
     * @param string $apiKey - Constant Contact API Key
     */
    public function __construct($apiKey)
    {
        $this->api_key = $apiKey;
        $this->contactService = new ContactService($apiKey);
        $this->emailMarketingService = new EmailMarketingService($apiKey);
        $this->activityService = new ActivityService($apiKey);
        $this->campaignTrackingService = new CampaignTrackingService($apiKey);
        $this->contactTrackingService = new ContactTrackingService($apiKey);
        $this->campaignScheduleService = new CampaignScheduleService($apiKey);
        $this->listService = new ListService($apiKey);
        $this->accountService = new AccountService($apiKey);
    }
    
    /**
     * Get a set of campaigns
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param mixed $param - denotes the number of results per set, limited to 50, or a next parameter provided
     * from a previous getContacts call
     * @return ResultSet
     */
    public function getContacts($accessToken, $param = null)
    {
        $param = $this->determineParam($param);
        return $this->contactService->getContacts($accessToken, $param);
    }
    
    /**
     * Get an individual contact
     * @param string $accessToken - Valid access token
     * @param int $contactId - Id of the contact to retrieve
     * @return Contact
     */
    public function getContact($accessToken, $contactId)
    {
        return $this->contactService->getContact($accessToken, $contactId);
    }
    
    /**
     * Get contacts with a specified email eaddress
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param string $email - contact email address to search for
     * @return array
     */
    public function getContactByEmail($accessToken, $email)
    {
        return $this->contactService->getContacts($accessToken, array('email' => $email));
    }
    
    /**
     * Add a new contact to an account
     * @param string $accessToken - Valid access token
     * @param Contact $contact - Contact to add
     * @param boolean $actionByVisitor - is the action being taken by the visitor 
     * @return Contact
     */
    public function addContact($accessToken, Contact $contact, $actionByVisitor = false)
    {
        return $this->contactService->addContact($accessToken, $contact, $actionByVisitor);
    }
    
    /**
     * Sets an individual contact to 'REMOVED' status
     * @param string $accessToken - Valid access token
     * @param mixed $contact - Either a Contact id or the Contact itself
     * @throws IllegalArgumentException - if an int or Contact object is not provided
     * @return boolean
     */
    public function deleteContact($accessToken, $contact)
    {
        $contactId = $this->getArgumentId($contact, 'Contact');
        return $this->contactService->deleteContact($accessToken, $contactId);
    }
    
    /**
     * Delete a contact from all contact lists
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param mixed $contact - Contact id or the Contact object itself
     * @throws IllegalArgumentException - if an int or Contact object is not provided
     * @return boolean
     */
    public function deleteContactFromLists($accessToken, $contact)
    {
        $contactId = $this->getArgumentId($contact, 'Contact');
        return $this->contactService->deleteContactFromLists($accessToken, $contactId);
    }

    /**
     * Delete a contact from all contact lists
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param mixed $contact - Contact id or a Contact object
     * @param mixed $list - ContactList id or a ContactList object
     * @throws IllegalArgumentException - if an int or Contact object is not provided, 
     * as well as an int or ContactList object
     * @return boolean
     */
    public function deleteContactFromList($accessToken, $contact, $list)
    {
        $contactId = $this->getArgumentId($contact, 'Contact');
        $listId = $this->getArgumentId($list, 'ContactList');
        
        return $this->contactService->deleteContactFromList($accessToken, $contactId, $listId);
    }
    
    /**
     * Update an individual contact
     * @param string $accessToken - Valid access token
     * @param Contact $contact - Contact to update
     * @param boolean $actionByVisitor - is the action being taken by the visitor, default is false  
     * @return Contact
     */
    public function updateContact($accessToken, Contact $contact, $actionByVisitor = false)
    {
        return $this->contactService->updateContact($accessToken, $contact, $actionByVisitor);
    }
    
    /**
     * Get lists
     * @param string $accessToken - Valid access token
     * @return array
     */
    public function getLists($accessToken)
    {
        return $this->listService->getLists($accessToken);
    }
    
    /**
     * Get an individual list
     * @param string $accessToken - Valid access token
     * @param int $listId - Id of the list to retrieve
     * @return ContactList
     */
    public function getList($accessToken, $listId)
    {
        return $this->listService->getList($accessToken, $listId);
    }
    
    /**
     * Add a new contact list to an account
     * @param string $accessToken - Valid access token
     * @param ContactList $list - List to add
     * @return ContactList
     */
    public function addList($accessToken, ContactList $list)
    {
        return $this->listService->addList($accessToken, $list);
    }
    
    /**
     * Update a contact list
     * @param string $accessToken - Valid access token
     * @param ContactList $list - ContactList to update
     * @return ContactList
     */
    public function updateList($accessToken, ContactList $list)
    {
        return $this->listService->updateList($accessToken, $list);
    }

    /**
     * Get contact that belong to a specific list
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param mixed $list - Id of the list or a ContactList object
     * @param mixed $param - denotes the number of results per set, limited to 50, or a next parameter provided
     * from a previous getContactsFromList call
     * @return array
     * @throws IllegalArgumentException - if a ContactList object or id is not passed
     */
    public function getContactsFromList($accessToken, $list, $param = null)
    {
        $listId = $this->getArgumentId($list, 'ContactList');
        $param = $this->determineParam($param);
        return $this->listService->getContactsFromList($accessToken, $listId, $param);
    }
    
    /**
     * Get a set of campaigns
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param mixed $param - denotes the number of results per set, limited to 50, or a next parameter provided
     * from a previous getCampaigns call
     * @return ResultSet
     */
    public function getEmailCampaigns($accessToken, $status = null, $param = null)
    {
        $params = $this->determineParam($param);
        if ($status) {
            $params['status'] = $status;
        }
        return $this->emailMarketingService->getCampaigns($accessToken, $params);
    }
    
    /**
     * Get an individual campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $campaignId - Valid campaign id
     */
    public function getEmailCampaign($accessToken, $campaignId)
    {
        return $this->emailMarketingService->getCampaign($accessToken, $campaignId);
    }
    
    /**
     * Delete an individual campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param mixed $campaign - Id of a campaign or a Campaign object itself
     * @throws IllegalArgumentException - if a Campaign object or campaign id is not passed
     * @return boolean 
     */
    public function deleteEmailCampaign($accessToken, $campaign)
    {
        $campaignId = $this->getArgumentId($campaign, 'Campaign');
        return $this->emailMarketingService->deleteCampaign($accessToken, $campaignId);
    }
    
    /**
     * Create a new campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param Campaign $campaign - Campaign to be created
     * @return Campaign - created campaign
     */
    public function addEmailCampaign($accessToken, Campaign $campaign)
    {
        return $this->emailMarketingService->addCampaign($accessToken, $campaign);
    }
    
    /**
     * Update a specific campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param Campaign $campaign - Campaign to be updated
     * @return Campaign - updated campaign
     */
    public function updateEmailCampaign($accessToken, Campaign $campaign)
    {
        return $this->emailMarketingService->updateCampaign($accessToken, $campaign);
    }
    
    /**
     * Schedule a campaign to be sent
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param mixed $campaign - Campaign to be updated
     * @param Schedule $schedule - Schedule to be associated with the provided campaign
     * @return Campaign - updated campaign
     */
    public function addEmailCampaignSchedule($accessToken, $campaign, Schedule $schedule)
    {
        $campaignId = $this->getArgumentId($campaign, 'Campaign');
        return $this->campaignScheduleService->addSchedule($accessToken, $campaignId, $schedule);
    }
    
    /**
     * Get an array of schedules associated with a given campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param mixed $campaign - Campaign id  or Campaign object itself
     * @return array
     */
    public function getEmailCampaignSchedules($accessToken, $campaign)
    {
        $campaignId = $this->getArgumentId($campaign, 'Campaign');
        return $this->campaignScheduleService->getSchedules($accessToken, $campaignId);
    }
    
    /**
     * Get a specific schedule associated with a given campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param mixed $campaign - Campaign id or Campaign object itself
     * @param mixed $schedule - Schedule id or Schedule object itself
     * @throws IllegalArgumentException
     * @return array
     */
    public function getEmailCampaignSchedule($accessToken, $campaign, $schedule)
    {
        $campaignId = $this->getArgumentId($campaign, 'Campaign');
        
        $scheduleId = null;

        if ($schedule instanceof Schedule) {
            $scheduleId = $schedule->id;
        } elseif (is_numeric($schedule)) {
            $scheduleId = $schedule;
        } else {
            throw new IllegalArgumentException(sprintf(Config::get('errors.id_or_object'), 'Schedule'));
        }
        
        return $this->campaignScheduleService->getSchedule($accessToken, $campaignId, $scheduleId);
    }
    
    /**
     * Update a specific schedule associated with a given campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param mixed $campaign - Campaign id or Campaign object itself
     * @param Schedule $schedule - Schedule to be updated
     * @return array
     */
    public function updateEmailCampaignSchedule($accessToken, $campaign, Schedule $schedule)
    {
        $campaignId = $this->getArgumentId($campaign, 'Campaign');
        return $this->campaignScheduleService->updateSchedule($accessToken, $campaignId, $schedule);
    }
    
    /**
     * Delete a specific schedule associated with a given campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param mixed $campaign - Campaign id or Campaign object itself
     * @param mixed $schedule - Schedule id or Schedule object itself
     * @throws IllegalArgumentException
     * @return array
     */
    public function deleteEmailCampaignSchedule($accessToken, $campaign, $schedule)
    {
        $campaignId = $this->getArgumentId($campaign, 'Campaign');
        $scheduleId = null;
        
        if ($schedule instanceof Schedule) {
            $scheduleId = $schedule->id;
        } elseif (is_numeric($schedule)) {
            $scheduleId = $schedule;
        } else {
            throw new IllegalArgumentException(sprintf(Config::get('errors.id_or_object'), 'Schedule'));
        }
        
        return $this->campaignScheduleService->deleteSchedule($accessToken, $campaignId, $scheduleId);
    }

    /**
     * Send a test send of a campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param mixed $emailCampaign  - Campaign id or Campaign object itself
     * @param TestSend $test_send - test send details
     * @return TestSend
     */
    public function sendEmailCampaignTest($accessToken, $campaign, TestSend $test_send)
    {
        $campaignId = $this->getArgumentId($campaign, 'Campaign');
        return $this->campaignScheduleService->sendTest($accessToken, $campaignId, $test_send);
    }

    /**
     * Get sends for a campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param mixed $emailCampaign  - Campaign id or Campaign object itself
     * @param mixed $param - either the next link from a previous request, or a limit or restrict the page size of
     * an initial request
     * @return TrackingActivity - Containing a results array of {@link Ctct\Components\CampaignTracking\SendActivity}
     */
    public function getEmailCampaignSends($accessToken, $campaign, $param = null)
    {
        $campaignId = $this->getArgumentId($campaign, 'Campaign');
        $param = $this->determineParam($param);
        return $this->campaignTrackingService->getSends($accessToken, $campaignId, $param);
    }

    /**
     * Get bounces for a campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param mixed $emailCampaign  - Campaign id or Campaign object itself
     * @param mixed $param - either the next link from a previous request, or a limit or restrict the page size of
     * an initial request
     * @return ResultSet - Containing a results array of {@link Ctct\Components\CampaignTracking\BounceActivity}
     */
    public function getEmailCampaignBounces($accessToken, $campaign, $param = null)
    {
        $campaignId = $this->getArgumentId($campaign, 'Campaign');
        $param = $this->determineParam($param);
        return $this->campaignTrackingService->getBounces($accessToken, $campaignId, $param);
    }

    /**
     * Get clicks for a campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param mixed $emailCampaign  - Campaign id or Campaign object itself
     * @param mixed $param - either the next link from a previous request, or a limit or restrict the page size of
     * an initial request
     * @return ResultSet - Containing a results array of {@link Ctct\Components\CampaignTracking\ClickActivity}
     */
    public function getEmailCampaignClicks($accessToken, $campaign, $param = null)
    {
        $campaignId = $this->getArgumentId($campaign, 'Campaign');
        $param = $this->determineParam($param);
        return $this->campaignTrackingService->getClicks($accessToken, $campaignId, $param);
    }

    /**
     * Get opens for a campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param mixed $emailCampaign  - Campaign id or Campaign object itself
     * @param mixed $param - either the next link from a previous request, or a limit or restrict the page size of
     * an initial request
     * @return ResultSet - Containing a results array of {@link Ctct\Components\CampaignTracking\OpenActivity}
     */
    public function getEmailCampaignOpens($accessToken, $campaign, $param = null)
    {
        $campaignId = $this->getArgumentId($campaign, 'Campaign');
        $param = $this->determineParam($param);
        return $this->campaignTrackingService->getOpens($accessToken, $campaignId, $param);
    }

    /**
     * Get forwards for a campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param mixed $emailCampaign  - Campaign id or Campaign object itself
     * @param mixed $param - either the next link from a previous request, or a limit or restrict the page size of
     * an initial request
     * @return ResultSet - Containing a results array of {@link Ctct\Components\CampaignTracking\ForwardActivity}
     */
    public function getEmailCampaignForwards($accessToken, $campaign, $param = null)
    {
        $campaignId = $this->getArgumentId($campaign, 'Campaign');
        $param = $this->determineParam($param);
        return $this->campaignTrackingService->getForwards($accessToken, $campaignId, $param);
    }

    /**
     * Get unsubscribes for a campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param mixed $emailCampaign  - Campaign id or Campaign object itself
     * @param mixed $param - either the next link from a previous request, or a limit or restrict the page size of
     * an initial request
     * @return ResultSet - Containing a results array of {@link Ctct\Components\CampaignTracking\UnsubscribeActivity}
     */
    public function getEmailCampaignUnsubscribes($accessToken, $campaign, $param = null)
    {
        $campaignId = $this->getArgumentId($campaign, 'Campaign');
        $param = $this->determineParam($param);
        return $this->campaignTrackingService->getUnsubscribes($accessToken, $campaignId, $param);
    }

    /**
     * Get a reporting summary for a campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param mixed $emailCampaign  - Campaign id or Campaign object itself
     * @param string $next - next value returned from a previous request (used in pagination)
     * @param int $limit - number of results to return per page
     * @return TrackingSummary
     */
    public function getEmailCampaignSummaryReport($accessToken, $campaign)
    {
        $campaignId = $this->getArgumentId($campaign, 'Campaign');
        return $this->campaignTrackingService->getSummary($accessToken, $campaignId);
    }

    /**
     * Get sends for a Contact
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param mixed $contact  - Contact id or Contact object itself
     * @param mixed $param - either the next link from a previous request, or a limit or restrict the page size of
     * an initial request
     * @return ResultSet - Containing a results array of {@link Ctct\Components\Tracking\SendActivity}
     */
    public function getContactSends($accessToken, $contact, $param = null)
    {
        $contactId = $this->getArgumentId($contact, 'Contact');
        $param = $this->determineParam($param);
        return $this->contactTrackingService->getSends($accessToken, $contactId, $param);
    }

    /**
     * Get bounces for a Contact
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param mixed $contact  - Contact id or Contact object itself
     * @param mixed $param - either the next link from a previous request, or a limit or restrict the page size of
     * an initial request
     * @return ResultSet - Containing a results array of {@link Ctct\Components\Tracking\BounceActivity}
     */
    public function getContactBounces($accessToken, $contact, $param = null)
    {
        $contactId = $this->getArgumentId($contact, 'Contact');
        $param = $this->determineParam($param);
        return $this->contactTrackingService->getBounces($accessToken, $contactId, $param);
    }

    /**
     * Get clicks for a Contact
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param mixed $contact  - Contact id or Contact object itself
     * @param mixed $param - either the next link from a previous request, or a limit or restrict the page size of
     * an initial request
     * @return ResultSet - Containing a results array of {@link Ctct\Components\Tracking\ClickActivity}
     */
    public function getContactClicks($accessToken, $contact, $param = null)
    {
        $contactId = $this->getArgumentId($contact, 'Contact');
        $param = $this->determineParam($param);
        return $this->contactTrackingService->getClicks($accessToken, $contactId, $param);
    }

    /**
     * Get opens for a Contact
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param mixed $contact  - Contact id or Contact object itself
     * @param mixed $param - either the next link from a previous request, or a limit or restrict the page size of
     * an initial request
     * @return ResultSet - Containing a results array of {@link Ctct\Components\Tracking\OpenActivity}
     */
    public function getContactOpens($accessToken, $contact, $param = null)
    {
        $contactId = $this->getArgumentId($contact, 'Contact');
        $param = $this->determineParam($param);
        return $this->contactTrackingService->getOpens($accessToken, $contactId, $param);
    }

    /**
     * Get forwards for a Contact
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param mixed $contact  - Contact id or Contact object itself
     * @param mixed $param - either the next link from a previous request, or a limit or restrict the page size of
     * an initial request
     * @return ResultSet - Containing a results array of {@link Ctct\Components\Tracking\ForwardActivity}
     */
    public function getContactForwards($accessToken, $contact, $param = null)
    {
        $contactId = $this->getArgumentId($contact, 'Contact');
        $param = $this->determineParam($param);
        return $this->contactTrackingService->getForwards($accessToken, $contactId, $param);
    }

    /**
     * Get opt outs for a Contact
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param mixed $contact  - Contact id or Contact object itself
     * @param mixed $param - either the next link from a previous request, or a limit or restrict the page size of
     * an initial request
     * @return TrackingActivity - Containing a results array of {@link Ctct\Components\Tracking\UnsubscribeActivity}
     */
    public function getContactUnsubscribes($accessToken, $contact, $param = null)
    {
        $contactId = $this->getArgumentId($contact, 'Contact');
        $param = $this->determineParam($param);
        return $this->contactTrackingService->getUnsubscribes($accessToken, $contactId, $param);
    }

    /**
     * Get verified addresses for the account
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param string $status - Status to filter query results by
     * @return array of VerifiedEmailAddress objects
     */
    public function getVerifiedEmailAddresses($accessToken, $status = null)
    {
        $params = array();
        if ($status) {
            $params['status'] = $status;
        }
        return $this->accountService->getVerifiedEmailAddresses($accessToken, $params);
    }

    /**
     * Get a reporting summary for a Contact
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param mixed $contact  - Contact id or Contact object itself
     * @param string $next - next value returned from a previous request (used in pagination)
     * @param int $limit - number of results to return per page
     * @return TrackingSummary
     */
    public function getContactSummaryReport($accessToken, $contact)
    {
        $contactId = $this->getArgumentId($contact, 'Contact');
        return $this->contactTrackingService->getSummary($accessToken, $contactId);
    }

    /**
     * Get an array of activities
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @return array 
     */
    public function getActivities($accessToken)
    {
        return $this->activityService->getActivities($accessToken);
    }

    /**
     * Get a single activity by id
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param string $activityId - Activity id
     * @return array 
     */
    public function getActivity($accessToken, $activityId)
    {
        return $this->activityService->getActivity($accessToken, $activityId);
    }

    /**
     * Add an AddContacts Activity to add contacts in bulk
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param AddContacts - Add Contacts Activity
     */
    public function addCreateContactsActivity($accessToken, AddContacts $addContactsActivity)
    {
        return $this->activityService->createAddContactsActivity($accessToken, $addContactsActivity);
    }

    /**
     * Add an ClearLists Activity to remove all contacts from the provided lists
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param AddContacts - Add Contacts Activity
     */
    public function addClearListsActivity($accessToken, Array $lists)
    {
        return $this->activityService->addClearListsActivity($accessToken, $lists);
    }

    /**
     * Add a Remove Contacts From Lists Activity
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param array $emailAddresses - email addresses to be removed
     * @param array $lists - lists to remove the provided email addresses from
     * @param AddContacts - Add Contacts Activity
     */
    public function addRemoveContactsFromListsActivity($accessToken, Array $emailAddresses, Array $lists)
    {
        return $this->activityService->addRemoveContactsFromListsActivity($accessToken, $emailAddresses, $lists);
    }

     /**
     * Create an Export Contacts Activity
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param ExportContacts $exportContacts
     * @return array - Array of all ActivitySummaryReports
     */
    public function addExportContactsActivity($accessToken, ExportContacts $exportContacts)
    {
        return $this->activityService->addExportContactsActivity($accessToken, $exportContacts);
    }
    
    /**
     * Get the id of object, or attempt to convert the argument to an int
     * @param mixed $item - object or a numeric value
     * @param string $class_name - class name to test the given object against
     * @throws IllegalArgumentException - if the item is not an instance of the class name given, or cannot be
     * converted to a numeric value
     * @return int
     */
    private function getArgumentId($item, $class_name)
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

    /**
     * Builds an array of query parameters to be added to the request
     * @param string $param
     * @return array
     */
    private function determineParam($param)
    {
        $params = array();
        if (substr($param, 0, 1) === '?') {
            $param = substr($param, 1);
            parse_str($param, $params);
        } else {
            $params['limit'] = $param;
        }
        return $params;
    }
}
