<?php
namespace Ctct\Components\EventSpot\Registrant;

use Ctct\Components\Component;

/**
 * Represents a single Contact List
 *
 * @package        Components
 * @subpackage     Contacts
 * @author         Constant Contact
 */
class RegistrantSection extends Component
{
	/**
	 * The section label
	 * @var string
	 */
	public $label;

	/**
	 * An array of the fields displayed on the event registration page, and the values the registrant entered.
	 * @var RegistrantSectionField[]
	 */
	public $fields;

	/**
	 * Factory method to create a EventList object from an array
	 * @param array $props - Associative array of initial properties to set
	 * @return RegistrantSection
	 */
	public static function create(array $props)
	{
		$registrant_section = new RegistrantSection();
		$registrant_section->label = parent::getValue($props, "label");

		if (isset($props['fields'])) {
			foreach ($props['fields'] as $field) {
				$registrant_section->fields[] = RegistrantSectionField::create($field);
			}
		}

		return $registrant_section;
	}
}
