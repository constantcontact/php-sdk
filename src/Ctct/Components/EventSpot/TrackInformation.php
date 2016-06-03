<?php
namespace Ctct\Components\EventSpot;

use Ctct\Components\Component;

/**
 * Defines the information displayed on the Event registration page
 *
 * @package        Components
 * @subpackage     EventSpot
 * @author         Katz Web Services, Inc.
 */
class TrackInformation extends Component
{

	/**
	 * Date on which early fees end, in ISO-8601 format
	 * @var string
	 */
	public $early_fee_date;

	/**
	 * Default = Guest(s); How guests are referred to on the registration form; use your own, or one of the following suggestions are Associate(s), Camper(s), Child(ren), Colleague(s), Employee(s), Friend(s), Guest(s), Member(s), Participant(s), Partner(s), Player(s), Spouse(s), Student(s), Teammate(s), Volunteer(s)
	 * @var string (50)
	 */
	public $guest_display_label;

	/**
	 * Number of guests each registrant can bring, 0 - 100, default = 0
	 * @var int
	 */
	public $guest_limit;

	/**
	 * Determines if the Who (CONTACT), When (TIME), or Where (LOCATION) information is shown on the Event page. Default settings are `CONTACT`, `TIME`, and `LOCATION` ; valid values are: `CONTACT` - displays the event contact information, `TIME` - displays the event date and time, `LOCATION` - displays the event location
	 * @var array
	 */
	public $information_sections;

	/**
	 * Default = false; Set to true to display the guest count field on the registration form; if true, `is_guest_name_required` must be set to false (default).
	 * @var boolean
	 */
	public $is_guest_anonymous_enabled;

	/**
	 * Default = false. Set to display guest name fields on registration form; if true, then `is_guest_anonymous_enabled` must be set false (default).
	 * @var boolean
	 */
	public $is_guest_name_required;

	/**
	 * Default = false; Manually closes the event registration when set to true, takes precedence over `registration_limit_date` and `registration_limit_count` settings
	 * @var boolean
	 */
	public $is_registration_closed_manually;

	/**
	 * Default = false; Set to true provide a link for registrants to retrieve an event ticket after they register.
	 * @var boolean
	 */
	public $is_ticketing_link_displayed;

	/**
	 * Date after which late fees apply, in ISO-8601 format
	 * @var string
	 */
	public $late_fee_date;

	/**
	 * Specifies the maximum number of registrants for the event
	 * @var integer
	 */
	public $registration_limit_count;

	/**
	 * Date when event registrations close, in ISO-8601 format
	 * @var string
	 */
	public $registration_limit_date;

	/**
	 * Factory method to create a NotificationOption object from an array
	 * @param array $props - Associative array of initial properties to set
	 * @return TrackInformation
	 */
	public static function create(array $props)
	{
		$track_information = new TrackInformation();
		$track_information->early_fee_date = parent::getValue($props, "early_fee_date");
		$track_information->guest_display_label = parent::getValue($props, "guest_display_label");
		$track_information->guest_limit = parent::getValue($props, "guest_limit");
		$track_information->information_sections = parent::getValue($props, "information_sections");
		$track_information->is_guest_anonymous_enabled = parent::getValue($props, "is_guest_anonymous_enabled");
		$track_information->is_guest_name_required = parent::getValue($props, "is_guest_name_required");
		$track_information->is_registration_closed_manually = parent::getValue($props, "is_registration_closed_manually");
		$track_information->is_ticketing_link_displayed = parent::getValue($props, "is_ticketing_link_displayed");
		$track_information->late_fee_date = parent::getValue($props, "late_fee_date");
		$track_information->registration_limit_count = parent::getValue($props, "registration_limit_count");
		$track_information->registration_limit_date = parent::getValue($props, "registration_limit_date");
		return $track_information;
	}
}
