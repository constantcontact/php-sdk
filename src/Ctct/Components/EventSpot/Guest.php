<?php
namespace Ctct\Components\EventSpot;

use Ctct\Components\Component;

/**
 * Represents a single Contact List
 *
 * @package        Components
 * @subpackage     EventSpot
 * @author         Katz Web Services, Inc.
 */
class Guest extends Component
{
	/**
	 * Unique identifier of the contact list
	 * @var string
	 */
	public $guest_id;

	/**
	 * The event title, visible to registrants
	 * @var string (100)
	 */
	public $guest_section;

	/**
	 * Factory method to create a EventList object from an array
	 * @param array $props - Associative array of initial properties to set
	 * @return Guest
	 */
	public static function create(array $props)
	{
		$guest = new Guest();
		$guest->guest_id = parent::getValue($props, "guest_id");
		$guest->guest_section = GuestSection::create(parent::getValue($props, "guest_section"));
		return $guest;
	}
}
