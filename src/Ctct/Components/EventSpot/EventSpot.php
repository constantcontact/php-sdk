<?php
namespace Ctct\Components\EventSpot;

use Ctct\Components\Component;

/**
 * Represents a single Event in Constant Contact
 *
 * @package        Components
 * @subpackage     Events
 * @author         Katz Web Services, Inc.
 */
class EventSpot extends Component
{
	/**
	 * Unique ID of the event
	 * @var string (26)
	 */
	public $id;

	/**
	 * Date event was published or announced, in ISO-8601 format
	 * @var string
	 */
	public $active_date;

	/**
	 * Address specifying the event location, used to determine event location on map if is_map_displayed set to true.
	 * @var Address
	 */
	public $address;

	/**
	 * Set to true allows registrants to view others who have registered for the event, default = false
	 * @var boolean
	 */
	public $are_registrants_public = false;

	/**
	 * Date the event was cancelled in ISO-8601 format
	 * @var string
	 */
	public $cancelled_date;

	/**
	 * The event host's contact information
	 * @var Contact
	 */
	public $contact;

	/**
	 * Date the event was created, in ISO-8601 format
	 * @var string
	 */
	public $created_date;

	/**
	 * Currency that the account will be paid in; although this is not a required field, it has a default value of USD.
	 * Valid values are: USD, CAD, AUD, CHF, CZK, DKK, EUR, GBP, HKD, HUF, ILS, JPY, MXN, NOK, NZD, PHP, PLN, SEK, SGD, THB, TWD
	 * @var string
	 */
	public $currency_type;

	/**
	 * Date the event was deleted, in ISO-8601 format
	 * @var string
	 */
	public $deleted_date;

	/**
	 * Provide a brief description of the event that will be visible on the event registration form and landing page
	 * @var string (350)
	 */
	public $description;

	/**
	 * The event end date, in ISO-8601 format
	 * @var string
	 */
	public $end_date;

	/**
	 * URL that points to the detailed description of that event, same as a GET on a specific event.
	 * @var string
	 */
	public $event_detail_url;

	/**
	 * Enter the Google analytics key if being used to track the event registration homepage
	 * @var string (20)
	 */
	public $google_analytics_key;

	/**
	 * Google merchant id to which payments are made; Google Checkout is not supported for new events, only valid on events created prior to October 2013.
	 * @var string (20)
	 */
	public $google_merchant_id;

	/**
	 * Set to true to display the event on the account's calendar; Default = true
	 * @var boolean
	 */
	public $is_calendar_displayed;

	/**
	 * Set to true to enable registrant check-in, and indicate that the registrant attended the event; default = false
	 * @var boolean
	 */
	public $is_checkin_available;

	/**
	 * Indicates if the event home/landing page is displayed for the event; set to true only if a landing page has been created for the event; default = false
	 * @var boolean
	 */
	public $is_home_page_displayed;

	/**
	 * Set to true to publish the event in external event directories such as SocialVents and EventsInAmerica; default = false
	 * @var boolean
	 */
	public $is_listed_in_external_directory;

	/**
	 * For future usage, Default = true
	 * @var boolean
	 */
	public $is_map_displayed;

	/**
	 * Set to true if this is an online event; default = false
	 * @var boolean
	 */
	public $is_virtual_event;

	/**
	 * Name of the venue or location at which the event is being held
	 * @var string (50)
	 */
	public $location;

	/**
	 * Specify keywords to improve search engine optimization (SEO) for the event; use commas to separate multiple keywords
	 * @var string (100)
	 */
	public $meta_data_tags;

	/**
	 * The event filename - not visible to registrants
	 * @var string (100)
	 */
	public $name;

	/**
	 * Define whether or not event notifications are sent to the contact email_address, and which notifications.
	 * @var NotificationOption
	 */
	public $notification_options;

	/**
	 * Online meeting details, REQUIRED if is_virtual_event is set to true
	 * @var OnlineMeeting
	 */
	public $online_meeting;

	/**
	 * Name to which registrants paying by check must make checks payable to; REQUIRED if 'CHECK' is selected as a payment option
	 * @var string (128)
	 */
	public $payable_to;

	/**
	 * Address to which checks will be sent. REQUIRED if CHECK is selected as a payment option
	 * @var Address
	 */
	public $payment_address;

	/**
	 * Specifies the payment options available to registrants. Multiple types allowed:
	 *   PAYPAL
	 *   GOOGLE_CHECKOUT - Not supported for new events as of October 2013
	 *   CHECK - if selected, payment_address and payable_to are REQUIRED
	 *   DOOR - payment is accepted at the door
	 * @var array
	 */
	public $payment_options;

	/**
	 * Email address linked to PayPal account to which payments will be made. REQUIRED if 'PAYPAL' is selected as a payment option
	 * @var string (128)
	 */
	public $paypal_account_email;

	/**
	 * For events that have a homepage configured (via the product GUI), the registration_url points to the event homepage, otherwise it points to the event registration page.
	 * @var string (250)
	 */
	public $registration_url;

	/**
	 * The event start date, in ISO-8601 format
	 * @var string
	 */
	public $start_date;

	/**
	 * The event status, valid values are:
	 *  DRAFT
	 *  ACTIVE - Event is published and publicly accessible
	 *  COMPLETE - Event has occurred and is complete
	 *  CANCELLED - Event is no long publicly accessible
	 *  DELETED
	 *  When an event is published, status transitions from DRAFT to ACTIVE.
	 *  When an event is cancelled, status transitions from ACTIVE to CANCELLED.
	 * @var string
	 */
	public $status;

	/**
	 * The theme_name defines the layout and style (including background and color) for the event invitation, home page, and Registration form, see Event Themes for a list of all available themes; default = Default
	 * @var string
	 */
	public $theme_name;

	/**
	 * Specify additional text to help describe the event time zone
	 * @var string (80)
	 */
	public $time_zone_description;

	/**
	 * Time zone in which the event occurs, to see time_zone_id values go here.
	 * @var string (40)
	 */
	public $time_zone_id;

	/**
	 * The event title, visible to registrants
	 * @var string (100)
	 */
	public $title;

	/**
	 * Number of event registrants
	 * @var integer
	 */
	public $total_registered_count;

	/**
	 * Use these settings to define the information displayed on the Event registration page
	 * @var array {
	 *  @type string $early_fee_date Date on which early fees end, in ISO-8601 format
	 *  @type string $guest_display_label (50) Default = Guest(s); How guests are referred to on the registration form; use your own, or one of the following suggestions are Associate(s), Camper(s), Child(ren), Colleague(s), Employee(s), Friend(s), Guest(s), Member(s), Participant(s), Partner(s), Player(s), Spouse(s), Student(s), Teammate(s), Volunteer(s)
	 *  @type integer $guest_limit Number of guests each registrant can bring, 0 - 100, default = 0
	 *  @type array $information_sections Determines if the Who (CONTACT), When (TIME), or Where (LOCATION) information is shown on the Event page. Default settings are CONTACT, TIME, and LOCATION ; valid values are
	 *   CONTACT - displays the event contact information
	 *   TIME - displays the event date and time
	 *   LOCATION - displays the event location
	 *  @type boolean $is_guest_anonymous_enabled Default = false; Set to true to display the guest count field on the registration form; if true, is_guest_name_required must be set to false (default).
	 *  @type boolean $is_guest_name_required Default = false. Set to display guest name fields on registration form; if true, then is_guest_anonymous_enabled must be set false (default).
	 *  @type boolean $is_registration_closed_manually Default = false; Manually closes the event registration when set to true, takes precedence over registration_limit_date and registration_limit_count settings
	 *  @type boolean $is_ticketing_link_displayed Default = false; Set to true provide a link for registrants to retrieve an event ticket after they register.
	 *  @type string $late_fee_date Date after which late fees apply, in ISO-8601 format
	 *  @type integer $registration_limit_count Specifies the maximum number of registrants for the event
	 *  @type string $registration_limit_date Date when event registrations close, in ISO-8601 format
	 * }
	 */
	public $track_information;

	/**
	 * The event's Twitter hashtag
	 * @var string (30)
	 */
	public $twitter_hash_tag;

	/**
	 * The event type, valid values are:
	 *  AUCTION, BIRTHDAY, BUSINESS_FINANCE_SALES
	 *  CLASSES_WORKSHOPS, COMPETITION_SPORTS
	 *  CONFERENCES_SEMINARS_FORUM, CONVENTIONS_TRADESHOWS_EXPOS
	 *  FESTIVALS_FAIRS, FOOD_WINE, FUNDRAISERS_CHARITIES
	 *  HOLIDAY, INCENTIVE_REWARD_RECOGNITION, MOVIES_FILM
	 *  MUSIC_CONCERTS, NETWORKING_CLUBS, PERFORMING_ARTS
	 *  OUTDOORS_RECREATION, RELIGION_SPIRITUALITY
	 *  SCHOOLS_REUNIONS_ALUMNI, PARTIES_SOCIAL_EVENTS_MIXERS
	 *  TRAVEL, WEBINAR_TELESEMINAR_TELECLASS
	 *  WEDDINGS, OTHER
	 * @var string
	 */
	public $type;

	/**
	 * Date the event was updated in ISO-8601 format
	 * @var string
	 */
	public $updated_date;

	/**
	 * Factory method to create an Event object from an array
	 * @param array $props - associative array of initial properties to set
	 * @return EventSpot
	 */
	public static function create(array $props)
	{
		$event = new EventSpot();
		$event->id = parent::getValue($props, "id");
		$event->active_date = parent::getValue($props, "active_date");
		if(!empty($props['address'])) {
			$event->address = Address::create( parent::getValue( $props, "address" ) );
		}
		$event->are_registrants_public = parent::getValue($props, "are_registrants_public");
		$event->cancelled_date = parent::getValue($props, "cancelled_date");
		if(!empty( $props['contact'])) {
			$event->contact = Contact::create( parent::getValue( $props, "contact" ) );
		}
		$event->created_date = parent::getValue($props, "created_date");
		$event->currency_type = parent::getValue($props, "currency_type");
		$event->deleted_date = parent::getValue($props, "deleted_date");
		$event->description = parent::getValue($props, "description");
		$event->end_date = parent::getValue($props, "end_date");
		$event->event_detail_url = parent::getValue($props, "event_detail_url");
		$event->google_analytics_key = parent::getValue($props, "google_analytics_key");
		$event->google_merchant_id = parent::getValue($props, "google_merchant_id");
		$event->is_calendar_displayed = parent::getValue($props, "is_calendar_displayed");
		$event->is_checkin_available = parent::getValue($props, "is_checkin_available");
		$event->is_home_page_displayed = parent::getValue($props, "is_home_page_displayed");
		$event->is_listed_in_external_directory = parent::getValue($props, "is_listed_in_external_directory");
		$event->is_map_displayed = parent::getValue($props, "is_map_displayed");
		$event->is_virtual_event = parent::getValue($props, "is_virtual_event");
		$event->location = parent::getValue($props, "location");
		$event->meta_data_tags = parent::getValue($props, "meta_data_tags");
		$event->name = parent::getValue($props, "name");
		if( parent::getValue( $props, "notification_options" ) ) {
			$event->notification_options = NotificationOption::create( parent::getValue( $props, "notification_options" ) );
		}
		$event->online_meeting = parent::getValue($props, "online_meeting");
		$event->payable_to = parent::getValue($props, "payable_to");
		$event->payable_to = parent::getValue($props, "payable_to");
		if( parent::getValue( $props, "payment_address" ) ) {
			$event->payment_address = Address::create( parent::getValue( $props, "payment_address" ) );
		}
		$event->payment_options = parent::getValue($props, "payment_options");
		$event->paypal_account_email = parent::getValue($props, "paypal_account_email");
		$event->registration_url = parent::getValue($props, "registration_url");
		$event->start_date = parent::getValue($props, "start_date");
		$event->status = parent::getValue($props, "status");
		$event->theme_name = parent::getValue($props, "theme_name");
		$event->time_zone_description = parent::getValue($props, "time_zone_description");
		$event->time_zone_id = parent::getValue($props, "time_zone_id");
		$event->title = parent::getValue($props, "title");
		$event->total_registered_count = parent::getValue($props, "total_registered_count");
		if( parent::getValue( $props, "track_information" ) ) {
			$event->track_information = TrackInformation::create( parent::getValue( $props, "track_information" ) );
		}
		$event->twitter_hash_tag = parent::getValue($props, "twitter_hash_tag");
		$event->type = parent::getValue($props, "type");
		$event->updated_date = parent::getValue($props, "updated_date");

		return $event;
	}

	public function toJson()
	{
		return json_encode($this);
	}
}
