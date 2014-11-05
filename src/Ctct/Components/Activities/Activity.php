<?php
namespace Ctct\Components\Activities;

use Ctct\Components\Component;

/**
 * Represents a single Activity in Constant Contact
 *
 * @package        Components
 * @subpackage     Activities
 * @author         Constant Contact
 */
class Activity extends Component
{
    public $id;
    public $type;
    public $status;
    public $start_date;
    public $finish_date;
    public $file_name;
    public $created_date;
    public $error_count;
    public $errors = array();
    public $warnings = array();
    public $contact_count;

    /**
     * Factory method to create an Activity object from an array
     * @param array $props - associative array of initial properties to set
     * @return Activity
     */
    public static function create(array $props)
    {
        $activity = new Activity();
        $activity->id = parent::getValue($props, "id");
        $activity->type = parent::getValue($props, "type");
        $activity->status = parent::getValue($props, "status");
        $activity->start_date = parent::getValue($props, "start_date");
        $activity->finish_date = parent::getValue($props, "finish_date");
        $activity->created_date = parent::getValue($props, "created_date");
        $activity->error_count = parent::getValue($props, "error_count");
        $activity->contact_count = parent::getValue($props, "contact_count");

        // set any errors that exist, otherwise destroy the property
        if (array_key_exists('errors', $props)) {
            foreach ($props['errors'] as $error) {
                $activity->errors[] = ActivityError::create($error);
            }
        } else {
            unset($activity->errors);
        }

        // set any warnings that exist, otherwise destroy the property
        if (array_key_exists('warnings', $props)) {
            foreach ($props['warnings'] as $error) {
                $activity->warnings[] = ActivityError::create($error);
            }
        } else {
            unset($activity->warnings);
        }

        // set the file name if exists
        if (array_key_exists('file_name', $props)) {
            $activity->file_name = $props['file_name'];
        } else {
            unset($activity->file_name);
        }

        return $activity;
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
