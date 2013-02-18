<?php

namespace Ctct\Components\Tracking;

use Ctct\Components\Component;

/**
 * Represents a Tracking Summary
 *
 * @package     Components
 * @subpackage     Campaigns
 * @author         Constant Contact
 */
class TrackingSummary extends Component
{
    public $sends;
    public $opens;
    public $clicks;
    public $forwards;
    public $unsubscribes;
    public $bounces;

    /**
     * Factory method to create a TrackingSummary object from an array
     * @param array $props - array of properties to create object from
     * @return TrackingSummary
     */
    public static function create(array $props)
    {
        $tracking_summary = new TrackingSummary();
        $tracking_summary->sends = parent::getValue($props, "sends");
        $tracking_summary->opens = parent::getValue($props, "opens");
        $tracking_summary->clicks = parent::getValue($props, "clicks");
        $tracking_summary->forwards = parent::getValue($props, "forwards");
        $tracking_summary->unsubscribes = parent::getValue($props, "unsubscribes");
        $tracking_summary->bounces = parent::getValue($props, "bounces");
        return $tracking_summary;
    }
}
