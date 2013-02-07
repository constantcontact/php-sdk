<?php

use Ctct\Services\CampaignTrackingService;
use Ctct\Util\RestClient;

class CampaignTrackingServiceUnitTest extends PHPUnit_Framework_TestCase{

    public function testGetBounces()
    {
        $rest_client = new MockRestClient(200, JsonLoader::getBounces());
        $svc = new CampaignTrackingService($rest_client);

        $tracking_activity = $svc->getBounces('access_token', 1100394165290, 0, 2);

        $this->assertEquals("4", $tracking_activity->next);
        $this->assertEquals("EMAIL_BOUNCE", $tracking_activity->results[0]->activity_type);
        $this->assertEquals(1100394165290, $tracking_activity->results[0]->campaign_id);
        $this->assertEquals("2", $tracking_activity->results[0]->contact_id);
        $this->assertEquals("wizpie9dedde9dd27644bdb3d9be134b7294f71354817123188401000@snoopy.roving.com",
            $tracking_activity->results[0]->email_address);

        $this->assertEquals("B", $tracking_activity->results[0]->bounce_code);
        $this->assertEquals("Non-existent address", $tracking_activity->results[0]->bounce_description);
        $this->assertEquals("", $tracking_activity->results[0]->bounce_message);
        $this->assertEquals("2012-12-06T13:05:24.844Z", $tracking_activity->results[0]->bounce_date);
    }

    public function testGetClicks()
    {
        $rest_client = new MockRestClient(200, JsonLoader::getClicks());
        $svc = new CampaignTrackingService($rest_client);

        $tracking_activity = $svc->getClicks('access_token', 1100394165290, 0, 2);

        $this->assertEquals("1354817221599", $tracking_activity->next);
        $this->assertEquals("EMAIL_CLICK", $tracking_activity->results[0]->activity_type);
        $this->assertEquals(1100394165290, $tracking_activity->results[0]->campaign_id);
        $this->assertEquals("69", $tracking_activity->results[0]->contact_id);
        $this->assertEquals("wizpie00375ca0a11346a89aea4b8f5991d0d91354817217769892000@snoopy.roving.com",
            $tracking_activity->results[0]->email_address);

        $this->assertEquals(0, $tracking_activity->results[0]->link_id);
        $this->assertEquals("2012-12-06T13:07:01.701Z", $tracking_activity->results[0]->click_date);
    }

    public function testGetForwards()
    {
        $rest_client = new MockRestClient(200, JsonLoader::getForwards());
        $svc = new CampaignTrackingService($rest_client);

        $tracking_activity = $svc->getForwards('access_token', 1100394165290, 0, 2);

        $this->assertEquals("1354817226701", $tracking_activity->next);
        $this->assertEquals("EMAIL_FORWARD", $tracking_activity->results[0]->activity_type);
        $this->assertEquals(1100394165290, $tracking_activity->results[0]->campaign_id);
        $this->assertEquals("74", $tracking_activity->results[0]->contact_id);
        $this->assertEquals("wizpie2ca3455df5c34a26806f519f01f8a22e1354817223114268000@snoopy.roving.com",
            $tracking_activity->results[0]->email_address);

        $this->assertEquals("2012-12-06T13:07:06.810Z", $tracking_activity->results[0]->forward_date);
    }

    public function testGetOptOuts()
    {
        $rest_client = new MockRestClient(200, JsonLoader::getOptOuts());
        $svc = new CampaignTrackingService($rest_client);

        $tracking_activity = $svc->getOptOuts('access_token', 1100394165290, 0, 2);

        $this->assertEquals("135", $tracking_activity->next);
        $this->assertEquals("EMAIL_UNSUBSCRIBE", $tracking_activity->results[0]->activity_type);
        $this->assertEquals(1100394165290, $tracking_activity->results[0]->campaign_id);
        $this->assertEquals("58", $tracking_activity->results[0]->contact_id);
        $this->assertEquals("wizpieabd7817c1d0d4f08bb05f16f6681221c1354817211855027000@snoopy.roving.com",
            $tracking_activity->results[0]->email_address);

        $this->assertEquals("2012-12-06T13:06:53.440Z", $tracking_activity->results[0]->unsubscribe_date);
        $this->assertEquals("ACTION_BY_CUSTOMER", $tracking_activity->results[0]->unsubscribe_source);
        $this->assertEquals("", $tracking_activity->results[0]->unsubscribe_reason);
    }

    public function testGetSends()
    {
        $rest_client = new MockRestClient(200, JsonLoader::getSends());
        $svc = new CampaignTrackingService($rest_client);

        $tracking_activity = $svc->getSends('access_token', 1100394165290, 0, 2);

        $this->assertEquals("1354817210533", $tracking_activity->next);
        $this->assertEquals("EMAIL_SEND", $tracking_activity->results[0]->activity_type);
        $this->assertEquals(1100394165290, $tracking_activity->results[0]->campaign_id);
        $this->assertEquals("55", $tracking_activity->results[0]->contact_id);
        $this->assertEquals("wizpiea298d1c2500b4f2d8294300de4b29fe31354817207606824000@snoopy.roving.com",
            $tracking_activity->results[0]->email_address);

        $this->assertEquals("2012-12-06T13:06:50.650Z", $tracking_activity->results[0]->send_date);
    }

    public function testGetOpens()
    {
        $rest_client = new MockRestClient(200, JsonLoader::getOpens());
        $svc = new CampaignTrackingService($rest_client);

        $tracking_activity = $svc->getOpens('access_token', 1100394165290, 0, 2);

        $this->assertEquals("1354817231729", $tracking_activity->next);
        $this->assertEquals("EMAIL_OPEN", $tracking_activity->results[0]->activity_type);
        $this->assertEquals(1100394165290, $tracking_activity->results[0]->campaign_id);
        $this->assertEquals("86", $tracking_activity->results[0]->contact_id);
        $this->assertEquals("wizpie9e19a6d35ec249efa8fc3085721aa61d1354817227762990000@snoopy.roving.com",
            $tracking_activity->results[0]->email_address);

        $this->assertEquals("2012-12-06T13:07:11.839Z", $tracking_activity->results[0]->open_date);
    }

    public function testGetSummary()
    {
        $rest_client = new MockRestClient(200, JsonLoader::getSummary());
        $svc = new CampaignTrackingService($rest_client);

        $summary = $svc->getSummary('access_token', 1100394165290);

        $this->assertEquals(15, $summary->sends);
        $this->assertEquals(10, $summary->opens);
        $this->assertEquals(10, $summary->clicks);
        $this->assertEquals(3, $summary->forwards);
        $this->assertEquals(2, $summary->unsubscribes);
        $this->assertEquals(18, $summary->bounces);
        $this->assertEquals(1100394165290, $summary->campaign_id);
    }

}
