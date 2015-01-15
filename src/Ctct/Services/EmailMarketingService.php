<?php
namespace Ctct\Services;

use Ctct\Exceptions\CtctException;
use Ctct\Util\Config;
use Ctct\Components\EmailMarketing\Campaign;
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
     * @param array $params - query params to be appended to the request
     * @return ResultSet
     * @throws CtctException
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
     * @param int $campaign_id - Valid campaign id
     * @return Campaign
     * @throws CtctException
     */
    public function getCampaign($accessToken, $campaign_id)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign'), $campaign_id);

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
     * @param int $campaign_id - Valid campaign id
     * @return boolean
     * @throws CtctException
     */
    public function deleteCampaign($accessToken, $campaign_id)
    {
        $baseUrl = Config::get('endpoints.base_url') . sprintf(Config::get('endpoints.campaign'), $campaign_id);

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
}
