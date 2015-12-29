<?php
namespace Ctct\Services;

use Ctct\Components\EmailMarketing\Campaign;
use Ctct\Components\EmailMarketing\CampaignPreview;
use Ctct\Components\ResultSet;
use Ctct\Exceptions\CtctException;
use Ctct\Util\Config;
use GuzzleHttp\Exception\TransferException;

/**
 * Performs all actions pertaining to Constant Contact Campaigns
 *
 * @package Services
 * @author Constant Contact
 */
class EmailMarketingService extends BaseService {
    /**
     * Create a new campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param Campaign $campaign - Campaign to be created
     * @return Campaign
     * @throws CtctException
     */
    public function addCampaign($accessToken, Campaign $campaign) {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.campaigns');
        if ($campaign->message_footer == null) {
            // API doesn't work well with a null message footer, so omit it entirely.
            unset($campaign->message_footer);
        }

        try {
            $response = parent::sendRequestWithBody($accessToken, 'POST', $baseUrl, $campaign);
        } catch (TransferException $e) {
            throw parent::convertException($e);
        }

        return Campaign::create(json_decode($response->getBody(), true));
    }

    /**
     * Get a set of campaigns
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param array $params - associative array of query parameters and values to append to the request.
     *      Allowed parameters include:
     *      limit - Specifies the number of results displayed per page of output, from 1 - 500, default = 50.
     *      modified_since - ISO-8601 formatted timestamp.
     *      next - the next link returned from a previous paginated call. May only be used by itself.
     * @return ResultSet
     * @throws CtctException
     */
    public function getCampaigns($accessToken, Array $params = array()) {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.campaigns');

        try {
            $response = parent::sendRequestWithoutBody($accessToken, 'GET', $baseUrl, $params);
        } catch (TransferException $e) {
            throw parent::convertException($e);
        }

        $body = json_decode($response->getBody(), true);
        $campaigns = array();
        foreach ($body['results'] as $contact) {
            $campaigns[] = Campaign::createSummary($contact);
        }

        return new ResultSet($campaigns, $body['meta']);
    }

    /**
     * Get campaign details for a specific campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $campaignId - Valid campaign id
     * @return Campaign
     * @throws CtctException
     */
    public function getCampaign($accessToken, $campaignId) {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign'), $campaignId);

        try {
            $response = parent::sendRequestWithoutBody($accessToken, 'GET', $baseUrl);
        } catch (TransferException $e) {
            throw parent::convertException($e);
        }

        return Campaign::create(json_decode($response->getBody(), true));
    }

    /**
     * Delete an email campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $campaignId - Valid campaign id
     * @return boolean
     * @throws CtctException
     */
    public function deleteCampaign($accessToken, $campaignId) {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign'), $campaignId);

        try {
            $response = parent::sendRequestWithoutBody($accessToken, 'DELETE', $baseUrl);
        } catch (TransferException $e) {
            throw parent::convertException($e);
        }

        return ($response->getStatusCode() == 204) ? true : false;
    }

    /**
     * Update a specific email campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param Campaign $campaign - Campaign to be updated
     * @return Campaign
     * @throws CtctException
     */
    public function updateCampaign($accessToken, Campaign $campaign) {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign'), $campaign->id);

        try {
            $response = parent::sendRequestWithBody($accessToken, 'PUT', $baseUrl, $campaign);
        } catch (TransferException $e) {
            throw parent::convertException($e);
        }

        return Campaign::create(json_decode($response->getBody(), true));
    }

    /**
     * Get a preview of an email campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $campaignId - Valid campaign id
     * @return CampaignPreview
     * @throws CtctException
     */
    public function getPreview($accessToken, $campaignId) {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign_preview'), $campaignId);

        try {
            $response = parent::sendRequestWithoutBody($accessToken, 'GET', $baseUrl);
        } catch (TransferException $e) {
            throw parent::convertException($e);
        }

        return CampaignPreview::create(json_decode($response->getBody(), true));
    }
}
