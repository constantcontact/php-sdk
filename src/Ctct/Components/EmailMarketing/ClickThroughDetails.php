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
class ClickThroughDetails extends Component
{
    /**
     * the actual url that was clicked on
     * @var string
     */
    public $url;

    /**
     * url unique identifier
     * @var string
     */
    public $url_uid;

    /**
     * number of times the url was clicked on
     * @var int
     */
    public $click_count;

    /**
     * Factory method to create a ClickThroughDetails object from an array
     * @param array $props - associative array of initial properties to set
     * @return ClickThroughDetails
     */
    public static function create(array $props)
    {
        $click_through_details = new ClickThroughDetails();
        $click_through_details->url = parent::getValue($props, "url");
        $click_through_details->url_uid = parent::getValue($props, "url_uid");
        $click_through_details->click_count = parent::getValue($props, "click_count");
        return $click_through_details;
    }
}
