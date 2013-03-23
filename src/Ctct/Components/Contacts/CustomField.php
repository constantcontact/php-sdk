<?php
namespace Ctct\Components\Contacts;
 
use Ctct\Components\Component;

/**
 * Represents a single Custom Field for a Contact
 *
 * @package        Components
 * @subpackage     Contacts
 * @author         Constant Contact
 */
class CustomField extends Component
{
    
    /**
     * Name of the custom field
     * @var string
     */
    public $name;
    
    /**
     * Value of the custom field
     * @var string
     */
    public $value;

    /**
     * Factory method to create a CustomField object from an array
     * @param array $props - Associative array of initial properties to set
     * @return CustomField
     */
    public static function create(array $props)
    {
        $custom_field = new CustomField();
        $custom_field->name = parent::getValue($props, "name");
        $custom_field->value = parent::getValue($props, "value");
        return $custom_field;
    }
}
