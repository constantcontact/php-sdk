<?php
namespace Ctct\Components\EventSpot;

use Ctct\Components\Component;

/**
 * Represents the event Contact
 *
 * @package        Components
 * @subpackage     EventSpot
 * @author         Katz Web Services, Inc.
 */
class Contact extends Component
{
	/**
	 * @var string
	 */
	public $email_address;

	/**
	 * @var string (100)
	 */
	public $name;

	/**
	 * @var string (100)
	 */
	public $organization_name;

	/**
	 * @var string
	 */
	public $phone_number;

	/**
	 * Factory method to create a Contact object from an array
	 * @param array $props - Associative array of initial properties to set
	 * @return Contact
	 */
	public static function create(array $props)
	{
		$guest = new Contact();
		$guest->email_address = parent::getValue($props, "email_address");
		$guest->name = parent::getValue($props, "name");
		$guest->organization_name = parent::getValue($props, "organization_name");
		$guest->phone_number = parent::getValue($props, "phone_number");

		return $guest;
	}
}
