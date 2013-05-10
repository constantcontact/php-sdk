<?php

use Ctct\Services\CampaignTrackingService;
use Ctct\Util\RestClient;
use Ctct\Util\CurlResponse;

class CampaignTrackingServiceUnitTest extends PHPUnit_Framework_TestCase
{

    private $restClient;
    private $campaignTrackingService;

    public function setUp()
    {
        $this->restClient = $this->getMock('Ctct\Util\RestClientInterface');
        $this->campaignTrackingService = new CampaignTrackingService("apikey", $this->restClient);
    }

    public function testGetBounces()
    {
        $curlResponse = CurlResponse::create(JsonLoader::getBounces(), array('http_code' => 200));
        $this->restClient->expects($this->once())
            ->method('get')
            ->with()
            ->will($this->returnValue($curlResponse));

        $resultSet = $this->campaignTrackingService->getBounces('access_token', "1100394165290", array('limit' => 2));

        $this->assertInstanceOf('Ctct\Components\ResultSet', $resultSet);
        $this->assertInstanceOf('Ctct\Components\Tracking\BounceActivity', $resultSet->results[0]);
        $this->assertEquals("bGltaXQ9MyZuZXh0PTEzNTQ4MTcyMTA0MzA", $resultSet->next);
        $this->assertEquals("EMAIL_BOUNCE", $resultSet->results[0]->activity_type);
        $this->assertEquals(1100394165290, $resultSet->results[0]->campaign_id);
        $this->assertEquals("2", $resultSet->results[0]->contact_id);
        $this->assertEquals(
            "wizpie9dedde9dd27644bdb3d9be134b7294f71354817123188401000@snoopy.roving.com",
            $resultSet->results[0]->email_address
        );

        $this->assertEquals("B", $resultSet->results[0]->bounce_code);
        $this->assertEquals("Non-existent address", $resultSet->results[0]->bounce_description);
        $this->assertEquals("", $resultSet->results[0]->bounce_message);
        $this->assertEquals("2012-12-06T13:05:24.844Z", $resultSet->results[0]->bounce_date);
    }

    public function testGetClicks()
    {
        $curlResponse = CurlResponse::create(JsonLoader::getClicks(), array('http_code' => 200));
        $this->restClient->expects($this->once())
            ->method('get')
            ->with()
            ->will($this->returnValue($curlResponse));

        $resultSet = $this->campaignTrackingService->getClicks('access_token', "1100394165290", array('limit' => 2));

        $this->assertInstanceOf('Ctct\Components\ResultSet', $resultSet);
        $this->assertInstanceOf('Ctct\Components\Tracking\ClickActivity', $resultSet->results[0]);
        $this->assertEquals("bGltaXQ9MyZuZXh0PTEzNTQ4MTcyMTA0MzA", $resultSet->next);
        $this->assertEquals("EMAIL_CLICK", $resultSet->results[0]->activity_type);
        $this->assertEquals(1100394165290, $resultSet->results[0]->campaign_id);
        $this->assertEquals("69", $resultSet->results[0]->contact_id);
        $this->assertEquals(
            "wizpie00375ca0a11346a89aea4b8f5991d0d91354817217769892000@snoopy.roving.com",
            $resultSet->results[0]->email_address
        );

        $this->assertEquals(0, $resultSet->results[0]->link_id);
        $this->assertEquals("2012-12-06T13:07:01.701Z", $resultSet->results[0]->click_date);
    }

    public function testGetForwards()
    {
        $curlResponse = CurlResponse::create(JsonLoader::getForwards(), array('http_code' => 200));
        $this->restClient->expects($this->once())
            ->method('get')
            ->with()
            ->will($this->returnValue($curlResponse));

        $resultSet = $this->campaignTrackingService->getForwards('access_token', "1100394165290", array('limit' => 2));

        $this->assertInstanceOf('Ctct\Components\ResultSet', $resultSet);
        $this->assertInstanceOf('Ctct\Components\Tracking\ForwardActivity', $resultSet->results[0]);
        $this->assertEquals("bGltaXQ9MyZuZXh0PTEzNTQ4MTcyMTA0MzA", $resultSet->next);
        $this->assertEquals("EMAIL_FORWARD", $resultSet->results[0]->activity_type);
        $this->assertEquals(1100394165290, $resultSet->results[0]->campaign_id);
        $this->assertEquals("74", $resultSet->results[0]->contact_id);
        $this->assertEquals(
            "wizpie2ca3455df5c34a26806f519f01f8a22e1354817223114268000@snoopy.roving.com",
            $resultSet->results[0]->email_address
        );

        $this->assertEquals("2012-12-06T13:07:06.810Z", $resultSet->results[0]->forward_date);
    }

    public function testGetUnsubscribes()
    {
        $curlResponse = CurlResponse::create(JsonLoader::getOptOuts(), array('http_code' => 200));
        $this->restClient->expects($this->once())
            ->method('get')
            ->with()
            ->will($this->returnValue($curlResponse));

        $resultSet = $this->campaignTrackingService->getUnsubscribes(
            'access_token',
            "1100394165290",
            array('limit' => 2)
        );

        $this->assertInstanceOf('Ctct\Components\ResultSet', $resultSet);
        $this->assertInstanceOf('Ctct\Components\Tracking\UnsubscribeActivity', $resultSet->results[0]);
        $this->assertEquals("bGltaXQ9MyZuZXh0PTEzNTQ4MTcyMTA0MzA", $resultSet->next);
        $this->assertEquals("EMAIL_UNSUBSCRIBE", $resultSet->results[0]->activity_type);
        $this->assertEquals(1100394165290, $resultSet->results[0]->campaign_id);
        $this->assertEquals("58", $resultSet->results[0]->contact_id);
        $this->assertEquals(
            "wizpieabd7817c1d0d4f08bb05f16f6681221c1354817211855027000@snoopy.roving.com",
            $resultSet->results[0]->email_address
        );

        $this->assertEquals("2012-12-06T13:06:53.440Z", $resultSet->results[0]->unsubscribe_date);
        $this->assertEquals("ACTION_BY_CUSTOMER", $resultSet->results[0]->unsubscribe_source);
        $this->assertEquals("", $resultSet->results[0]->unsubscribe_reason);
    }

    public function testGetSends()
    {
        $curlResponse = CurlResponse::create(JsonLoader::getSends(), array('http_code' => 200));
        $this->restClient->expects($this->once())
            ->method('get')
            ->with()
            ->will($this->returnValue($curlResponse));

        $resultSet = $this->campaignTrackingService->getSends('access_token', "1100394165290", array('limit' => 2));

        $this->assertInstanceOf('Ctct\Components\ResultSet', $resultSet);
        $this->assertInstanceOf('Ctct\Components\Tracking\SendActivity', $resultSet->results[0]);
        $this->assertEquals("bGltaXQ9MyZuZXh0PTEzNTQ4MTcyMTA0MzA", $resultSet->next);
        $this->assertEquals("EMAIL_SEND", $resultSet->results[0]->activity_type);
        $this->assertEquals(1100394165290, $resultSet->results[0]->campaign_id);
        $this->assertEquals("55", $resultSet->results[0]->contact_id);
        $this->assertEquals(
            "wizpiea298d1c2500b4f2d8294300de4b29fe31354817207606824000@snoopy.roving.com",
            $resultSet->results[0]->email_address
        );

        $this->assertEquals("2012-12-06T18:06:50.650Z", $resultSet->results[0]->send_date);
    }

    public function testGetOpens()
    {
        $curlResponse = CurlResponse::create(JsonLoader::getOpens(), array('http_code' => 200));
        $this->restClient->expects($this->once())
            ->method('get')
            ->with()
            ->will($this->returnValue($curlResponse));

        $resultSet = $this->campaignTrackingService->getOpens('access_token', "1100394165290", array('limit' => 2));

        $this->assertInstanceOf('Ctct\Components\ResultSet', $resultSet);
        $this->assertInstanceOf('Ctct\Components\Tracking\OpenActivity', $resultSet->results[0]);
        $this->assertEquals("bGltaXQ9MyZuZXh0PTEzNTQ4MTcyMTA0MzA", $resultSet->next);
        $this->assertEquals("EMAIL_OPEN", $resultSet->results[0]->activity_type);
        $this->assertEquals(1100394165290, $resultSet->results[0]->campaign_id);
        $this->assertEquals("86", $resultSet->results[0]->contact_id);
        $this->assertEquals(
            "wizpie9e19a6d35ec249efa8fc3085721aa61d1354817227762990000@snoopy.roving.com",
            $resultSet->results[0]->email_address
        );

        $this->assertEquals("2012-12-06T13:07:11.839Z", $resultSet->results[0]->open_date);
    }

    public function testGetSummary()
    {
        $curlResponse = CurlResponse::create(JsonLoader::getSummary(), array('http_code' => 200));
        $this->restClient->expects($this->once())
            ->method('get')
            ->with()
            ->will($this->returnValue($curlResponse));

        $summary = $this->campaignTrackingService->getSummary('access_token', 1100394165290);

        $this->assertInstanceOf('Ctct\Components\Tracking\TrackingSummary', $summary);
        $this->assertEquals(15, $summary->sends);
        $this->assertEquals(10, $summary->opens);
        $this->assertEquals(10, $summary->clicks);
        $this->assertEquals(3, $summary->forwards);
        $this->assertEquals(2, $summary->unsubscribes);
        $this->assertEquals(18, $summary->bounces);
    }
}
