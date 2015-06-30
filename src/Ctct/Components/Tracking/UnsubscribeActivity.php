<?php
namespace Ctct\Components\Tracking;

use Ctct\Components\Component;

/**
 * Represents a single Opt Out Activity
 *
 * @package     Components
 * @subpackage     CampaignTracking
 * @author         Constant Contact
 */
class UnsubscribeActivity extends Component
{
    public $activity_type;
    public $campaign_id;
    public $contact_id;
    public $email_address;
    public $unsubscribe_date;
    public $unsubscribe_source;
    public $unsubscribe_reason;

    /**
     * Factory method to create an OptOutActivity object from an array
     * @param array $props - array of properties to create object from
     * @return UnsubscribeActivity
     */
    public static function create(array $props)
    {
        $opt_out_activity = new UnsubscribeActivity();
        $opt_out_activity->activity_type = parent::getValue($props, "activity_type");
        $opt_out_activity->unsubscribe_date = parent::getValue($props, "unsubscribe_date");
        $opt_out_activity->unsubscribe_source = parent::getValue($props, "unsubscribe_source");
        $opt_out_activity->unsubscribe_reason = parent::getValue($props, "unsubscribe_reason");
        $opt_out_activity->contact_id = parent::getValue($props, "contact_id");
        $opt_out_activity->email_address = parent::getValue($props, "email_address");
        $opt_out_activity->campaign_id = parent::getValue($props, "campaign_id");
        return $opt_out_activity;
    }
}
