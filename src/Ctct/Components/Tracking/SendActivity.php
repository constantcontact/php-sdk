<?php
namespace Ctct\Components\Tracking;

use Ctct\Components\Component;

/**
 * Represents a Sent Activity
 *
 * @package     Components
 * @subpackage     CampaignTracking
 * @author         Constant Contact
 */
class SendActivity extends Component
{
    public $activity_type;
    public $send_date;
    public $contact_id;
    public $email_address;
    public $campaign_id;

    /**
     * Factory method to create a SentActivity object from an array
     * @param array $props - array of properties to create object from
     * @return SendActivity
     */
    public static function create(array $props)
    {
        $sent_activity = new SendActivity();
        $sent_activity->activity_type = parent::getValue($props, "activity_type");
        $sent_activity->send_date = parent::getValue($props, "send_date");
        $sent_activity->contact_id = parent::getValue($props, "contact_id");
        $sent_activity->email_address = parent::getValue($props, "email_address");
        $sent_activity->campaign_id = parent::getValue($props, "campaign_id");
        return $sent_activity;
    }
}
