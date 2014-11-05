<?php
namespace Ctct\Components\Tracking;

use Ctct\Components\Component;

/**
 * Represents a Click Activity
 *
 * @package     Components
 * @subpackage     CampaignTracking
 * @author         Constant Contact
 */
class ClickActivity extends Component
{
    public $activity_type;
    public $campaign_id;
    public $contact_id;
    public $email_address;
    public $link_id;
    public $click_date;

    /**
     * Factory method to create a ClickActivity object from an array
     * @param array $props - array of properties to create object from
     * @return ClickActivity
     */
    public static function create(array $props)
    {
        $click_activity = new ClickActivity();
        $click_activity->activity_type = parent::getValue($props, "activity_type");
        $click_activity->campaign_id = parent::getValue($props, "campaign_id");
        $click_activity->contact_id = parent::getValue($props, "contact_id");
        $click_activity->email_address = parent::getValue($props, "email_address");
        $click_activity->link_id = parent::getValue($props, "link_id");
        $click_activity->click_date = parent::getValue($props, "click_date");
        return $click_activity;
    }
}
