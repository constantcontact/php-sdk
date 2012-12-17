<?php
namespace Ctct\Components\Contacts;
 
use Ctct\Components\Component;

/**
 * Represents a single Contact List
 *
 * @package 	Components
 * @subpackage 	Contacts
 * @author 		Constant Contact
 */ 
class ContactList extends Component{
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
     * Determines if the list is the default list for the account
     * @var boolean
     */
	public $opt_in_default;
	
	public function __construct($list_id = null)
	{
		if(!is_null($list_id))
		{
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
		$contact_list->opt_in_default = parent::getValue($props, "opt_in_default");
		return $contact_list;	
	}
	
	public function to_json()
	{
		return json_encode($this);
	}
}
