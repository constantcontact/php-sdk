<?php
namespace Ctct\WebHooks;

use Ctct\Exceptions\CtctException;

/**
 * Main Webhook Utility class.<br/>
 * This is meant to be used by users to validate and parse Webhooks received from ConstantContact.<br/>
 *
 * @package     WebHooks
 * @author      Constant Contact
 */
class CTCTWebhookUtil
{

    /**
     * The client secret associated with the api key
     */
    private $clientSecret = '';


    /**
     * Constructor that creates a validation Object for WebHooks.
     * 
     * @param string $clientSecret - The client secret associated with the api key
     * @return  CTCTWebhookUtil
     */
    function __construct($clientSecret='')
    {
        $this->setClientSecret($clientSecret);
    }


    /**
     * CTCTWebhookUtil::getClientSecret()
     * 
     * @return string - the secret API key  
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }


    /**
     * CTCTWebhookUtil::setClientSecret()
     * Set the clientSecret
     * 
     * @param string $clientSecret - The client secret associated with the api key
     * @return void
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * Get Billing Change Notification.<br/>
     *
     * Validates and parses the bodyMessage into 
     *
     * @param xCtctHmacSHA256 The value in the x-ctct-hmac-sha256 header.
     * @param bodyMessage The body message from the POST received from ConstantContact in Webhook callback.
     * @return The object corresponding to bodyMessage in case of success; an exception is thrown otherwise.
     * @throws CtctException Thrown when :
     * <ul>
     * <li>message encryption does not correspond with x-ctct-hmac-sha256 header value;</li>
     * <li>or an error is raised when parsing the bodyMessage.</li>
     * </ul>
     * <p/>
     */
    public function getBillingChangeNotification($xCtctHmacSHA256, $bodyMessage)
    {
        if ($this->isValidWebhook($xCtctHmacSHA256, $bodyMessage))
        {
            return json_decode($bodyMessage);            
        } else
        {
            throw new CtctException("Invalid WebHook");
        }
    }

    /**
     * Check if a Webhook message is valid or not.<br/>
     *
     * @param xCtctHmacSHA256 The value in the x-ctct-hmac-sha256 header.
     * @param bodyMessage The body message from the POST received from ConstantContact in Webhook callback.
     * @return true if in case of success; false if the Webhook is invalid.
     * 
     */
    public function isValidWebhook($xCtctHmacSHA256, $bodyMessage)
    {    
        if ($this->getClientSecret() == null)
        {
            throw new CtctException("NO_CLIENT_SECRET");
        }
        $encodedString = hash_hmac("sha256", $bodyMessage, $this->clientSecret);
        
        return ($encodedString == $xCtctHmacSHA256)?true:false;
    }
}
