<?php
namespace Ctct\Services;

use Ctct\Util\Config;
use Ctct\Components\EmailMarketing\Campaign;
use Ctct\Components\ResultSet;

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
        $url = $this->buildUrl($baseUrl);
        $response = parent::getRestClient()->post($url, parent::getHeaders($accessToken), $campaign->toJson());
        return Campaign::create(json_decode($response->body, true));
    }

    /**
     * Get a set of campaigns
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param array $params - query params to be appended to the request
     * @return ResultSet
     */
    public function getCampaigns($accessToken, Array $params = null)
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.campaigns');
        $url = $this->buildUrl($baseUrl, $params);
        $response = parent::getRestClient()->get($url, parent::getHeaders($accessToken));
        $body = json_decode($response->body, true);
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
        $url = $this->buildUrl($baseUrl);
        $response = parent::getRestClient()->get($url, parent::getHeaders($accessToken));
        return Campaign::create(json_decode($response->body, true));
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
        $url = $this->buildUrl($baseUrl);
        $response = parent::getRestClient()->delete($url, parent::getHeaders($accessToken));
        return ($response->info['http_code'] == 204) ? true : false;
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
        $url = $this->buildUrl($baseUrl);
        $response = parent::getRestClient()->put($url, parent::getHeaders($accessToken), $campaign->toJson());
        return Campaign::create(json_decode($response->body, true));
    }
}
