<?php
namespace Ctct\Services;

use Ctct\Util\RestClient;
use Ctct\Util\Config;
use Ctct\Components\Tracking\BounceActivity;
use Ctct\Components\Tracking\TrackingActivity;
use Ctct\Components\Tracking\ClickActivity;
use Ctct\Components\Tracking\ForwardActivity;
use Ctct\Components\Tracking\OpenActivity;
use Ctct\Components\Tracking\UnsubscribeActivity;
use Ctct\Components\Tracking\SendActivity;
use Ctct\Components\Tracking\TrackingSummary;
use Ctct\Components\ResultSet;

/**
 * Performs all actions pertaining to Contact Tracking
 *
 * @package     Services
 * @author         Constant Contact
 */
class ContactTrackingService extends BaseService
{

    /**
     * Get bounces for a given contact
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $contact_id - Contact id
     * @param array $param - query params to be appended to request
     * @return ResultSet - Containing a results array of {@link BounceActivity}
     */
    public function getBounces($accessToken, $contact_id, Array $params = null)
    {
        $baseUrl = Config::get('endpoints.base_url') .
            sprintf(Config::get('endpoints.contact_tracking_bounces'), $contact_id);

        $url = $this->buildUrl($baseUrl, $params);

        $response = parent::getRestClient()->get($url, parent::getHeaders($accessToken));
        $body = json_decode($response->body, true);

        $bounces = array();
        foreach ($body['results'] as $bounceActivity) {
            $bounces[] = BounceActivity::create($bounceActivity);
        }

        return new ResultSet($bounces, $body['meta']);
    }

    /**
     * Get clicks for a given contact
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $contact_id - Contact id
     * @param array $param - query params to be appended to request
     * @return ResultSet - Containing a results array of {@link ClickActivity}
     */
    public function getClicks($accessToken, $contact_id, Array $params = null)
    {
        $baseUrl = Config::get('endpoints.base_url') .
            sprintf(Config::get('endpoints.contact_tracking_clicks'), $contact_id);
        
        $url = $this->buildUrl($baseUrl, $params);

        $response = parent::getRestClient()->get($url, parent::getHeaders($accessToken));
        $body = json_decode($response->body, true);

        $clicks = array();
        foreach ($body['results'] as $click_activity) {
            $clicks[] = ClickActivity::create($click_activity);
        }

        return new ResultSet($clicks, $body['meta']);
    }

    /**
     * Get forwards for a given contact
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $contact_id - Contact id
     * @param array $param - query params to be appended to request
     * @return ResultSet - Containing a results array of {@link ForwardActivity}
     */
    public function getForwards($accessToken, $contact_id, Array $params = null)
    {
        $baseUrl = Config::get('endpoints.base_url') .
            sprintf(Config::get('endpoints.contact_tracking_forwards'), $contact_id);
        
        $url = $this->buildUrl($baseUrl, $params);

        $response = parent::getRestClient()->get($url, parent::getHeaders($accessToken));
        $body = json_decode($response->body, true);
        $forwards = array();
        foreach ($body['results'] as $forward_activity) {
            $forwards[] = ForwardActivity::create($forward_activity);
        }

        return new ResultSet($forwards, $body['meta']);
    }

    /**
     * Get opens for a given contact
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $contact_id - Contact id
     * @param array $param - query params to be appended to request
     * @return ResultSet - Containing a results array of {@link OpenActivity}
     */
    public function getOpens($accessToken, $contact_id, Array $params = null)
    {
        $baseUrl = Config::get('endpoints.base_url') .
            sprintf(Config::get('endpoints.contact_tracking_opens'), $contact_id);
        
        $url = $this->buildUrl($baseUrl, $params);

        $response = parent::getRestClient()->get($url, parent::getHeaders($accessToken));
        $body = json_decode($response->body, true);
        $opens = array();
        foreach ($body['results'] as $open_activity) {
            $opens[] = OpenActivity::create($open_activity);
        }

        return new ResultSet($opens, $body['meta']);
    }

    /**
     * Get sends for a given contact
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $contact_id - Contact id
     * @param array $param - query params to be appended to request
     * @return ResultSet - Containing a results array of {@link SendActivity}
     */
    public function getSends($accessToken, $contact_id, Array $params = null)
    {
        $baseUrl = Config::get('endpoints.base_url') .
            sprintf(Config::get('endpoints.contact_tracking_sends'), $contact_id);
        
        $url = $this->buildUrl($baseUrl, $params);

        $response = parent::getRestClient()->get($url, parent::getHeaders($accessToken));
        $body = json_decode($response->body, true);
        $sends = array();
        foreach ($body['results'] as $send_activity) {
            $sends[] = SendActivity::create($send_activity);
        }

        return new ResultSet($sends, $body['meta']);
    }

    /**
     * Get unsubscribes for a given contact
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $contact_id - Contact id
     * @param array $param - query params to be appended to request
     * @return ResultSet - Containing a results array of {@link UnsubscribeActivity}
     */
    public function getUnsubscribes($accessToken, $contact_id, Array $params = null)
    {
        $baseUrl = Config::get('endpoints.base_url') .
            sprintf(Config::get('endpoints.contact_tracking_unsubscribes'), $contact_id);

        $url = $this->buildUrl($baseUrl, $params);
       
        $response = parent::getRestClient()->get($url, parent::getHeaders($accessToken));
        $body = json_decode($response->body, true);
        $opt_outs = array();
        foreach ($body['results'] as $opt_out_activity) {
            $opt_outs[] = UnsubscribeActivity::create($opt_out_activity);
        }

        return new ResultSet($opt_outs, $body['meta']);
    }

    /**
     * Get a summary of reporting data for a given contact
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $contact_id - Contact id
     * @return TrackingSummary
     */
    public function getSummary($accessToken, $contact_id)
    {
        $baseUrl = Config::get('endpoints.base_url') .
            sprintf(Config::get('endpoints.contact_tracking_summary'), $contact_id);
        $url = $this->buildUrl($baseUrl);
        $response = parent::getRestClient()->get($url, parent::getHeaders($accessToken));
        return TrackingSummary::create(json_decode($response->body, true));
    }
}
