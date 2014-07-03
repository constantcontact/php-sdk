<?php
namespace Ctct\Components\Tracking;

/**
 * Class to wrap a result set of individual activities (ie: OpensActivity, SendActivity)
 *
 * @package     Components
 * @subpackage     CampaignTracking
 * @author         Constant Contact
 */
class TrackingActivity
{
    public $results = array();
    public $next;

    /**
     * Constructor to create a TrackingActivity from the results/pagination response from getting a set of activities
     * @param array $results - results array from a tracking endpoint
     * @param array $pagination - pagination array returned from a tracking endpoint
     */
    public function __construct(array $results, array $pagination)
    {
        $this->results = $results;

        if (array_key_exists('next', $pagination)) {
            $this->next = substr($pagination['next'], strrpos($pagination['next'], '&next=') + 6);
        }
    }
}
