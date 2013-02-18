<?php
namespace Ctct\Services;

use Ctct\Util\RestClient;
use Ctct\Util\Config;
use Ctct\Components\EmailCampaigns\EmailCampaign;
use Ctct\Components\ResultSet;

/**
 * Performs all actions pertaining to Constant Contact Campaigns
 *
 * @package     Services
 * @author         Constant Contact
 */
class EmailCampaignService extends BaseService
{
    /**
     * Create a new campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param Campaign $campaign - Campign to be created
     * @return Campaign
     */
    public static function addCampaign($accessToken, EmailCampaign $campaign)
    {
        $url = Config::get('endpoints.base_url') . Config::get('endpoints.campaigns');
        $response = parent::getRestClient()->post($url, parent::getHeaders($accessToken), $campaign->toJson());
        return EmailCampaign::create(json_decode($response->body, true));
    }
    
    /**
     * Get a set of campaigns
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $param - query param to be appended to the request
     * @return ResultSet 
     */
    public static function getCampaigns($accessToken, $param = null)
    {
        $url = Config::get('endpoints.base_url') . Config::get('endpoints.campaigns');
        if ($param) {
            $url .= $param;
        }
        
        $response = parent::getRestClient()->get($url, parent::getHeaders($accessToken));
        $body = json_decode($response->body, true);
        $campaigns = array();
        foreach ($body['results'] as $contact) {
            $campaigns[] = EmailCampaign::createSummary($contact);
        }
        return new ResultSet($campaigns, $body['meta']);
    }

    /**
     * Get campaign details for a specific campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $campaign_id - Valid campaign id
     * @return Campaign
     */
    public static function getCampaign($accessToken, $campaign_id)
    {
        $url = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign'), $campaign_id);
        $response = parent::getRestClient()->get($url, parent::getHeaders($accessToken));
        return EmailCampaign::create(json_decode($response->body, true));
    }
    
    /**
     * Delete an email campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param int $campaign_id - Valid campaign id
     * @return boolean
     */
    public static function deleteCampaign($accessToken, $campaign_id)
    {
        $url = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign'), $campaign_id);
        $response = parent::getRestClient()->delete($url, parent::getHeaders($accessToken));
        return ($response->info['http_code'] == 204) ? true : false;
    }
    
    /**
     * Update a specific email campaign
     * @param string $accessToken - Constant Contact OAuth2 access token
     * @param Campaign $campaign - Campaign to be updated
     * @return Campaign
     */
    public static function updateCampaign($accessToken, EmailCampaign $campaign)
    {
        $url = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign'), $campaign->id);
        $response = parent::getRestClient()->put($url, parent::getHeaders($accessToken), $campaign->toJson());
        return EmailCampaign::create(json_decode($response->body, true));
    }
}
