<?php

use Ctct\ConstantContact;
use Ctct\Components\Contacts\Address;
use Ctct\Components\Contacts\CustomField;
use Ctct\Components\Contacts\Contact;

require_once 'config.inc';

class CampaignTrackingIntegrationTest extends PHPUnit_Framework_TestCase{
        
    public $cc; 

    public function setUp()
    {
        $this->cc = new ConstantContact(APIKEY);
    }

    public function testGetEmailCampaignSummaryReport()
    {
        $summaryReport = $this->cc->getEmailCampaignSummaryReport(ACCESS_TOKEN, EMAIL_CAMPAIGN_TRACKING_ID);
        $this->assertNotNull($summaryReport);
    }

    public function testGetEmailCampaignClicks()
    {
        $resultSet = $this->cc->getEmailCampaignClicks(ACCESS_TOKEN, EMAIL_CAMPAIGN_TRACKING_ID);
        $this->assertNotNull($resultSet);
        $this->assertEquals("EMAIL_CLICK", $resultSet->results[0]->activity_type);
    }

    public function testGetEmailCampaignClicksLimit()
    {
        $resultSet = $this->cc->getEmailCampaignClicks(ACCESS_TOKEN, EMAIL_CAMPAIGN_TRACKING_ID, 1);
        $this->assertNotNull($resultSet);
        $this->assertEquals("EMAIL_CLICK", $resultSet->results[0]->activity_type);
        $this->assertEquals(1, count($resultSet->results));
        $this->assertNotNull($resultSet->next);

        return $resultSet->next;
    }

    /**
     * @depends testGetEmailCampaignClicksLimit
     */ 
    public function testGetNextPageCampaignClicks($nextLink)
    {
        $resultSet = $this->cc->getEmailCampaignClicks(ACCESS_TOKEN, EMAIL_CAMPAIGN_TRACKING_ID, 1);
        $this->assertNotNull($resultSet);
        $this->assertEquals("EMAIL_CLICK", $resultSet->results[0]->activity_type);
        $this->assertEquals(1, count($resultSet->results));
    }

    public function testGetEmailCampaignSendsLimit()
    {
        $resultSet = $this->cc->getEmailCampaignSends(ACCESS_TOKEN, EMAIL_CAMPAIGN_TRACKING_ID, 1);
        $this->assertNotNull($resultSet);
        $this->assertEquals("EMAIL_SEND", $resultSet->results[0]->activity_type);
        $this->assertEquals(1, count($resultSet->results));
        $this->assertNotNull($resultSet->next);

        return $resultSet->next;
    }

    public function testGetEmailCampaignBouncesLimit()
    {
        $resultSet = $this->cc->getEmailCampaignBounces(ACCESS_TOKEN, EMAIL_CAMPAIGN_TRACKING_ID, 1);
        $this->assertNotNull($resultSet);
        $this->assertEquals("EMAIL_BOUNCE", $resultSet->results[0]->activity_type);
        $this->assertEquals(1, count($resultSet->results));
        $this->assertNotNull($resultSet->next);

        return $resultSet->next;
    }

    public function testGetEmailCampaignOpenLimit()
    {
        $resultSet = $this->cc->getEmailCampaignOpens(ACCESS_TOKEN, EMAIL_CAMPAIGN_TRACKING_ID, 1);
        $this->assertNotNull($resultSet);
        $this->assertEquals("EMAIL_OPEN", $resultSet->results[0]->activity_type);
        $this->assertEquals(1, count($resultSet->results));
        $this->assertNotNull($resultSet->next);

        return $resultSet->next;
    }

    public function testGetEmailCampaignUnsubscribeLimit()
    {
        $resultSet = $this->cc->getEmailCampaignUnsubscribes(ACCESS_TOKEN, EMAIL_CAMPAIGN_TRACKING_ID, 1);
        $this->assertNotNull($resultSet);
        $this->assertEquals(1, count($resultSet->results));
        $this->assertEquals("EMAIL_UNSUBSCRIBE", $resultSet->results[0]->activity_type);
        $this->assertNotNull($resultSet->next);

        return $resultSet->next;
    }

    public function testGetEmailCampaignForwards()
    {
        $resultSet = $this->cc->getEmailCampaignForwards(ACCESS_TOKEN, EMAIL_CAMPAIGN_TRACKING_ID);
        $this->assertNotNull($resultSet);
        $this->assertEquals("EMAIL_FORWARD", $resultSet->results[0]->activity_type);
    }

}
