<?php
namespace Ctct\Components\EventSpot;

use Ctct\Components\Component;

/**
 * Represents a single Address of a Payment
 *
 * @package        Components
 * @subpackage     EventSpot
 * @author         Constant Contact
 */
class Address extends Component
{
    /**
     * City info for this address
     * @var string
     */
    public $city;

    /**
     * Country of the event location
     * @var string (128)
     */
    public $country;

    /**
     * Standard 2 letter ISO 3166-1 code of the country associated with the event address
     * @var string (2)
     */
    public $country_code;

    /**
     * Latitude coordinates of the event location
     * @var float
     */
    public $latitude;

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
     * Longitude coordinates of the event location
     * @var float
     */
    public $longitude;

    /**
     * Postal ZIP code for the event
     * @var string (25)
     */
    public $postal_code;

    /**
     * The state for this address (non-US/Canada)
     * @var string (50)
     */
    public $state;

    /**
     * The state code for this address
     * @var string (50)
     */
    public $state_code;


    /**
     * Factory method to create an Address object from an array
     * @param array $props - Associative array of initial properties to set
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
        $address->state = parent::getValue($props, "state");
        $address->country_code = parent::getValue($props, "country_code");
        $address->postal_code = parent::getValue($props, "postal_code");
        $address->sub_postal_code = parent::getValue($props, "sub_postal_code");
        return $address;
    }
}
