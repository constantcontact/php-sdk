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
class RegistrantSectionField extends Component
{
	/**
	 * Type of field - Either single_value or multiple_values
	 * @var string
	 */
	public $type;

	/**
	 * Name of the field
	 * @var string
	 */
	public $name;

	/**
	 * Field label displayed on the event registration page, typical values are NAME_FIRST, NAME_LAST, EMAIL_ADDRESS
	 * @var string
	 */
	public $label;

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
	 * @return RegistrantSectionField
	 */
	public static function create(array $props)
	{
		$field = new RegistrantSectionField();
		$field->type = parent::getValue($props, "type");
		$field->name = parent::getValue($props, "name");
		$field->label = parent::getValue($props, "label");
		$field->value = parent::getValue($props, "value");
		$field->values = parent::getValue($props, "values");
		return $field;
	}

	public function toJson()
	{
		return json_encode($this);
	}
}
