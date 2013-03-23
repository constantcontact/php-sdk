<?php
namespace Ctct\Components\Contacts;

use Ctct\Components\Component;

/**
 * Represents a single Address of a Contact
 *
 * @package        Components
 * @subpackage     Contacts
 * @author         Constant Contact
 */
class Address extends Component
{

    /**
     * Id of the address
     * @var string
     */
    public $id;

    /**
     * Line 1 of the address
     * @var string
     */
    public $line1;

    /**
     * Line 2 of the address
     * @var string
     */
    public $line2;

    /**
     * Line 3 of the address
     * @var string
     */
    public $line3;

    /**
     * City info for this address
     * @var string
     */
    public $city;

    /**
     * Address type, must be one of "BUSINESS", "PERSONAL", or "UNKNOWN"
     * @var string
     */
    public $address_type;

    /**
     * The state code for this address
     * @var string
     */
    public $state_code;

    /**
     * The country code for this address
     * @var string
     */
    public $country_code;

    /**
     * The postal code for this address
     * @var string
     */
    public $postal_code;

    /**
     * The sub postal code for this address
     * @var string
     */
    public $sub_postal_code;

    /**
     * Factory method to create an Address object from an array
     * @array $props - Associative array of initial properties to set
     * @return Address
     */
    public static function create(array $props)
    {
        $address = new Address();
        $address->id = parent::getValue($props, "id");
        $address->line1 = parent::getValue($props, "line1");
        $address->line2 = parent::getValue($props, "line2");
        $address->line3 = parent::getValue($props, "line3");
        $address->city = parent::getValue($props, "city");
        $address->address_type = parent::getValue($props, "address_type");
        $address->state_code = parent::getValue($props, "state_code");
        $address->country_code = parent::getValue($props, "country_code");
        $address->postal_code = parent::getValue($props, "postal_code");
        $address->sub_postal_code = parent::getValue($props, "sub_postal_code");
        return $address;
    }
}
