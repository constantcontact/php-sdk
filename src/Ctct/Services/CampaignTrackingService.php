<?php
namespace Ctct\Services;

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
 * Performs all actions pertaining to Constant Contact Campaign Tracking
 *
 * @package Services
 * @author Constant Contact
 */
class CampaignTrackingService extends BaseService
{

    /**
     * Get a result set of bounces for a given campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param string $campaign_id - Campaign id
     * @param array $params - query parameters to be appended to the request
     * @return ResultSet - Containing a results array of {@link BounceActivity}
     */
    public function getBounces($accessToken, $campaign_id, Array $params)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign_tracking_bounces'), $campaign_id);

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);
        if ($params) {
            $query = $request->getQuery();
            foreach ($params as $name => $value) {
                $query->add($name, $value);
            }
        }
        $response = parent::getClient()->send($request);

        $body = $response->json();
        $bounces = array();
        foreach ($body['results'] as $bounceActivity) {
            $bounces[] = BounceActivity::create($bounceActivity);
        }
        return new ResultSet($bounces, $body['meta']);
    }

    /**
     * Get clicks for a given campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param string $campaignId - Campaign id
     * @param array $params - query params to be appended to request
     * @return ResultSet - Containing a results array of {@link ClickActivity}
     */
    public function getClicks($accessToken, $campaignId, Array $params)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign_tracking_clicks'), $campaignId);

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);
        if ($params) {
            $query = $request->getQuery();
            foreach ($params as $name => $value) {
                $query->add($name, $value);
            }
        }
        $response = parent::getClient()->send($request);

        $body = $response->json();
        $clicks = array();
        foreach ($body['results'] as $click_activity) {
            $clicks[] = ClickActivity::create($click_activity);
        }

        return new ResultSet($clicks, $body['meta']);
    }

    /**
     * Get forwards for a given campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param string $campaignId - Campaign id
     * @param array $params - query param to be appended to request
     * @return ResultSet - Containing a results array of {@link ForwardActivity}
     */
    public function getForwards($accessToken, $campaignId, Array $params)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign_tracking_forwards'), $campaignId);

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);
        if ($params) {
            $query = $request->getQuery();
            foreach ($params as $name => $value) {
                $query->add($name, $value);
            }
        }
        $response = parent::getClient()->send($request);

        $body = $response->json();
        $forwards = array();
        foreach ($body['results'] as $forward_activity) {
            $forwards[] = ForwardActivity::create($forward_activity);
        }

        return new ResultSet($forwards, $body['meta']);
    }

    /**
     * Get opens for a given campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param string $campaign_id - Campaign id
     * @param array $params - query params to be appended to request
     * @return ResultSet - Containing a results array of {@link OpenActivity}
     */
    public function getOpens($accessToken, $campaign_id, Array $params)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign_tracking_opens'), $campaign_id);

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);
        if ($params) {
            $query = $request->getQuery();
            foreach ($params as $name => $value) {
                $query->add($name, $value);
            }
        }
        $response = parent::getClient()->send($request);

        $body = $response->json();
        $opens = array();
        foreach ($body['results'] as $open_activity) {
            $opens[] = OpenActivity::create($open_activity);
        }

        return new ResultSet($opens, $body['meta']);
    }

    /**
     * Get sends for a given campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param string $campaignId - Campaign id
     * @param Array $params - query params to be appended to request
     * @return TrackingActivity - Containing a results array of {@link SendActivity}
     */
    public function getSends($accessToken, $campaignId, Array $params)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign_tracking_sends'), $campaignId);

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);
        if ($params) {
            $query = $request->getQuery();
            foreach ($params as $name => $value) {
                $query->add($name, $value);
            }
        }
        $response = parent::getClient()->send($request);

        $body = $response->json();
        $sends = array();
        foreach ($body['results'] as $send_activity) {
            $sends[] = SendActivity::create($send_activity);
        }

        return new ResultSet($sends, $body['meta']);
    }

    /**
     * Get unsubscribes for a given campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param string $campaignId - Campaign id
     * @param array $params - query params to be appended to request
     * @return ResultSet - Containing a results array of {@link UnsubscribeActivity}
     */
    public function getUnsubscribes($accessToken, $campaignId, Array $params)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign_tracking_unsubscribes'), $campaignId);

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);
        if ($params) {
            $query = $request->getQuery();
            foreach ($params as $name => $value) {
                $query->add($name, $value);
            }
        }
        $response = parent::getClient()->send($request);

        $body = $response->json();
        $optOuts = array();
        foreach ($body['results'] as $opt_out_activity) {
            $optOuts[] = UnsubscribeActivity::create($opt_out_activity);
        }

        return new ResultSet($optOuts, $body['meta']);
    }

    /**
     * Get a summary of reporting data for a given campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $campaign_id - Campaign id
     * @return TrackingSummary
     */
    public function getSummary($accessToken, $campaign_id)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign_tracking_summary'), $campaign_id);

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);
        $response = parent::getClient()->send($request);

        return TrackingSummary::create($response->json());
    }
}
