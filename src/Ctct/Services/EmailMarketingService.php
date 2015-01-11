<?php
namespace Ctct\Services;

use Ctct\Util\Config;
use Ctct\Components\EmailMarketing\Campaign;
use Ctct\Components\ResultSet;
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
     */
    public function addCampaign($accessToken, Campaign $campaign)
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.campaigns');

        $request = parent::createBaseRequest($accessToken, 'POST', $baseUrl);
        $stream = Stream::factory(json_encode($campaign));
        $request->setBody($stream);
        $response = parent::getClient()->send($request);

        return Campaign::create($response->json());
    }

    /**
     * Get a set of campaigns
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param array $params - query params to be appended to the request
     * @return ResultSet
     */
    public function getCampaigns($accessToken, Array $params)
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.campaigns');

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);
        if ($params) {
            $query = $request->getQuery();
            foreach ($params as $name => $value) {
                $query->add($name, $value);
            }
        }
        $response = parent::getClient()->send($request);

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
     * @param int $campaign_id - Valid campaign id
     * @return Campaign
     */
    public function getCampaign($accessToken, $campaign_id)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign'), $campaign_id);

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);
        $response = parent::getClient()->send($request);

        return Campaign::create($response->json());
    }

    /**
     * Delete an email campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $campaign_id - Valid campaign id
     * @return boolean
     */
    public function deleteCampaign($accessToken, $campaign_id)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign'), $campaign_id);

        $request = parent::createBaseRequest($accessToken, 'DELETE', $baseUrl);
        $response = parent::getClient()->send($request);

        return ($response->getStatusCode() == 204) ? true : false;
    }

    /**
     * Update a specific email campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param Campaign $campaign - Campaign to be updated
     * @return Campaign
     */
    public function updateCampaign($accessToken, Campaign $campaign)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign'), $campaign->id);

        $request = parent::createBaseRequest($accessToken, 'PUT', $baseUrl);
        $stream = Stream::factory(json_encode($campaign));
        $request->setBody($stream);
        $response = parent::getClient()->send($request);

        return Campaign::create($response->json());
    }
}
