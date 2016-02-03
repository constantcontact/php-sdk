<?php
namespace Ctct\Services;

use Ctct\Exceptions\CtctException;
use Ctct\Util\Config;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7\Request;

/**
 * Super class for all services
 *
 * @package Services
 * @author Constant Contact
 */
abstract class BaseService {
    /**
     * GuzzleHTTP Client Implementation to use for HTTP requests
     * @var Client
     */
    private $client;
    /**
     * ApiKey for the application
     * @var string
     */
    private $apiKey;

    /**
     * Constructor with the option to to supply an alternative rest client to be used
     * @param string $apiKey - Constant Contact API Key
     * @param ClientInterface|null $client - GuzzleHttp Client
     */
    public function __construct($apiKey, ClientInterface $client = null) {
        $this->apiKey = $apiKey;
        $this->client = $client ?: new Client();
    }

    protected static function getHeadersForMultipart($accessToken) {
        return array(
            'User-Agent' => 'ConstantContact AppConnect PHP Library v' . Config::get('settings.version'),
            'Content-Type' => 'multipart/form-data',
            'Authorization' => 'Bearer ' . $accessToken
        );
    }

    /**
     * Get the rest client being used by the service
     * @return Client - GuzzleHTTP Client implementation being used
     */
    protected function getClient() {
        return $this->client;
    }

    protected function sendRequestWithBody($accessToken, $method, $baseUrl, $body, Array $queryParams = array()) {
        $queryParams["api_key"] = $this->apiKey;
        $request = new Request($method, $baseUrl);
        return $this->client->send($request, [
            'query' => $queryParams,
            'json' => $body,
            'headers' => self::getHeaders($accessToken)
        ]);
    }

    /**
     * Helper function to return required headers for making an http request with constant contact
     * @param $accessToken - OAuth2 access token to be placed into the Authorization header
     * @return array - authorization headers
     */
    private static function getHeaders($accessToken) {
        return array(
            'User-Agent' => 'ConstantContact AppConnect PHP Library v' . Config::get('settings.version'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
            'x-ctct-request-source' => 'sdk.php.' . Config::get('settings.version')
        );
    }

    protected function sendRequestWithoutBody($accessToken, $method, $baseUrl, Array $queryParams = array()) {
        $queryParams["api_key"] = $this->apiKey;
        $request = new Request($method, $baseUrl);

        return $this->client->send($request, [
            'query' => $queryParams,
            'headers' => self::getHeaders($accessToken)
        ]);
    }

    /**
     * Turns a ClientException into a CtctException - like magic.
     * @param TransferException $exception - Guzzle TransferException can be one of RequestException,
     *        ConnectException, ClientException, ServerException
     * @return CtctException
     */
    protected function convertException($exception) {
        if ($exception instanceof ClientException || $exception instanceof ServerException) {
            $ctctException = new CtctException($exception->getResponse()->getReasonPhrase(), $exception->getCode());
        } else {
            $ctctException = new CtctException("Something went wrong", $exception->getCode());
        }
        $ctctException->setUrl($exception->getRequest()->getUri());
        $ctctException->setErrors(json_decode($exception->getResponse()->getBody()->getContents()));
        return $ctctException;
    }
}
