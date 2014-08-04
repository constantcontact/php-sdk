<?php
namespace Ctct\Components\Tracking;

use Ctct\Components\Component;

/**
 * Represents a Forward Activity
 *
 * @package     Components
 * @subpackage     CampaignTracking
 * @author         Constant Contact
 */
class ForwardActivity extends Component
{
    public $activity_type;
    public $campaign_id;
    public $contact_id;
    public $email_address;
    public $forward_date;

    /**
     * Factory method to create a ForwardActivity object from an array
     * @param array $props - array of properties to create object from
     * @return ClickActivity
     */
    public static function create(array $props)
    {
        $forward_activity = new ForwardActivity();
        $forward_activity->activity_type = parent::getValue($props, "activity_type");
        $forward_activity->campaign_id = parent::getValue($props, "campaign_id");
        $forward_activity->contact_id = parent::getValue($props, "contact_id");
        $forward_activity->email_address = parent::getValue($props, "email_address");
        $forward_activity->forward_date = parent::getValue($props, "forward_date");

        return $forward_activity;
    }
}
