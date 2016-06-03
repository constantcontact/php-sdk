<?php
namespace Ctct\Components\EventSpot;

use Ctct\Components\Component;

/**
 * Represents a method of notifications for an Event
 *
 * @package        Components
 * @subpackage     EventSpot
 * @author         Katz Web Services, Inc.
 */
class NotificationOption extends Component
{
	/**
	 * Set to true to send event notifications to the contact email_address, false for no notifications; default = false
	 * @var boolean
	 */
	public $is_opted_in;

	/**
	 * Specifies the type of notifications sent to the contact email_address, valid values: SO_REGISTRATION_NOTIFICATION - send notice for each registration (Default)
	 * @var string
	 */
	public $notification_type;

	/**
	 * Factory method to create a NotificationOption object from an array
	 * @param array $props - Associative array of initial properties to set
	 * @return NotificationOption
	 */
	public static function create(array $props)
	{
		$notification_option = new NotificationOption();
		$notification_option->is_opted_in = parent::getValue($props, "guest_id");
		$notification_option->notification_type = parent::getValue($props, "notification_type");
		return $notification_option;
	}
}
