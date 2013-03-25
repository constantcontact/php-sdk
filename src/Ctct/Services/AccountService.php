<?php
namespace Ctct\Services;

use Ctct\Util\RestClient;
use Ctct\Util\Config;
use Ctct\Components\Account\VerifiedEmailAddress;

/**
 * Performs all actions pertaining to scheduling Constant Contact Account's
 *
 * @package     Services
 * @author         Constant Contact
 */
class AccountService extends BaseService
{
    /**
     * Get all verified email addresses associated with an account
     * @param string $accessToken - Constant Contact OAuth2 Access Token
     * @param array $params - array of query parameters/values to append to the request
     * @return array of VerifiedEmailAddress 
     */
    public function getVerifiedEmailAddresses($accessToken, Array $params)
    {
        $baseUrl = Config::get('endpoints.base_url')
            . sprintf(Config::get('endpoints.account_verified_addresses'));

        $url = $this->buildUrl($baseUrl, $params);
        $response = parent::getRestClient()->get($url, parent::getHeaders($accessToken));
        $verifiedAddresses = array();
        
        foreach (json_decode($response->body, true) as $verifiedAddress) {
            $verifiedAddresses[] = VerifiedEmailAddress::create($verifiedAddress);
        }
        
        return $verifiedAddresses;
    }
}
