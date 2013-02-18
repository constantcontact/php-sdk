<?php
namespace Ctct\Components;

/**
 * Container for a get on a collection, such as Contacts, Campaigns, or TrackingData.
 * 
 */
class ResultSet
{
    /**
     * array of result objects returned
     * @var array
     */
    public $results = array();

    /**
     * next link returned from a get on a collection if one exists
     * @var string
     */
    public $next;

    /**
     * Constructor to create a ResultSet from the results/meta response when performing a get on a collection
     * @param array $results - results array from request
     * @param array $meta - meta array from request
     */
    public function __construct(array $results, array $meta)
    {
        $this->results = $results;

        if (array_key_exists('next_link', $meta['pagination'])) {
            $nextLink = $meta['pagination']['next_link'];
            $this->next = substr($nextLink, strpos($nextLink, '?'));
        }
    }
}
