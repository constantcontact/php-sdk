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
class GuestSection extends Component
{
	/**
	 * The section label
	 * @var string
	 */
	public $label;

	/**
	 * An array of the fields displayed on the event registration page, and the values the registrant entered.
	 * @var GuestSectionField[]
	 */
	public $fields = array();

	/**
	 * Factory method to create a EventList object from an array
	 * @param array $props - Associative array of initial properties to set
	 * @return GuestSection
	 */
	public static function create(array $props)
	{
		$guest_section = new GuestSection();

		$guest_section->label = parent::getValue($props, "label");

		if (isset($props['fields'])) {
			foreach ($props['fields'] as $field) {
				$guest_section->fields[] = GuestSectionField::create($field);
			}
		}
		return $guest_section;
	}
}
