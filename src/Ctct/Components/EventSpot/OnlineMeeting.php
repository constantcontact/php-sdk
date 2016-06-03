<?php
namespace Ctct\Components\EventSpot;

use Ctct\Components\Component;

/**
 * Represents details for an online meeting Event
 *
 * @package        Components
 * @subpackage     EventSpot
 * @author         Katz Web Services, Inc.
 */
class OnlineMeeting extends Component
{
	/**
	 * Online meeting instructions, such as dial in number, password, etc
	 * @var string (2500)
	 */
	public $instructions;

	/**
	 * Meeting ID, if any, for the meeting
	 * @var string (50)
	 */
	public $provider_meeting_id;

	/**
	 * Specify the online meeting provider, such as WebEx
	 * @var string (20)
	 */
	public $provider_type;

	/**
	 * URL for online meeting. REQUIRED if Event `is_virtual_event` is set to true.
	 * @var string (250)
	 */
	public $url;

	/**
	 * Factory method to create a OnlineMeeting object from an array
	 * @param array $props - Associative array of initial properties to set
	 * @return OnlineMeeting
	 */
	public static function create(array $props)
	{
		$online_meeting = new OnlineMeeting();
		$online_meeting->is_opted_in = parent::getValue($props, "guest_id");
		$online_meeting->notification_type = parent::getValue($props, "notification_type");
		return $online_meeting;
	}
}
