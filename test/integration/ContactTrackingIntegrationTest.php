<?php

use Ctct\ConstantContact;
use Ctct\Components\Contacts\Address;
use Ctct\Components\Contacts\CustomField;
use Ctct\Components\Contacts\Contact;

class ContactTrackingIntegrationTest extends PHPUnit_Framework_TestCase{
        
    public $cc; 

    public function setUp()
    {
        $this->cc = new ConstantContact(APIKEY);
    }

    public function testGetContactSummaryReport()
    {
        $summaryReport = $this->cc->getContactSummaryReport(ACCESS_TOKEN, CONTACT_TRACKING_ID);
        $this->assertNotNull($summaryReport);
    }

    public function testGetContactClicks()
    {
        $resultSet = $this->cc->getContactClicks(ACCESS_TOKEN, CONTACT_TRACKING_ID);
        $this->assertNotNull($resultSet);
        
        if (count($resultSet->results) > 0) {
            $this->assertEquals("EMAIL_CLICK", $resultSet->results[0]->activity_type);
        }
    }

    public function testGetContactClicksLimit()
    {
        $resultSet = $this->cc->getContactClicks(ACCESS_TOKEN, CONTACT_TRACKING_ID, 1);
        $this->assertNotNull($resultSet);

        if (count($resultSet->results) > 0) {
            $this->assertEquals("EMAIL_CLICK", $resultSet->results[0]->activity_type);
            $this->assertEquals(1, count($resultSet->results));
        }

        return $resultSet->next;
    }

    /**
     * @depends testGetContactClicksLimit
     */ 
    public function testGetNextPageCampaignClicks($nextLink)
    {
        $resultSet = $this->cc->getContactClicks(ACCESS_TOKEN, CONTACT_TRACKING_ID, 1);
        $this->assertNotNull($resultSet);

        if (count($resultSet->results) > 0) {
            $this->assertEquals("EMAIL_CLICK", $resultSet->results[0]->activity_type);
            $this->assertEquals(1, count($resultSet->results));
        }
    }

    public function testGetContactSendsLimit()
    {
        $resultSet = $this->cc->getContactSends(ACCESS_TOKEN, CONTACT_TRACKING_ID, 1);
        $this->assertNotNull($resultSet);
        
        if (count($resultSet->results) > 0) {
            $this->assertEquals("EMAIL_SEND", $resultSet->results[0]->activity_type);
            $this->assertEquals(1, count($resultSet->results));
        }

        return $resultSet->next;
    }

    public function testGetContactBouncesLimit()
    {
        $resultSet = $this->cc->getContactBounces(ACCESS_TOKEN, CONTACT_TRACKING_ID, 1);
        $this->assertNotNull($resultSet);

        if (count($resultSet->results) > 0) {
            $this->assertEquals("EMAIL_BOUNCe", $resultSet->results[0]->activity_type);
            $this->assertEquals(1, count($resultSet->results));
            $this->assertNotNull($resultSet->next);
        }

        return $resultSet->next;
    }

    public function testGetContactOpenLimit()
    {
        $resultSet = $this->cc->getContactOpens(ACCESS_TOKEN, CONTACT_TRACKING_ID, 1);
        $this->assertNotNull($resultSet);

        if (count($resultSet->results) > 0) {
            $this->assertEquals("EMAIL_OPEN", $resultSet->results[0]->activity_type);
            $this->assertEquals(1, count($resultSet->results));
        }

        return $resultSet->next;
    }

    public function testGetContactUnsubscribeLimit()
    {
        $resultSet = $this->cc->getContactUnsubscribes(ACCESS_TOKEN, CONTACT_TRACKING_ID, 1);
        $this->assertNotNull($resultSet);

        if (count($resultSet->results) > 0) {
            $this->assertEquals("EMAIL_UNSUBSCRIBE", $resultSet->results[0]->activity_type);
            $this->assertEquals(1, count($resultSet->results));
        }

        return $resultSet->next;
    }

    public function testGtContactForwards()
    {
        $resultSet = $this->cc->getContactForwards(ACCESS_TOKEN, CONTACT_TRACKING_ID);
        $this->assertNotNull($resultSet);

        if (count($resultSet->results) > 0) {
            $this->assertEquals("EMAIL_FORWARD", $resultSet->results[0]->activity_type);
        }
    }

}
