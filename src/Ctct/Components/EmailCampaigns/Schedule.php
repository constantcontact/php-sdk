<?php
namespace Ctct\Components\EmailCampaigns;
 
use Ctct\Components\Component;

/**
 * Represents a campaign Schedule in Constant Contact
 *
 * @package     Components
 * @subpackage     Campaigns
 * @author         Constant Contact
 */
class Schedule extends Component
{
    /**
     * unique id of the schedule
     * @var string
     */
    public $schedule_id;
    
    /**
     * The scheduled start date/time in ISO 8601 format
     * @var string
     */
    public $scheduled_date;


    /**
     * Factory method to create a Schedule object from an array
     * @param array $props - associative array of initial properties to set
     * @return Schedule
     */
    public static function create(array $props)
    {
        $schedule = new Schedule();
        $schedule->schedule_id = parent::getValue($props, "schedule_id");
        $schedule->scheduled_date = parent::getValue($props, "scheduled_date");
        return $schedule;
    }
    
    /**
     * Create json used for a POST/PUT request, also handles removing attributes that will cause errors if sent 
     * @return string 
     */
    public function toJson()
    {
        return json_encode($this);
    }
}
