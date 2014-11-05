<?php
namespace Ctct\Components\Tracking;

use Ctct\Components\Component;

/**
 * Represents a single Bounce Activity
 *
 * @package     Components
 * @subpackage     CampaignTracking
 * @author         Constant Contact
 */
class BounceActivity extends Component
{
    public $activity_type;
    public $bounce_code;
    public $bounce_description;
    public $bounce_message;
    public $bounce_date;
    public $contact_id;
    public $email_address;
    public $campaign_id;

    /**
     * Factory method to create a BounceActivity object from an array
     * @param array $props - array of properties to create object from
     * @return BounceActivity
     */
    public static function create(array $props)
    {
        $bounceActivity = new BounceActivity();
        $bounceActivity->activity_type = parent::getValue($props, "activity_type");
        $bounceActivity->bounce_code = parent::getValue($props, "bounce_code");
        $bounceActivity->bounce_description = parent::getValue($props, "bounce_description");
        $bounceActivity->bounce_message = parent::getValue($props, "bounce_message");
        $bounceActivity->bounce_date = parent::getValue($props, "bounce_date");
        $bounceActivity->contact_id = parent::getValue($props, "contact_id");
        $bounceActivity->email_address = parent::getValue($props, "email_address");
        $bounceActivity->campaign_id = parent::getValue($props, "campaign_id");
        return $bounceActivity;
    }
}
