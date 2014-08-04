<?php
namespace Ctct\Components\Activities;

use Ctct\Components\Component;

/**
 * Represents an Export Contacts Activity in Constant Contact
 *
 * @package     Components
 * @subpackage     Activities
 * @author         Constant Contact
 */
class ExportContacts extends Component
{
    public $file_type = "CSV";
    public $sort_by = "EMAIL_ADDRESS";
    public $export_date_added = true;
    public $export_added_by = true;
    public $lists = array();
    public $column_names = array("Email Address", "First Name", "Last Name");

    /**
     * Constructor
     * @param array $lists - array of list id's to export from
     * @return ExportContacts
     */
    public function __construct(Array $lists = null)
    {
        if (!$lists == null) {
            $this->lists = $lists;
        }
    }

    /**
     * Create json used for a POST/PUT request, also handles removing attributes that will cause errors if sent
     * @return string
     */
    public function toJson()
    {
        return json_encode($this);
    }
}
