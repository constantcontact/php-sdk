<?php
namespace Ctct\Components\Account;

use Ctct\Components\Component;

/**
 * Represents a single Verified Email Address in Constant Contact
 *
 * @package        Components
 * @subpackage     Account
 * @author         Constant Contact
 */
class VerifiedEmailAddress extends Component
{
    /**
     * Email Address associated with the account
     * @var string
     */
    public $email_address;

    /**
     * Status of the verified email address
     * @var string
     */
    public $status;

    /**
     * Factory method to create an VerifiedEmail object from an array
     * @param array $props - associative array of initial properties to set
     * @return VerifiedEmailAddress
     */
    public static function create(array $props)
    {
        $verifiedAddress = new VerifiedEmailAddress();
        $verifiedAddress->email_address = parent::getValue($props, "email_address");
        $verifiedAddress->status = parent::getValue($props, "status");
        return $verifiedAddress;
    }
}
