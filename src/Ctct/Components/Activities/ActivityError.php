<?php
namespace Ctct\Components\Activities;

use Ctct\Components\Component;

/**
 * Represents a single Activity Error in Constant Contact
 *
 * @package        Components
 * @subpackage     Activities
 * @author         Constant Contact
 */
class ActivityError extends Component
{
    public $message;
    public $line_number;
    public $email_address;

    /**
     * Factory method to create an  object from an array
     * @param array $props - associative array of initial properties to set
     * @return ActivityError
     */
    public static function create(array $props)
    {
        $activityError = new ActivityError();
        $activityError->message = parent::getValue($props, "message");
        $activityError->line_number = parent::getValue($props, "line_number");
        $activityError->email_address = parent::getValue($props, "email_address");
        return $activityError;
    }
}
