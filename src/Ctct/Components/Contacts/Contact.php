<?php
namespace Ctct\Components\Contacts;

use Ctct\Components\Component;

/**
 * Represents a single Contact in Constant Contact
 *
 * @package        Components
 * @subpackage     Contacts
 * @author         Constant Contact
 */
class Contact extends Component
{

    /**
     * Unique identifier for the contact
     * @var string
     */
    public $id;

    /**
     * Status of the contact, must be one of "ACTIVE", "UNCONFIRMED", "OPTOUT", "REMOVED", "NON_SUBSCRIBER", "VISITOR"
     * @var string
     */
    public $status;

    /**
     * First name of the contact
     * @var string
     */
    public $first_name;

    /**
     * Last name of the contact
     * @var string
     */
    public $last_name;

    /**
     * Whether or not the contact is confirmed
     * @var boolean
     */
    public $confirmed;

    /**
     * Contact source information
     * @var string
     */
    public $source;

    /**
     * Array of email addresses associated with this contact
     * @var EmailAddress[]
     */
    public $email_addresses = array();

    /**
     * The prefix name of the contact
     * @var string
     */
    public $prefix_name;

    /**
     * The job title of the contact
     * @var string
     */
    public $job_title;

    /**
     * Array of addresses associated with this contact
     * @var Address[]
     */
    public $addresses = array();

    /**
     * Array of notes associated with this contact
     * @var Note[]
     */
    public $notes = array();

    /**
     * Company name this contact works for
     * @var string
     */
    public $company_name;

    /**
     * Contact's home phone number
     * @var string
     */
    public $home_phone;

    /**
     * Contact's work phone number
     * @var string
     */
    public $work_phone;

    /**
     * Contact's cell phone number
     * @var string
     */
    public $cell_phone;

    /**
     * Contact's fax number
     * @var string
     */
    public $fax;

    /**
     * Array of custom fields associated with this contact
     * @var CustomField[]
     */
    public $custom_fields = array();

    /**
     * Array of contact lists this contact belongs to
     * @var ContactList[]
     */
    public $lists = array();

    /**
     * Date the contact was created
     * @var string
     */
    public $created_date;

    /**
     * Date the contact was last modified
     * @var string
     */
    public $modified_date;

    /**
     * Contact source details
     * @var string
     */
    public $source_details;

    /**
     * Factory method to create a Contact object from an array
     * @param array $props - Associative array of initial properties to set
     * @return Contact
     */
    public static function create(array $props)
    {
        $contact = new Contact();
        $contact->id = parent::getValue($props, "id");
        $contact->status = parent::getValue($props, "status");
        $contact->first_name = parent::getValue($props, "first_name");
        $contact->last_name = parent::getValue($props, "last_name");
        $contact->confirmed = parent::getValue($props, "confirmed");
        $contact->source = parent::getValue($props, "source");

        if (isset($props['email_addresses'])) {
            foreach ($props['email_addresses'] as $email_address) {
                $contact->email_addresses[] = EmailAddress::create($email_address);
            }
        }

        $contact->prefix_name = parent::getValue($props, "prefix_name");
        $contact->job_title = parent::getValue($props, "job_title");

        if (isset($props['addresses'])) {
            foreach ($props['addresses'] as $address) {
                $contact->addresses[] = Address::create($address);
            }
        }

        if (isset($props['notes'])) {
            foreach ($props['notes'] as $note) {
                $contact->notes[] = Note::create($note);
            }
        }

        $contact->company_name = parent::getValue($props, "company_name");
        $contact->home_phone = parent::getValue($props, "home_phone");
        $contact->work_phone = parent::getValue($props, "work_phone");
        $contact->cell_phone = parent::getValue($props, "cell_phone");
        $contact->fax = parent::getValue($props, "fax");

        if (isset($props['custom_fields'])) {
            foreach ($props['custom_fields'] as $custom_field) {
                $contact->custom_fields[] = CustomField::create($custom_field);
            }
        }

        if (isset($props['lists'])) {
          foreach ($props['lists'] as $contact_list) {
              $contact->lists[] = ContactList::create($contact_list);
          }
        }

        $contact->created_date = parent::getValue($props, "created_date");
        $contact->modified_date = parent::getValue($props, "modified_date");

        $contact->source_details = parent::getValue($props, "source_details");

        return $contact;
    }

    /**
     * Add a ContactList
     * @param mixed $contactList - ContactList object or contact list id
     */
    public function addList($contactList)
    {
        if (!$contactList instanceof ContactList) {
            $contactList = new ContactList($contactList);
        }

        $this->lists[] = $contactList;
    }

    /**
     * Add an EmailAddress
     * @param mixed $emailAddress - EmailAddress object or email address
     */
    public function addEmail($emailAddress)
    {
        if (!$emailAddress instanceof EmailAddress) {
            $emailAddress = new EmailAddress($emailAddress);
        }

        $this->email_addresses[] = $emailAddress;
    }

    /**
     * Add a custom field to the contact object
     * @param CustomField $customField - custom field to add to the contact
     */
    public function addCustomField(CustomField $customField)
    {
        $this->custom_fields[] = $customField;
    }

    /**
     * Add an address
     * @param Address $address - Address to add
     */
    public function addAddress(Address $address)
    {
        $this->addresses[] = $address;
    }

    public function toJson()
    {
        unset($this->last_update_date);
        return json_encode($this);
    }
}
