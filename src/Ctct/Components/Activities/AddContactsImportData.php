<?php
namespace Ctct\Components\Activities;

use Ctct\Components\Component;
use Ctct\Components\Contacts\Address;
use Ctct\Components\Contacts\CustomField;

/**
 * Represents a single Activity in Constant Contact
 *
 * @package     Components
 * @subpackage     Activities
 * @author         Constant Contact
 */
class AddContactsImportData extends Component
{
    public $first_name;
    public $middle_name;
    public $last_name;
    public $job_title;
    public $company_name;
    public $work_phone;
    public $home_phone;

    public $email_addresses = array();
    public $addresses = array();
    public $custom_fields = array();

    /**
     * Factory method to create an Activity object from an array
     * @param array $props - associative array of initial properties to set
     */
    public function __construct(array $props = array())
    {
        foreach ($this as $property => $value) {
            $this->$property = parent::getValue($props, $property);
        }
    }

    public function addCustomField(CustomField $customField)
    {
        $this->custom_fields[] = $customField;
    }

    public function addAddress(Address $address)
    {
        if (isset($address->state)) {
            $address->state_code = $address->state;
            unset($address->state);
        }

        foreach ($address as $key => $value) {
            if ($value == null) {
                unset($address->$key);
            }
        }
        $this->addresses[] = $address;
    }

    public function addEmail($emailAddress)
    {
        $this->email_addresses[] = $emailAddress;
    }

    public function toJson()
    {
        return json_encode($this);
    }
}
