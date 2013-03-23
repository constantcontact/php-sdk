<?php
namespace Ctct\Components\Contacts;
 
use Ctct\Components\Component;
 
/**
 * Represents a single EmailAddress of a Contact
 *
 * @package        Components
 * @subpackage     Contacts
 * @author         Constant Contact
 */
class EmailAddress extends Component
{

    /**
     * Id of the email address
     * @var string
     */
    public $id;

    /**
     * Status of the email address, must be one of "ACTIVE", "UNCONFIRMED", "OPTOUT", "REMOVED", 
     * "NON_SUBSCRIBER", "VISITOR"
     * @var string
     */
    public $status;

    /**
     * Contact's confirmation status, must be one of "CONFIRMED", "NO_CONFIRMATION_REQUIRED", "UNCONFIRMED"
     * @var string
     */
    public $confirm_status;

    /**
     * Contact's opt in source, must be one of "ACTION_BY_VISITOR", "ACTION_BY_OWNER"
     * @var string
     */
    public $opt_in_source;

    /**
     * Contact's opt in date in ISO 8601 format
     * @var string
     */
    public $opt_in_date;

    /**
     * Contact's opt out date in ISO 8601 format
     * @var string
     */
    public $opt_out_date;

    /**
     * Email address associated with the contact
     * @var string
     */
    public $email_address;
    
    public function __construct($email_address = null)
    {
        if (!is_null($email_address)) {
            $this->email_address = $email_address;
        }
        
        return $this;
    }

    /**
     * Factory method to create an EmailAddress object from an array
     * @param array $props - Associative array of initial properties to set
     * @return EmailAddress
     */
    public static function create(array $props)
    {
        $email_address = new EmailAddress();
        $email_address->id = parent::getValue($props, "id");
        $email_address->status = parent::getValue($props, "status");
        $email_address->confirm_status = parent::getValue($props, "confirm_status");
        $email_address->opt_in_source = parent::getValue($props, "opt_in_source");
        $email_address->opt_in_date = parent::getValue($props, "opt_in_date");
        $email_address->opt_out_date = parent::getValue($props, "opt_out_date");
        $email_address->email_address = parent::getValue($props, "email_address");
        return $email_address;
    }
}
