<?php
namespace Ctct\Components\Account;

use Ctct\Components\Component;

/**
 * Represents account info associated with an access token in Constant Contact
 *
 * @package        Components
 * @subpackage     Account
 * @author         ewaltman
 */
class AccountInfo extends Component
{
    /**
     * Website associated with the account
     * @var string
     */
    public $website;

    /**
     * Name of organization associated with the account
     * @var string
     */
    public $organization_name;

    /**
     * Time zone used with the account
     * @var string
     */
    public $time_zone;

    /**
     * First name of the account user
     * @var string
     */
    public $first_name;

    /**
     * Last name of the account user
     * @var string
     */
    public $last_name;

    /**
     * Email address associated with the account
     * @var string
     */
    public $email;

    /**
     * Phone number associated with the account
     * @var string
     */
    public $phone;

    /**
     * URL of the company logo associated with the account
     * @var string
     */
    public $company_logo;

    /**
     * Country code associated with the account
     * @var string
     */
    public $country_code;

    /**
     * State code associated with the account
     * @var string
     */
    public $state_code;

    /**
     * Array of organization addresses associated with the account
     * @var array
     */
    public $organization_addresses;

    /**
     * Factory method to create an AccountInfo object from an array
     * @param array $props - associative array of initial properties to set
     * @return AccountInfo
     */
    public static function create(array $props)
    {
        $accountInfo = new AccountInfo();
        $accountInfo->website = parent::getValue($props, "website");
        $accountInfo->organization_name = parent::getValue($props, "organization_name");
        $accountInfo->time_zone = parent::getValue($props, "time_zone");
        $accountInfo->first_name = parent::getValue($props, "first_name");
        $accountInfo->last_name = parent::getValue($props, "last_name");
        $accountInfo->email = parent::getValue($props, "email");
        $accountInfo->phone = parent::getValue($props, "phone");
        $accountInfo->company_logo = parent::getValue($props, "company_logo");
        $accountInfo->country_code = parent::getValue($props, "country_code");
        $accountInfo->state_code = parent::getValue($props, "state_code");
        $accountInfo->organization_addresses = parent::getValue($props, "organization_addresses");

        return $accountInfo;
    }

    public function toJson() {
        return json_encode($this);
    }
}
