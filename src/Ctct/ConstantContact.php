<?php
namespace Ctct;

use Ctct\Services\AccountService;
use Ctct\Services\ActivityService;
use Ctct\Services\CampaignScheduleService;
use Ctct\Services\CampaignTrackingService;
use Ctct\Services\ContactService;
use Ctct\Services\ContactTrackingService;
use Ctct\Services\EmailMarketingService;
use Ctct\Services\LibraryService;
use Ctct\Services\ListService;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

/**
 * Exposes all implemented Constant Contact API functionality
 *
 * @package Ctct
 * @version 2.0.0
 * @author Constant Contact
 * @link https://developer.constantcontact.com
 */
class ConstantContact {
    /**
     * Handles interaction with contact management
     * @var ContactService
     */
    public $contactService;

    /**
     * Handles interaction with email marketing
     * @var EmailMarketingService
     */
    public $emailMarketingService;

    /**
     * Handles interaction with contact list management
     * @var ListService
     */
    public $listService;

    /**
     * ActivityService for handling interaction with bulk activities
     * @var ActivityService
     */
    public $activityService;

    /**
     * Handles interaction with email marketing tracking
     * @var CampaignTrackingService
     */
    public $campaignTrackingService;

    /**
     * Handles interaction with contact tracking
     * @var ContactTrackingService
     */
    public $contactTrackingService;

    /**
     * Handles interaction with email marketing campaign scheduling
     * @var CampaignScheduleService
     */
    public $campaignScheduleService;

    /**
     * Handles interaction with account management
     * @var AccountService
     */
    public $accountService;

    /**
     * Handles interaction with Library management
     * @var LibraryService
     */
    public $libraryService;

    /**
     * Class constructor
     * Registers the API key with the ConstantContact class that will be used for all API calls.
     * @param string $apiKey - Constant Contact API Key
     * @param ClientInterface|null $client - GuzzleHttp Client
     */
    public function __construct($apiKey, ClientInterface $client = null) {
        $client = $client ?: new Client();

        $this->contactService = new ContactService($apiKey, $client);
        $this->emailMarketingService = new EmailMarketingService($apiKey, $client);
        $this->activityService = new ActivityService($apiKey, $client);
        $this->campaignTrackingService = new CampaignTrackingService($apiKey, $client);
        $this->contactTrackingService = new ContactTrackingService($apiKey, $client);
        $this->campaignScheduleService = new CampaignScheduleService($apiKey, $client);
        $this->listService = new ListService($apiKey, $client);
        $this->accountService = new AccountService($apiKey, $client);
        $this->libraryService = new LibraryService($apiKey, $client);
    }
}
