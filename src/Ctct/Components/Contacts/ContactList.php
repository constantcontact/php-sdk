<?php
namespace Ctct\Components\Contacts;

use Ctct\Components\Component;

/**
 * Represents a single Contact List
 *
 * @package        Components
 * @subpackage     Contacts
 * @author         Constant Contact
 */
class ContactList extends Component
{
    /**
     * Unique identifier of the contact list
     * @var string
     */
    public $id;

    /**
     * Name of the contact list
     * @var string
     */
    public $name;

    /**
     * Status of the contact list, must be one of "ACTIVE", "HIDDEN", "REMOVED"
     * @var string
     */
    public $status;

    /**
     * The number of contacts in the list
     * @var string
     */
    public $contact_count;

    /**
     * Date and time the list was created.
     * @var string
     */
    public $created_date;

    /**
     * Date and time the list was last modified.
     * @var string
     */
    public $modified_date;

    public function __construct($list_id = null)
    {
        if (!is_null($list_id)) {
            $this->id = $list_id;
        }

        return $this;
    }

    /**
     * Factory method to create a ContactList object from an array
     * @param array $props - Associative array of initial properties to set
     * @return ContactList
     */
    public static function create(array $props)
    {
        $contact_list = new ContactList();
        $contact_list->id = parent::getValue($props, "id");
        $contact_list->name = parent::getValue($props, "name");
        $contact_list->status = parent::getValue($props, "status");
        $contact_list->contact_count = parent::getValue($props, "contact_count");
        $contact_list->created_date = parent::getValue($props, "created_date");
        $contact_list->modified_date = parent::getValue($props, "modified_date");
        return $contact_list;
    }

    public function toJson()
    {
        return json_encode($this);
    }
}
