<?php
namespace Ctct\Components\EventSpot;

use Ctct\Components\Component;

/**
 * Represents a single Contact List
 *
 * @package        Components
 * @subpackage     Contacts
 * @author         Constant Contact
 */
class EventSpotList extends Component
{
	/**
	 * Unique identifier of the contact list
	 * @var string
	 */
	public $id;

	/**
	 * The event title, visible to registrants
	 * @var string (100)
	 */
	public $title;

	/**
	 * Status of the contact list, must be one of "DRAFT", "ACTIVE", "COMPLETE", "CANCELLED", "DELETED"
	 * @var string
	 */
	public $status;

	/**
	 * Number of event registrants
	 * @var integer
	 */
	public $total_registered_count;

	/**
	 * Date event was published or announced, in ISO-8601 format
	 * @var string
	 */
	public $active_date;

	/**
	 * Date the event was updated in ISO-8601 format
	 * @var string
	 */
	public $updated_date;

	/**
	 * Factory method to create a EventList object from an array
	 * @param array $props - Associative array of initial properties to set
	 * @return EventSpotList
	 */
	public static function create(array $props)
	{
		$event_list = new EventSpotList();
		$event_list->id = parent::getValue($props, "id");
		$event_list->title = parent::getValue($props, "title");
		$event_list->status = parent::getValue($props, "status");
		$event_list->total_registered_count = parent::getValue($props, "total_registered_count");
		$event_list->created_date = parent::getValue($props, "created_date");
		$event_list->updated_date = parent::getValue($props, "updated_date");
		return $event_list;
	}

	public function toJson()
	{
		return json_encode($this);
	}
}
