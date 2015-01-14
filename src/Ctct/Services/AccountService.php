<?php
namespace Ctct\Services;

use Ctct\Util\Config;
use Ctct\Components\Account\VerifiedEmailAddress;
use Ctct\Components\Account\AccountInfo;
use GuzzleHttp\Stream\Stream;

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
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.account_verified_addresses');

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);
        if ($params) {
            $query = $request->getQuery();
            foreach ($params as $name => $value) {
                $query->add($name, $value);
            }
        }
        $response = parent::getClient()->send($request);

        $verifiedAddresses = array();
        foreach ($response->json() as $verifiedAddress) {
            $verifiedAddresses[] = VerifiedEmailAddress::create($verifiedAddress);
        }

        return $verifiedAddresses;
    }

    /**
     * Create a new verified email address. This will also prompt the account to send
     * a verification email to the address.
     * @param string $accessToken - Constant Contact OAuth2 Access Token
     * @param string $emailAddress - email address to create
     * @return array - array of VerifiedEmailAddress created
     */
    public function createVerifiedEmailAddresses($accessToken, $emailAddress)
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.account_verified_addresses');

        $request = parent::createBaseRequest($accessToken, 'POST', $baseUrl);
        $stream = Stream::factory(json_encode(array(array("email_address" => $emailAddress))));
        $request->setBody($stream);
        $response = parent::getClient()->send($request);

        $verifiedAddresses = array();
        foreach ($response->json() as $verifiedAddress) {
            $verifiedAddresses[] = VerifiedEmailAddress::create($verifiedAddress);
        }

        return $verifiedAddresses;
    }

    /**
     * Get account info associated with an access token
     * @param string $accessToken - Constant Contact OAuth2 Access Token
     * @return AccountInfo
     */
    public function getAccountInfo($accessToken)
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.account_info');

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);
        $response = parent::getClient()->send($request);

        return AccountInfo::create($response->json());
    }

    /**
     * Update information of the account.
     * @param string $accessToken - Constant Contact OAuth2 Access Token
     * @param AccountInfo $accountInfo - Updated AccountInfo
     * @return AccountInfo
     */
    public function updateAccountInfo($accessToken, AccountInfo $accountInfo)
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.account_info');

        $request = parent::createBaseRequest($accessToken, 'PUT', $baseUrl);
        $stream = Stream::factory(json_encode($accountInfo));
        $request->setBody($stream);
        $response = parent::getClient()->send($request);

        return AccountInfo::create($response->json());
    }
}
