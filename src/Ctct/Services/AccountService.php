<?php
namespace Ctct\Services;

use Ctct\Util\Config;
use Ctct\Components\Account\VerifiedEmailAddress;
use Ctct\Components\Account\AccountInfo;

/**
 * Performs all actions pertaining to scheduling Constant Contact Account's
 *
 * @package Services
 * @author Constant Contact
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

    /**
     * Create new verified email addresses. This will also prompt the account to send
     * a verification email to the address.
     * @param string $accessToken - Constant Contact OAuth2 Access Token
     * @param array $emailAddresses - array of VerifiedEmailAddress to create
     * @return array - array of VerifiedEmailAddress created
     */
    public function createVerifiedEmailAddresses($accessToken, Array $emailAddresses)
    {
        $baseUrl = Config::get('endpoints.base_url')
            . sprintf(Config::get('endpoints.account_verified_addresses'));

        $url = $this->buildUrl($baseUrl);
        $response = parent::getRestClient()->post($url, parent::getHeaders($accessToken), $emailAddresses);
        $verifiedAddresses = array();

        foreach (json_decode($response->body, true) as $verifiedAddress) {
            $verifiedAddresses[] = VerifiedEmailAddress::create($verifiedAddress);
        }

        return $verifiedAddresses;
    }

    /**
     * Get account info associated with an access token
     * @param string $accessToken - Constant Contact OAuth2 Access Token
     * @param array $params - array of query parameters/values to append to the request
     * @return AccountInfo
     */
    public function getAccountInfo($accessToken, Array $params)
    {
        $baseUrl = Config::get('endpoints.base_url')
            . sprintf(Config::get('endpoints.account_info'));

        $url = $this->buildUrl($baseUrl, $params);
        $response = parent::getRestClient()->get($url, parent::getHeaders($accessToken));
        return AccountInfo::create(json_decode($response->body, true));
    }

    /**
     * Update information of the account.
     * @param string $accessToken - Constant Contact OAuth2 Access Token
     * @param AccountInfo $accountInfo - Updated AccountInfo
     * @return AccountInfo
     */
    public function updateAccountInfo($accessToken, AccountInfo $accountInfo)
    {
        $baseUrl = Config::get('endpoints.base_url')
            . sprintf(Config::get('endpoints.account_info'));

        $url = $this->buildUrl($baseUrl);
        $response = parent::getRestClient()->put($url, parent::getHeaders($accessToken), $accountInfo);
        return AccountInfo::create(json_decode($response->body, true));
    }
}
