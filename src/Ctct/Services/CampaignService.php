<?php
namespace Ctct\Services;

use Ctct\Util\RestClient;
use Ctct\Util\Config;
use Ctct\Components\Campaigns\Campaign;

/**
 * Performs all actions pertaining to Constant Contact Campaigns
 *
 * @package 	Services
 * @author 		Constant Contact
 */
class CampaignService extends BaseService{

	/**
	 * Create a new campaign
	 * @param string $access_token - Constant Contact OAuth2 access token
	 * @param Campaign $campaign - Campign to be created
	 * @return Campaign
	 */
	public static function addCampaign($access_token, Campaign $campaign)
	{
		$url = Config::get('endpoints.base_url') . Config::get('endpoints.campaigns');
		$response = parent::getRestClient()->post($url, parent::getHeaders($access_token), $campaign->to_json());
		return Campaign::create(json_decode($response->body, true));
	}
	
    /**
     * Get a set of campaigns
     * @param string $access_token - Constant Contact OAuth2 access token
     * @param int $offset - denotes the starting number for the result set
     * @param int $limit - denotes the number of results per set of results, limited to 50
     * @return array 
     */
    public static function getCampaigns($access_token, $offset = null, $limit = null)
    {
        $url = parent::paginateUrl(
           Config::get('endpoints.base_url') . Config::get('endpoints.campaigns'), $offset, $limit
       );

        $response = parent::getRestClient()->get($url, parent::getHeaders($access_token));

        $campaigns = array();
        foreach(json_decode($response->body, true) as $contact)
        {
           $campaigns[] = Campaign::create($contact);
        }
        return $campaigns;
    }

    /**
     * Get campaign details for a specific campaign
     * @param string $access_token - Constant Contact OAuth2 access token
     * @param int $campaign_id - Valid campaign id
     * @return Campaign
     */
    public static function getCampaign($access_token, $campaign_id)
    {
        $url = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign'), $campaign_id);
        $response = parent::getRestClient()->get($url, parent::getHeaders($access_token));
        return Campaign::create(json_decode($response->body, true));
    }
	
	/**
	 * Delete an email campaign
	 * @param string $access_token - Constant Contact OAuth2 access token
	 * @param int $campaign_id - Valid campaign id
	 * @return boolean
	 */
	public static function deleteCampaign($access_token, $campaign_id)
	{
		$url = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign'), $campaign_id);
        $response = parent::getRestClient()->delete($url, parent::getHeaders($access_token));
		return ($response->info['http_code'] == 204) ? true : false;
	}
	
	/**
	 * Update a specific email campaign
	 * @param string $access_token - Constant Contact OAuth2 access token
	 * @param Campaign $campaign - Campaign to be updated
	 * @return Campaign
	 */
	public static function updateCampaign($access_token, Campaign $campaign)
	{
		$url = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign'), $campaign->id);
        $response = parent::getRestClient()->put($url, parent::getHeaders($access_token), $campaign->to_json());
        return Campaign::create(json_decode($response->body, true));
	}
}
