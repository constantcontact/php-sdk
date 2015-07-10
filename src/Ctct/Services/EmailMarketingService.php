<?php
namespace Ctct\Services;

use Ctct\Exceptions\CtctException;
use Ctct\Util\Config;
use Ctct\Components\EmailMarketing\Campaign;
use Ctct\Components\EmailMarketing\CampaignPreview;
use Ctct\Components\ResultSet;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Stream\Stream;

/**
 * Performs all actions pertaining to Constant Contact Campaigns
 *
 * @package Services
 * @author Constant Contact
 */
class EmailMarketingService extends BaseService
{
    /**
     * Create a new campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param Campaign $campaign - Campaign to be created
     * @return Campaign
     * @throws CtctException
     */
    public function addCampaign($accessToken, Campaign $campaign)
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.campaigns');
        if ($campaign->message_footer == null) {
            // API doesn't work well with a null message footer, so omit it entirely.
            unset($campaign->message_footer);
        }

        $request = parent::createBaseRequest($accessToken, 'POST', $baseUrl);
        $stream = Stream::factory(json_encode($campaign));
        $request->setBody($stream);

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

        return Campaign::create($response->json());
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
    public function getCampaigns($accessToken, Array $params = array())
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.campaigns');

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
    public function getCampaign($accessToken, $campaignId)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign'), $campaignId);

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

        return Campaign::create($response->json());
    }

    /**
     * Delete an email campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $campaignId - Valid campaign id
     * @return boolean
     * @throws CtctException
     */
    public function deleteCampaign($accessToken, $campaignId)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign'), $campaignId);

        $request = parent::createBaseRequest($accessToken, 'DELETE', $baseUrl);

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
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
    public function updateCampaign($accessToken, Campaign $campaign)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign'), $campaign->id);

        $request = parent::createBaseRequest($accessToken, 'PUT', $baseUrl);
        $stream = Stream::factory(json_encode($campaign));
        $request->setBody($stream);

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

        return Campaign::create($response->json());
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

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);
        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

        return CampaignPreview::create($response->json());
    }
}
