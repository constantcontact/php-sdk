<?php
namespace Ctct\Services;

use Ctct\Components\ResultSet;
use Ctct\Components\Tracking\BounceActivity;
use Ctct\Components\Tracking\ClickActivity;
use Ctct\Components\Tracking\ForwardActivity;
use Ctct\Components\Tracking\OpenActivity;
use Ctct\Components\Tracking\SendActivity;
use Ctct\Components\Tracking\TrackingSummary;
use Ctct\Components\Tracking\UnsubscribeActivity;
use Ctct\Exceptions\CtctException;
use Ctct\Util\Config;
use GuzzleHttp\Exception\TransferException;

/**
 * Performs all actions pertaining to Contact Tracking
 *
 * @package Services
 * @author Constant Contact
 */
class ContactTrackingService extends BaseService {
    /**
     * Get bounces for a given contact
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param string $contactId - Contact id
     * @param array $params - associative array of query parameters and values to append to the request.
     *      Allowed parameters include:
     *      limit - Specifies the number of results displayed per page of output, from 1 - 500, default = 50.
     *      created_since - Used to retrieve a list of events since the date and time specified (in ISO-8601 format).
     *      next - the next link returned from a previous paginated call. May only be used by itself.
     * @return ResultSet - Containing a results array of {@link BounceActivity}
     * @throws CtctException
     */
    public function getBounces($accessToken, $contactId, Array $params = array()) {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.contact_tracking_bounces'), $contactId);

        try {
            $response = parent::sendRequestWithoutBody($accessToken, 'GET', $baseUrl, $params);
        } catch (TransferException $e) {
            throw parent::convertException($e);
        }

        $body = json_decode($response->getBody(), true);
        $bounces = array();
        foreach ($body['results'] as $bounceActivity) {
            $bounces[] = BounceActivity::create($bounceActivity);
        }

        return new ResultSet($bounces, $body['meta']);
    }

    /**
     * Get clicks for a given contact
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param string $contactId - Contact id
     * @param array $params - associative array of query parameters and values to append to the request.
     *      Allowed parameters include:
     *      limit - Specifies the number of results displayed per page of output, from 1 - 500, default = 50.
     *      created_since - Used to retrieve a list of events since the date and time specified (in ISO-8601 format).
     *      next - the next link returned from a previous paginated call. May only be used by itself.
     * @return ResultSet - Containing a results array of {@link ClickActivity}
     * @throws CtctException
     */
    public function getClicks($accessToken, $contactId, Array $params = array()) {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.contact_tracking_clicks'), $contactId);

        try {
            $response = parent::sendRequestWithoutBody($accessToken, 'GET', $baseUrl, $params);
        } catch (TransferException $e) {
            throw parent::convertException($e);
        }

        $body = json_decode($response->getBody(), true);
        $clicks = array();
        foreach ($body['results'] as $click_activity) {
            $clicks[] = ClickActivity::create($click_activity);
        }

        return new ResultSet($clicks, $body['meta']);
    }

    /**
     * Get forwards for a given contact
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param string $contactId - Contact id
     * @param array $params - associative array of query parameters and values to append to the request.
     *      Allowed parameters include:
     *      limit - Specifies the number of results displayed per page of output, from 1 - 500, default = 50.
     *      created_since - Used to retrieve a list of events since the date and time specified (in ISO-8601 format).
     *      next - the next link returned from a previous paginated call. May only be used by itself.
     * @return ResultSet - Containing a results array of {@link ForwardActivity}
     * @throws CtctException
     */
    public function getForwards($accessToken, $contactId, Array $params = array()) {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.contact_tracking_forwards'), $contactId);

        try {
            $response = parent::sendRequestWithoutBody($accessToken, 'GET', $baseUrl, $params);
        } catch (TransferException $e) {
            throw parent::convertException($e);
        }

        $body = json_decode($response->getBody(), true);
        $forwards = array();
        foreach ($body['results'] as $forward_activity) {
            $forwards[] = ForwardActivity::create($forward_activity);
        }

        return new ResultSet($forwards, $body['meta']);
    }

    /**
     * Get opens for a given contact
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param string $contactId - Contact id
     * @param array $params - associative array of query parameters and values to append to the request.
     *      Allowed parameters include:
     *      limit - Specifies the number of results displayed per page of output, from 1 - 500, default = 50.
     *      created_since - Used to retrieve a list of events since the date and time specified (in ISO-8601 format).
     *      next - the next link returned from a previous paginated call. May only be used by itself.
     * @return ResultSet - Containing a results array of {@link OpenActivity}
     * @throws CtctException
     */
    public function getOpens($accessToken, $contactId, Array $params = array()) {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.contact_tracking_opens'), $contactId);

        try {
            $response = parent::sendRequestWithoutBody($accessToken, 'GET', $baseUrl, $params);
        } catch (TransferException $e) {
            throw parent::convertException($e);
        }

        $body = json_decode($response->getBody(), true);
        $opens = array();
        foreach ($body['results'] as $open_activity) {
            $opens[] = OpenActivity::create($open_activity);
        }

        return new ResultSet($opens, $body['meta']);
    }

    /**
     * Get sends for a given contact
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param string $contactId - Contact id
     * @param array $params - associative array of query parameters and values to append to the request.
     *      Allowed parameters include:
     *      limit - Specifies the number of results displayed per page of output, from 1 - 500, default = 50.
     *      created_since - Used to retrieve a list of events since the date and time specified (in ISO-8601 format).
     *      next - the next link returned from a previous paginated call. May only be used by itself.
     * @return ResultSet - Containing a results array of {@link SendActivity}
     * @throws CtctException
     */
    public function getSends($accessToken, $contactId, Array $params = array()) {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.contact_tracking_sends'), $contactId);

        try {
            $response = parent::sendRequestWithoutBody($accessToken, 'GET', $baseUrl, $params);
        } catch (TransferException $e) {
            throw parent::convertException($e);
        }

        $body = json_decode($response->getBody(), true);
        $sends = array();
        foreach ($body['results'] as $send_activity) {
            $sends[] = SendActivity::create($send_activity);
        }

        return new ResultSet($sends, $body['meta']);
    }

    /**
     * Get unsubscribes for a given contact
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param string $contactId - Contact id
     * @param array $params - associative array of query parameters and values to append to the request.
     *      Allowed parameters include:
     *      limit - Specifies the number of results displayed per page of output, from 1 - 500, default = 50.
     *      created_since - Used to retrieve a list of events since the date and time specified (in ISO-8601 format).
     *      next - the next link returned from a previous paginated call. May only be used by itself.
     * @return ResultSet - Containing a results array of {@link UnsubscribeActivity}
     * @throws CtctException
     */
    public function getUnsubscribes($accessToken, $contactId, Array $params = array()) {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.contact_tracking_unsubscribes'), $contactId);

        try {
            $response = parent::sendRequestWithoutBody($accessToken, 'GET', $baseUrl, $params);
        } catch (TransferException $e) {
            throw parent::convertException($e);
        }

        $body = json_decode($response->getBody(), true);
        $opt_outs = array();
        foreach ($body['results'] as $opt_out_activity) {
            $opt_outs[] = UnsubscribeActivity::create($opt_out_activity);
        }

        return new ResultSet($opt_outs, $body['meta']);
    }

    /**
     * Get a summary of reporting data for a given contact
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param string $contactId - Contact id
     * @return TrackingSummary
     * @throws CtctException
     */
    public function getSummary($accessToken, $contactId) {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.contact_tracking_summary'), $contactId);

        try {
            $response = parent::sendRequestWithoutBody($accessToken, 'GET', $baseUrl);
        } catch (TransferException $e) {
            throw parent::convertException($e);
        }

        return TrackingSummary::create(json_decode($response->getBody(), true));
    }
}
