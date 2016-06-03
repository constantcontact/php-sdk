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
class GuestSectionField extends Component
{
	/**
	 * Type of field - Either single_value or multiple_values
	 * @var string
	 */
	public $type;

	/**
	 * Field label displayed on the event registration page, typical values are NAME_FIRST, NAME_LAST, EMAIL_ADDRESS
	 * @var string
	 */
	public $label;

	/**
	 * Name of the field
	 * @var string
	 */
	public $name;

	/**
	 * Value entered by registrant, used if field_typetype = single_value
	 * @var string
	 */
	public $value;

	/**
	 * An array of values entered by registrant, used if field_type = multiple_values
	 * @var array
	 */
	public $values;

	/**
	 * Factory method to create a EventList object from an array
	 * @param array $props - Associative array of initial properties to set
	 * @return GuestSectionField
	 */
	public static function create(array $props)
	{
		$guest_section_field = new GuestSectionField();

		$guest_section_field->type = parent::getValue($props, "type");
		$guest_section_field->name = parent::getValue($props, "name");
		$guest_section_field->label = parent::getValue($props, "label");
		$guest_section_field->value = parent::getValue($props, "value");
		$guest_section_field->values = parent::getValue($props, "values");

		return $guest_section_field;
	}
}
