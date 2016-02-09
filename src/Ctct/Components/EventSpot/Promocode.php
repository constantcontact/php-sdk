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
class Promocode extends Component
{
	/**
	 * Unique identifier of the contact list
	 * @var float
	 */
	public $total_discount;


	public $promo_code_info;

	/**
	 * Factory method to create a EventList object from an array
	 * @param array $props - Associative array of initial properties to set
	 * @return KWSEventList
	 */
	public static function create(array $props)
	{
		$event_list = new KWSEventList();
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
