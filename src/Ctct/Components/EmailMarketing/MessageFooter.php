<?php
namespace Ctct\Components\EmailMarketing;

use Ctct\Components\Component;

/**
 * Represents a click through detail
 *
 * @package        EmailMarketing
 * @subpackage     Campaigns
 * @author         Constant Contact
 */
class MessageFooter extends Component
{
    public $city;
    public $state;
    public $country;
    public $organization_name;
    public $address_line_1;
    public $address_line_2;
    public $address_line_3;
    public $international_state;
    public $postal_code;
    public $include_forward_email;
    public $forward_email_link_text;
    public $include_subscribe_link;
    public $subscribe_link_text;

    /**
     * Factory method to create a MessageFooter object from an array
     * @param array $props - associative array of initial properties to set
     * @return MessageFooter
     */
    public static function create(array $props)
    {
        $message_footer = new MessageFooter();
        $message_footer->city = parent::getValue($props, "city");
        $message_footer->state = parent::getValue($props, "state");
        $message_footer->country = parent::getValue($props, "country");
        $message_footer->organization_name = parent::getValue($props, "organization_name");
        $message_footer->address_line_1 = parent::getValue($props, "address_line_1");
        $message_footer->address_line_2 = parent::getValue($props, "address_line_2");
        $message_footer->address_line_3 = parent::getValue($props, "address_line_3");
        $message_footer->international_state = parent::getValue($props, "international_state");
        $message_footer->postal_code = parent::getValue($props, "postal_code");
        $message_footer->include_forward_email = parent::getValue($props, "include_forward_email");
        $message_footer->forward_email_link_text = parent::getValue($props, "forward_email_link_text");
        $message_footer->include_subscribe_link = parent::getValue($props, "include_subscribe_link");
        $message_footer->subscribe_link_text = parent::getValue($props, "subscribe_link_text");

        return $message_footer;
    }
}
