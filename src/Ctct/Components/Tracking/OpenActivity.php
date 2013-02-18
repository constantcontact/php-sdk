<?php

namespace Ctct\Components\Tracking;

use Ctct\Components\Component;

/**
 * Represents an Open Activity
 *
 * @package     Components
 * @subpackage     CampaignTracking
 * @author         Constant Contact
 */
class OpenActivity extends Component
{
    public $activity_type;
    public $open_date;
    public $contact_id;
    public $email_address;
    public $campaign_id;

    /**
     * Factory method to create a OpenActivity object from an array
     * @param array $props - array of properties to create object from
     * @return OpenActivity
     */
    public static function create(array $props)
    {
        $open_activity = new OpenActivity();
        $open_activity->activity_type = parent::getValue($props, "activity_type");
        $open_activity->open_date = parent::getValue($props, "open_date");
        $open_activity->contact_id = parent::getValue($props, "contact_id");
        $open_activity->email_address = parent::getValue($props, "email_address");
        $open_activity->campaign_id = parent::getValue($props, "campaign_id");
        return $open_activity;
    }
}
