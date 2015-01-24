<?php
namespace Ctct\Services;

use Ctct\Exceptions\CtctException;
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
use GuzzleHttp\Exception\ClientException;

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
     * @param string $campaignId - Campaign id
     * @param array $params - associative array of query parameters and values to append to the request.
     *      Allowed parameters include:
     *      limit - Specifies the number of results displayed per page of output, from 1 - 500, default = 50.
     *      created_since - Used to retrieve a list of events since the date and time specified (in ISO-8601 format).
     *      next - the next link returned from a previous paginated call. May only be used by itself.
     * @return ResultSet - Containing a results array of {@link BounceActivity}
     * @throws CtctException
     */
    public function getBounces($accessToken, $campaignId, Array $params = array())
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign_tracking_bounces'), $campaignId);

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);
        if ($params) {
            $query = $request->getQuery();
            foreach ($params as $name => $value) {
                $query->add($name, $value);
            }
        }

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

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
     * @param array $params - associative array of query parameters and values to append to the request.
     *      Allowed parameters include:
     *      limit - Specifies the number of results displayed per page of output, from 1 - 500, default = 50.
     *      created_since - Used to retrieve a list of events since the date and time specified (in ISO-8601 format).
     *      next - the next link returned from a previous paginated call. May only be used by itself.
     * @return ResultSet - Containing a results array of {@link ClickActivity}
     * @throws CtctException
     */
    public function getClicks($accessToken, $campaignId, Array $params = array())
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign_tracking_clicks'), $campaignId);

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);
        if ($params) {
            $query = $request->getQuery();
            foreach ($params as $name => $value) {
                $query->add($name, $value);
            }
        }

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

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
     * @param array $params - associative array of query parameters and values to append to the request.
     *      Allowed parameters include:
     *      limit - Specifies the number of results displayed per page of output, from 1 - 500, default = 50.
     *      created_since - Used to retrieve a list of events since the date and time specified (in ISO-8601 format).
     *      next - the next link returned from a previous paginated call. May only be used by itself.
     * @return ResultSet - Containing a results array of {@link ForwardActivity}
     * @throws CtctException
     */
    public function getForwards($accessToken, $campaignId, Array $params = array())
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign_tracking_forwards'), $campaignId);

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);
        if ($params) {
            $query = $request->getQuery();
            foreach ($params as $name => $value) {
                $query->add($name, $value);
            }
        }

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

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
     * @param string $campaignId - Campaign id
     * @param array $params - associative array of query parameters and values to append to the request.
     *      Allowed parameters include:
     *      limit - Specifies the number of results displayed per page of output, from 1 - 500, default = 50.
     *      created_since - Used to retrieve a list of events since the date and time specified (in ISO-8601 format).
     *      next - the next link returned from a previous paginated call. May only be used by itself.
     * @return ResultSet - Containing a results array of {@link OpenActivity}
     * @throws CtctException
     */
    public function getOpens($accessToken, $campaignId, Array $params = array())
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign_tracking_opens'), $campaignId);

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);
        if ($params) {
            $query = $request->getQuery();
            foreach ($params as $name => $value) {
                $query->add($name, $value);
            }
        }

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

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
     * @param Array $params = array() - associative array of query parameters and values to append to the request.
     *      Allowed parameters include:
     *      limit - Specifies the number of results displayed per page of output, from 1 - 500, default = 50.
     *      created_since - Used to retrieve a list of events since the date and time specified (in ISO-8601 format).
     *      next - the next link returned from a previous paginated call. May only be used by itself.
     * @return TrackingActivity - Containing a results array of {@link SendActivity}
     * @throws CtctException
     */
    public function getSends($accessToken, $campaignId, Array $params = array())
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign_tracking_sends'), $campaignId);

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);
        if ($params) {
            $query = $request->getQuery();
            foreach ($params as $name => $value) {
                $query->add($name, $value);
            }
        }

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

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
     * @param array $params - associative array of query parameters and values to append to the request.
     *      Allowed parameters include:
     *      limit - Specifies the number of results displayed per page of output, from 1 - 500, default = 50.
     *      created_since - Used to retrieve a list of events since the date and time specified (in ISO-8601 format).
     *      next - the next link returned from a previous paginated call. May only be used by itself.
     * @return ResultSet - Containing a results array of {@link UnsubscribeActivity}
     * @throws CtctException
     */
    public function getUnsubscribes($accessToken, $campaignId, Array $params = array())
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign_tracking_unsubscribes'), $campaignId);

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);
        if ($params) {
            $query = $request->getQuery();
            foreach ($params as $name => $value) {
                $query->add($name, $value);
            }
        }

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

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
     * @param int $campaignId - Campaign id
     * @return TrackingSummary
     * @throws CtctException
     */
    public function getSummary($accessToken, $campaignId)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign_tracking_summary'), $campaignId);

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

        return TrackingSummary::create($response->json());
    }
}
