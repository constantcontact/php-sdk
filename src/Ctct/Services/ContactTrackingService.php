<?php
namespace Ctct\Services;

use Ctct\Util\RestClient;
use Ctct\Util\Config;
use Ctct\Components\Tracking\BounceActivity;
use Ctct\Components\Tracking\TrackingActivity;
use Ctct\Components\Tracking\ClickActivity;
use Ctct\Components\Tracking\ForwardActivity;
use Ctct\Components\Tracking\OpenActivity;
use Ctct\Components\Tracking\OptOutActivity;
use Ctct\Components\Tracking\SendActivity;
use Ctct\Components\Tracking\TrackingSummary;

/**
 * Performs all actions pertaining to Contact Tracking
 *
 * @package 	Services
 * @author 		Constant Contact
 */
class ContactTrackingService extends BaseService
{

    /**
     * Get bounces for a given contact
     * @param string $access_token - Constant Contact OAuth2 access token
     * @param int $contact_id - Contact id
     * @param string $next - next value returned from a previous request (used in pagination)
     * @param int $limit - number of results to return per page
     * @return TrackingActivity - Containing a results array of {@link BounceActivity}
     */
    public static function getBounces($access_token, $contact_id, $next, $limit)
    {
        $url = parent::paginateTrackingUrl(
            Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.contact_tracking_bounces'), $contact_id), $next, $limit
       );

        $response = parent::getRestClient()->get($url, parent::getHeaders($access_token));
        $body = json_decode($response->body, true);

        $bounces = array();
        foreach($body['results'] as $bounceActivity)
        {
            $bounces[] = BounceActivity::create($bounceActivity);
        }

        return new TrackingActivity($bounces, $body['meta']['pagination']);
    }

    /**
     * Get clicks for a given contact
     * @param string $access_token - Constant Contact OAuth2 access token
     * @param int $contact_id - Contact id
     * @param string $next - next value returned from a previous request (used in pagination)
     * @param int $limit - number of results to return per page
     * @return TrackingActivity - Containing a results array of {@link ClickActivity}
     */
    public static function getClicks($access_token, $contact_id, $next = null, $limit = null)
    {
        $url = parent::paginateTrackingUrl(
            Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.contact_tracking_clicks'), $contact_id), $next, $limit
       );

        $response = parent::getRestClient()->get($url, parent::getHeaders($access_token));
        $body = json_decode($response->body, true);

        $clicks = array();
        foreach($body['results'] as $click_activity)
        {
            $clicks[] = ClickActivity::create($click_activity);
        }

        return new TrackingActivity($clicks, $body['meta']['pagination']);
    }

    /**
     * Get forwards for a given contact
     * @param string $access_token - Constant Contact OAuth2 access token
     * @param int $contact_id - Contact id
     * @param string $next - next value returned from a previous request (used in pagination)
     * @param int $limit - number of results to return per page
     * @return TrackingActivity - Containing a results array of {@link ForwardActivity}
     */
    public static function getForwards($access_token, $contact_id, $next = null, $limit = null)
    {
        $url = parent::paginateTrackingUrl(
            Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.contact_tracking_forwards'), $contact_id), $next, $limit
       );

        $response = parent::getRestClient()->get($url, parent::getHeaders($access_token));
        $body = json_decode($response->body, true);
        $forwards = array();
        foreach($body['results'] as $forward_activity)
        {
            $forwards[] = ForwardActivity::create($forward_activity);
        }

        return new TrackingActivity($forwards, $body['meta']['pagination']);
    }

    /**
     * Get opens for a given contact
     * @param string $access_token - Constant Contact OAuth2 access token
     * @param int $contact_id - Contact id
     * @param string $next - next value returned from a previous request (used in pagination)
     * @param int $limit - number of results to return per page
     * @return TrackingActivity - Containing a results array of {@link OpenActivity}
     */
    public static function getOpens($access_token, $contact_id, $next = null, $limit = null)
    {
        $url = parent::paginateTrackingUrl(
            Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.contact_tracking_opens'), $contact_id), $next, $limit
       );

        $response = parent::getRestClient()->get($url, parent::getHeaders($access_token));
        $body = json_decode($response->body, true);
        $opens = array();
        foreach($body['results'] as $open_activity)
        {
            $opens[] = OpenActivity::create($open_activity);
        }

        return new TrackingActivity($opens, $body['meta']['pagination']);
    }

    /**
     * Get sends for a given contact
     * @param string $access_token - Constant Contact OAuth2 access token
     * @param int $contact_id - Contact id
     * @param string $next - next value returned from a previous request (used in pagination)
     * @param int $limit - number of results to return per page
     * @return TrackingActivity - Containing a results array of {@link SendActivity}
     */
    public static function getSends($access_token, $contact_id, $next = null, $limit = null)
    {
        $url = parent::paginateTrackingUrl(
            Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.contact_tracking_sends'), $contact_id), $next, $limit
       );

        $response = parent::getRestClient()->get($url, parent::getHeaders($access_token));
        $body = json_decode($response->body, true);
        $sends = array();
        foreach($body['results'] as $send_activity)
        {
            $sends[] = SendActivity::create($send_activity);
        }

        return new TrackingActivity($sends, $body['meta']['pagination']);
    }

    /**
     * Get opt outs for a given contact
     * @param string $access_token - Constant Contact OAuth2 access token
     * @param int $contact_id - Contact id
     * @param string $next - next value returned from a previous request (used in pagination)
     * @param int $limit - number of results to return per page
     * @return TrackingActivity - Containing a results array of {@link OptOutActivity}
     */
    public static function getOptOuts($access_token, $contact_id, $next = null, $limit = null)
    {
        $url = parent::paginateTrackingUrl(
            Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.contact_tracking_unsubscribes'), $contact_id), $next, $limit
       );

        $response = parent::getRestClient()->get($url, parent::getHeaders($access_token));
        $body = json_decode($response->body, true);
        $opt_outs = array();
        foreach($body['results'] as $opt_out_activity)
        {
            $opt_outs[] = OptOutActivity::create($opt_out_activity);
        }

        return new TrackingActivity($opt_outs, $body['meta']['pagination']);
    }

    /**
     * Get a summary of reporting data for a given contact
     * @param string $access_token - Constant Contact OAuth2 access token
     * @param int $contact_id - Contact id
     * @return TrackingSummary
     */
    public static function getSummary($access_token, $contact_id)
    {
        $url = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.contact_tracking_summary'), $contact_id);
        $response = parent::getRestClient()->get($url, parent::getHeaders($access_token));
        return TrackingSummary::create(json_decode($response->body, true));
    }

}
