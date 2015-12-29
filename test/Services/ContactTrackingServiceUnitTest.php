<?php

use Ctct\Components\ResultSet;
use Ctct\Components\Tracking\BounceActivity;
use Ctct\Components\Tracking\ClickActivity;
use Ctct\Components\Tracking\ForwardActivity;
use Ctct\Components\Tracking\OpenActivity;
use Ctct\Components\Tracking\SendActivity;
use Ctct\Components\Tracking\TrackingSummary;
use Ctct\Components\Tracking\UnsubscribeActivity;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class ContactTrackingServiceUnitTest extends PHPUnit_Framework_TestCase {
    /**
     * @var Client
     */
    private static $client;

    public static function setUpBeforeClass() {
        $mock = new MockHandler([
            new Response(200, array(), JsonLoader::getBounces()),
            new Response(200, array(), JsonLoader::getClicks()),
            new Response(200, array(), JsonLoader::getForwards()),
            new Response(200, array(), JsonLoader::getOptOuts()),
            new Response(200, array(), JsonLoader::getSends()),
            new Response(200, array(), JsonLoader::getOpens()),
            new Response(200, array(), JsonLoader::getSummary())
        ]);
        $handler = HandlerStack::create($mock);
        self::$client = new Client(['handler' => $handler]);
    }

    public function testGetBounces() {
        $response = json_decode(self::$client->request('GET', '/')->getBody(), true);

        $resultSet = new ResultSet($response['results'], $response['meta']);
        $bounceActivity = BounceActivity::create($resultSet->results[0]);
        $this->assertInstanceOf('Ctct\Components\ResultSet', $resultSet);
        $this->assertInstanceOf('Ctct\Components\Tracking\BounceActivity', $bounceActivity);
        $this->assertEquals("bGltaXQ9MyZuZXh0PTEzNTQ4MTcyMTA0MzA", $resultSet->next);
        $this->assertEquals("EMAIL_BOUNCE", $bounceActivity->activity_type);
        $this->assertEquals(1100394165290, $bounceActivity->campaign_id);
        $this->assertEquals("2", $bounceActivity->contact_id);
        $this->assertEquals(
            "wizpie9dedde9dd27644bdb3d9be134b7294f71354817123188401000@snoopy.roving.com",
            $bounceActivity->email_address
        );

        $this->assertEquals("B", $bounceActivity->bounce_code);
        $this->assertEquals("Non-existent address", $bounceActivity->bounce_description);
        $this->assertEquals("", $bounceActivity->bounce_message);
        $this->assertEquals("2012-12-06T13:05:24.844Z", $bounceActivity->bounce_date);
    }

    public function testGetClicks() {
        $response = json_decode(self::$client->request('GET', '/')->getBody(), true);

        $resultSet = new ResultSet($response['results'], $response['meta']);
        $clickActivity = ClickActivity::create($resultSet->results[0]);
        $this->assertInstanceOf('Ctct\Components\ResultSet', $resultSet);
        $this->assertInstanceOf('Ctct\Components\Tracking\ClickActivity', $clickActivity);
        $this->assertEquals("bGltaXQ9MyZuZXh0PTEzNTQ4MTcyMTA0MzA", $resultSet->next);
        $this->assertEquals("EMAIL_CLICK", $clickActivity->activity_type);
        $this->assertEquals(1100394165290, $clickActivity->campaign_id);
        $this->assertEquals("69", $clickActivity->contact_id);
        $this->assertEquals(
            "wizpie00375ca0a11346a89aea4b8f5991d0d91354817217769892000@snoopy.roving.com",
            $clickActivity->email_address
        );

        $this->assertEquals(0, $clickActivity->link_id);
        $this->assertEquals("2012-12-06T13:07:01.701Z", $clickActivity->click_date);
    }

    public function testGetForwards() {
        $response = json_decode(self::$client->request('GET', '/')->getBody(), true);

        $resultSet = new ResultSet($response['results'], $response['meta']);
        $forwardActivity = ForwardActivity::create($resultSet->results[0]);
        $this->assertInstanceOf('Ctct\Components\ResultSet', $resultSet);
        $this->assertInstanceOf('Ctct\Components\Tracking\ForwardActivity', $forwardActivity);
        $this->assertEquals("bGltaXQ9MyZuZXh0PTEzNTQ4MTcyMTA0MzA", $resultSet->next);
        $this->assertEquals("EMAIL_FORWARD", $forwardActivity->activity_type);
        $this->assertEquals(1100394165290, $forwardActivity->campaign_id);
        $this->assertEquals("74", $forwardActivity->contact_id);
        $this->assertEquals(
            "wizpie2ca3455df5c34a26806f519f01f8a22e1354817223114268000@snoopy.roving.com",
            $forwardActivity->email_address
        );

        $this->assertEquals("2012-12-06T13:07:06.810Z", $forwardActivity->forward_date);
    }

    public function testGetUnsubscribes() {
        $response = json_decode(self::$client->request('GET', '/')->getBody(), true);

        $resultSet = new ResultSet($response['results'], $response['meta']);
        $unsubscribeActivity = UnsubscribeActivity::create($resultSet->results[0]);
        $this->assertInstanceOf('Ctct\Components\ResultSet', $resultSet);
        $this->assertInstanceOf('Ctct\Components\Tracking\UnsubscribeActivity', $unsubscribeActivity);
        $this->assertEquals("bGltaXQ9MyZuZXh0PTEzNTQ4MTcyMTA0MzA", $resultSet->next);
        $this->assertEquals("EMAIL_UNSUBSCRIBE", $unsubscribeActivity->activity_type);
        $this->assertEquals(1100394165290, $unsubscribeActivity->campaign_id);
        $this->assertEquals("58", $unsubscribeActivity->contact_id);
        $this->assertEquals(
            "wizpieabd7817c1d0d4f08bb05f16f6681221c1354817211855027000@snoopy.roving.com",
            $unsubscribeActivity->email_address
        );

        $this->assertEquals("2012-12-06T13:06:53.440Z", $unsubscribeActivity->unsubscribe_date);
        $this->assertEquals("ACTION_BY_CUSTOMER", $unsubscribeActivity->unsubscribe_source);
        $this->assertEquals("", $unsubscribeActivity->unsubscribe_reason);
    }

    public function testGetSends() {
        $response = json_decode(self::$client->request('GET', '/')->getBody(), true);

        $resultSet = new ResultSet($response['results'], $response['meta']);
        $sendActivity = SendActivity::create($resultSet->results[0]);
        $this->assertInstanceOf('Ctct\Components\ResultSet', $resultSet);
        $this->assertInstanceOf('Ctct\Components\Tracking\SendActivity', $sendActivity);
        $this->assertEquals("bGltaXQ9MyZuZXh0PTEzNTQ4MTcyMTA0MzA", $resultSet->next);
        $this->assertEquals("EMAIL_SEND", $sendActivity->activity_type);
        $this->assertEquals(1100394165290, $sendActivity->campaign_id);
        $this->assertEquals("55", $sendActivity->contact_id);
        $this->assertEquals(
            "wizpiea298d1c2500b4f2d8294300de4b29fe31354817207606824000@snoopy.roving.com",
            $sendActivity->email_address
        );

        $this->assertEquals("2012-12-06T18:06:50.650Z", $sendActivity->send_date);
    }

    public function testGetOpens() {
        $response = json_decode(self::$client->request('GET', '/')->getBody(), true);

        $resultSet = new ResultSet($response['results'], $response['meta']);
        $openActivity = OpenActivity::create($resultSet->results[0]);
        $this->assertInstanceOf('Ctct\Components\ResultSet', $resultSet);
        $this->assertInstanceOf('Ctct\Components\Tracking\OpenActivity', $openActivity);
        $this->assertEquals("bGltaXQ9MyZuZXh0PTEzNTQ4MTcyMTA0MzA", $resultSet->next);
        $this->assertEquals("EMAIL_OPEN", $openActivity->activity_type);
        $this->assertEquals(1100394165290, $openActivity->campaign_id);
        $this->assertEquals("86", $openActivity->contact_id);
        $this->assertEquals(
            "wizpie9e19a6d35ec249efa8fc3085721aa61d1354817227762990000@snoopy.roving.com",
            $openActivity->email_address
        );

        $this->assertEquals("2012-12-06T13:07:11.839Z", $openActivity->open_date);
    }

    public function testGetSummary() {
        $response = self::$client->request('GET', '/');

        $summary = TrackingSummary::create(json_decode($response->getBody(), true));
        $this->assertInstanceOf('Ctct\Components\Tracking\TrackingSummary', $summary);
        $this->assertEquals(15, $summary->sends);
        $this->assertEquals(10, $summary->opens);
        $this->assertEquals(10, $summary->clicks);
        $this->assertEquals(3, $summary->forwards);
        $this->assertEquals(2, $summary->unsubscribes);
        $this->assertEquals(18, $summary->bounces);
    }
}
