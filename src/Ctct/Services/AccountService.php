<?php
namespace Ctct\Services;

use Ctct\Exceptions\CtctException;
use Ctct\Util\Config;
use Ctct\Components\Account\VerifiedEmailAddress;
use Ctct\Components\Account\AccountInfo;
use GuzzleHttp\Exception\ClientException;
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
     * @param array $params - associative array of query parameters and values to append to the request.
     *      Allowed parameters include:
     *      status - Status to filter results by. Must be one of ALL, CONFIRMED, or UNCONFIRMED.
     * @return array of VerifiedEmailAddress
     * @throws CtctException
     */
    public function getVerifiedEmailAddresses($accessToken, Array $params = array())
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.account_verified_addresses');

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
     * @throws CtctException
     */
    public function createVerifiedEmailAddress($accessToken, $emailAddress)
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.account_verified_addresses');

        $request = parent::createBaseRequest($accessToken, 'POST', $baseUrl);
        $stream = Stream::factory(json_encode(array(array("email_address" => $emailAddress))));
        $request->setBody($stream);

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

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
     * @throws CtctException
     */
    public function getAccountInfo($accessToken)
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.account_info');

        $request = parent::createBaseRequest($accessToken, 'GET', $baseUrl);

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

        return AccountInfo::create($response->json());
    }

    /**
     * Update information of the account.
     * @param string $accessToken - Constant Contact OAuth2 Access Token
     * @param AccountInfo $accountInfo - Updated AccountInfo
     * @return AccountInfo
     * @throws CtctException
     */
    public function updateAccountInfo($accessToken, AccountInfo $accountInfo)
    {
        $baseUrl = Config::get('endpoints.base_url') . Config::get('endpoints.account_info');

        $request = parent::createBaseRequest($accessToken, 'PUT', $baseUrl);
        $stream = Stream::factory(json_encode($accountInfo));
        $request->setBody($stream);

        try {
            $response = parent::getClient()->send($request);
        } catch (ClientException $e) {
            throw parent::convertException($e);
        }

        return AccountInfo::create($response->json());
    }
}
