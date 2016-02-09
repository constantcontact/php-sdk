<?php
namespace Ctct\Components\EventSpot\Registrant;

use Ctct\Components\Component;
use Ctct\Components\EventSpot\Promocode;
use Ctct\Components\EventSpot\Guest;
use Ctct\Components\EventSpot\PaymentSummary;


/**
 * Represents a single Contact List
 *
 * @package        Components
 * @subpackage     Contacts
 * @author         Katz Web Services, Inc.
 */
class Registrant extends Component
{
	/**
	 * Unique identifier of the contact list
	 * @var string
	 */
	public $id;

	/**
	 * The Registrant's attendance status, ATTENDED or NOT_ATTENDED
	 * @var string
	 */
	public $attendance_status;

	/**
	 * The Registrant's email address
	 * @var string
	 */
	public $email;

	/**
	 * The Registrant's first (given) name
	 * @var string
	 */
	public $first_name;

	/**
	 * The Registrant's last (family) name
	 * @var string
	 */
	public $last_name;

	/**
	 * Number of guests the registrant is bringing to the event
	 * @var integer
	 */
	public $guest_count;

	/**
	 * The Registrant's payment status
	 * @var string
	 */
	public $payment_status;

	/**
	 * Date the event was updated in ISO-8601 format
	 * @var string
	 */
	public $registration_date;

	/**
	 * Registrant's registration status, valid values are:
	 *  REGISTERED
	 *  CANCELLED - Registration was cancelled
	 *  ABANDONED - The registrant completed the registration form but did not complete the online payment process
	 *  through PayPal, ProPay or Authorize.net; abandoned registrants are not included in registration reports.
	 * @var string
	 */
	public $registration_status;

	/**
	 * Unique ID of the registrant's event ticket
	 * @var string
	 */
	public $ticket_id;

	/**
	 * Date the event was updated in ISO-8601 format
	 * @var string
	 */
	public $updated_date;

	/**
	 * Displays the payment_status and payment_type
	 *   payment_status - Registrant's payment status:
	 *      PENDING - default for cash (at door) or check payments
	 *      NA - default free event status
	 *      INCOMPLETE - default PayPal status
	 *      FAILED - PayPal payment failed
	 *      REFUNDED - Fee refunded to registrant (for PayPal only)
	 *      PAID
	 * @var array
	 */
	public $payment_summary;

	/**
	 * Displays registrant's personal information, grouped by the fields displayed on the event registration page
	 * @var RegistrantSection[]
	 */
	public $sections = array();

	/**
	 * Contains all the guest information fields and values, entered by the registrant on the event registration page.
	 * @var Guest[]
	 */
	public $guests = array();

	/**
	 * Factory method to create a EventList object from an array
	 * @param array $props - Associative array of initial properties to set
	 * @return Registrant
	 */
	public static function create(array $props)
	{
		$event_registrant = new Registrant();
		$event_registrant->attendance_status = parent::getValue($props, "attendance_status");
		$event_registrant->id = parent::getValue($props, "id");
		$event_registrant->registration_date = parent::getValue($props, "registration_date");
		$event_registrant->registration_status = parent::getValue($props, "registration_status");
		$event_registrant->ticket_id = parent::getValue($props, "ticket_id");
		$event_registrant->updated_date = parent::getValue($props, "updated_date");

		if ( isset( $props['payment_summary'] ) ) {
			$event_registrant->payment_summary = PaymentSummary::create( $props['payment_summary'] );
			$event_registrant->payment_status = $event_registrant->payment_summary->payment_status;
		} else {
			$event_registrant->payment_status = parent::getValue($props, "payment_status");
		}


		if ( !empty( $props['sections'] ) ) {
			foreach ($props['sections'] as $section) {
				$event_registrant->sections[] = RegistrantSection::create($section);
			}
		}

		$event_registrant->email = isset( $props['email'] ) ? parent::getValue($props, "email") : $event_registrant->getFieldValue('EMAIL_ADDRESS');
		$event_registrant->first_name = isset( $props['first_name'] ) ? parent::getValue($props, "first_name") : $event_registrant->getFieldValue('NAME_FIRST');
		$event_registrant->last_name = isset( $props['last_name'] ) ? parent::getValue($props, "last_name") : $event_registrant->getFieldValue('NAME_LAST');

		if (!empty($props['guests']) && isset( $props['guests']['guest_info'] ) ) {

			$event_registrant->guest_count = $props['guests']['guest_count'];

			foreach ($props['guests']['guest_info'] as $guest) {
				$event_registrant->guests[] = Guest::create($guest);
			}
		} else {
			$event_registrant->guest_count = parent::getValue($props, "guest_count");
		}

		return $event_registrant;
	}

	/**
	 * Get a field value from the RegistrantSection storage by field name
	 *
	 * @param string $fieldName Name of the field to get for the Registrant, like "NAME_LAST" or "CUSTOM1"
	 *
	 * @return string|array|null For single_value field types, returns string. For multiple_value field types, returns array. If not found, returns null
	 */
	public function getFieldValue( $fieldName ) {

		/** @var RegistrantSection $section */
		foreach ( $this->sections as $section ) {

			/** @var RegistrantSectionField $field */
			foreach ( $section->fields as $field ) {
				if( $fieldName === $field->name ) {
					return is_null( $field->values ) ? $field->value : $field->values;
				}
			}
		}

		return null;
	}

	public function toJson()
	{
		return json_encode($this);
	}
}
