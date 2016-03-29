<?php
namespace Ctct\Components\EventSpot;

use Ctct\Components\Component;

/**
 * Represents a single Contact List
 *
 * @package        Components
 * @subpackage     Contacts
 * @author         Katz Web Services, Inc.
 */
class EventFee extends Component
{
	/**
	 * Unique identifier of the contact list
	 * @var string (50) Unique ID for that fee
	 */
	public $id;

	/**
	 * Fee for registrations that occur prior to the event's early_fee_date
	 * @var string (100) Fee description displayed to event registrants, each label must be unique
	 */
	public $label;

	/**
	 * Specifies who the fee applies to:
	 *  BOTH - Fee applies to Registrants and Guests
	 *  REGISTRANTS - Fee applies to registrants only
	 *  GUESTS - Fee applies to guests only
	 * @var integer
	 */
	public $fee_scope;

	/**
	 * The fee amount
	 * @var float
	 */
	public $fee;

	/**
	 * The event title, visible to registrants
	 * @var string (100)
	 */
	public $early_fee;

	/**
	 * Fee for registrations that occur after the event's late_fee_date
	 * @var float
	 */
	public $late_fee;

	/**
	 * If true, fee is not displayed on registration page, and is available only to registrants who have a special promocode linked to this fee.
	 * @see RegistrantPromoCode
	 * @var bool
	 */
	public $has_restricted_access = false;

	/**
	 * Factory method to create a EventList object from an array
	 * @param array $props - Associative array of initial properties to set
	 * @return EventFee
	 */
	public static function create(array $props)
	{
		$event_fee = new EventFee( $props );
		$event_fee->id = parent::getValue($props, "id");
		$event_fee->label = parent::getValue($props, "label");
		$event_fee->fee = parent::getValue($props, "fee");
		$event_fee->fee_scope = parent::getValue($props, "fee_scope");
		$event_fee->early_fee = parent::getValue($props, "early_fee");
		$event_fee->late_fee = parent::getValue($props, "late_fee");
		$event_fee->has_restricted_access = parent::getValue($props, "has_restricted_access");

		return $event_fee;
	}

	public function toJson()
	{
		return json_encode($this);
	}
}
