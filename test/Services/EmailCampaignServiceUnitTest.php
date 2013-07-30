<?php

use Ctct\Services\EmailMarketingService;
use Ctct\Util\RestClient;
use Ctct\Util\CurlResponse;
use Ctct\Components\EmailMarketing\Campaign;

class EmailMarketingServiceUnitTest extends PHPUnit_Framework_TestCase
{
    private $restClient;
    private $emailMarketingService;

    public function setUp()
    {
        $this->restClient = $this->getMock('Ctct\Util\RestClientInterface');
        $this->emailMarketingService = new EmailMarketingService("apikey", $this->restClient);
    }

    public function testGetCampaignsModifiedSince()
    {
        $curlResponse = CurlResponse::create(JsonLoader::getCampaignModifiedSinceJson(1), array('http_code' => 200));
        $this->restClient->expects($this->once())
            ->method('get')
            ->with()
            ->will($this->returnValue($curlResponse));

        $response = $this->emailMarketingService->getCampaigns(
            'access_token',
            array('modified_since' => '2013-01-12T20:04:59.436Z', 'limit' => 2)
        );
        $campaigns = $response->results;

        $this->assertInstanceOf('Ctct\Components\EmailMarketing\Campaign', $campaigns[0]);
        $this->assertEquals("ABcGFnZU51bT0yJnBhZ2VTaXplPTImbW9kaWZpZWRfc2luY2U9MTM1OTUxNjYzMDU5MA", $response->next);
        $this->assertEquals("9112921497760", $campaigns[0]->id);
        $this->assertEquals("Email Created 2013/03/29, 11:30 PM", $campaigns[0]->name);
        $this->assertEquals("DRAFT", $campaigns[0]->status);
        $this->assertEquals("2013-03-30T03:30:48.033Z", $campaigns[0]->modified_date);

        $this->assertEquals("9112756952331", $campaigns[1]->id);
        $this->assertEquals("CampaignName234", $campaigns[1]->name);
        $this->assertEquals("DRAFT", $campaigns[1]->status);
        $this->assertEquals("2013-03-14T15:00:07.883Z", $campaigns[1]->modified_date);
    }

    public function testGetCampaigns()
    {
        $curlResponse = CurlResponse::create(JsonLoader::getCampaignsJson(), array('http_code' => 200));
        $this->restClient->expects($this->once())
            ->method('get')
            ->with()
            ->will($this->returnValue($curlResponse));

        $response = $this->emailMarketingService->getCampaigns('access_token');
        $campaigns = $response->results;

        $this->assertInstanceOf('Ctct\Components\EmailMarketing\Campaign', $campaigns[0]);
        $this->assertEquals("cGFnZU51bT0yJnBhZ2VTaXplPTM", $response->next);
        $this->assertEquals("1100371240640", $campaigns[0]->id);
        $this->assertEquals("Email Created 2012/11/29, 4:13 PM", $campaigns[0]->name);
        $this->assertEquals("SENT", $campaigns[0]->status);
        $this->assertEquals("2012-11-29T16:15:17.468Z", $campaigns[0]->modified_date);

        $this->assertEquals("1100368835463", $campaigns[1]->id);
        $this->assertEquals("CampaignNdddasdsdme2", $campaigns[1]->name);
        $this->assertEquals("DRAFT", $campaigns[1]->status);
        $this->assertEquals("2012-10-16T16:14:34.221Z", $campaigns[1]->modified_date);
    }

    public function testDeleteCampaign()
    {
        $curlResponse = CurlResponse::create(null, array('http_code' => 204));
        $this->restClient->expects($this->once())
            ->method('delete')
            ->with()
            ->will($this->returnValue($curlResponse));

        $response = $this->emailMarketingService->deleteCampaign('access_token', "1100368835463");
        $this->assertTrue($response);
    }

    public function testDeleteCampaignFailed()
    {
        $curlResponse = CurlResponse::create(null, array('http_code' => 400));
        $this->restClient->expects($this->once())
            ->method('delete')
            ->with()
            ->will($this->returnValue($curlResponse));

        $response = $this->emailMarketingService->deleteCampaign('access_token', "1100368835463");
        $this->assertEquals(false, $response);
    }

    public function testGetCampaign()
    {
        $curlResponse = CurlResponse::create(JsonLoader::getCampaignJson(), array('http_code' => 201));
        $this->restClient->expects($this->once())
            ->method('get')
            ->with()
            ->will($this->returnValue($curlResponse));

        $campaign = $this->emailMarketingService->getCampaign('access_token', 11109369315398);

        $this->assertEquals("1100394165290", $campaign->id);
        $this->assertEquals("CampaignName-05965ddb-12d2-43e5-b8f3-0c22ca487c3a", $campaign->name);
        $this->assertEquals("CampaignSubject", $campaign->subject);
        $this->assertEquals("SENT", $campaign->status);
        $this->assertEquals("From WSPI", $campaign->from_name);
        $this->assertEquals("wiz-20121206130519@l1.snoopy.roving.com", $campaign->from_email);
        $this->assertEquals("wiz-20121206130519@l1.snoopy.roving.com", $campaign->reply_to_email);
        $this->assertEquals("CUSTOM", $campaign->template_type);
        $this->assertEquals("2012-12-06T18:06:05.255Z", $campaign->created_date);
        $this->assertEquals("2012-12-06T18:06:40.342Z", $campaign->last_run_date);
        $this->assertEquals(false, $campaign->is_permission_reminder_enabled);
        $this->assertEquals("", $campaign->permission_reminder_text);
        $this->assertEquals(false, $campaign->is_view_as_webpage_enabled);
        $this->assertEquals("Having trouble viewing this email?", $campaign->view_as_web_page_text);
        $this->assertEquals("Click Here", $campaign->view_as_web_page_link_text);
        $this->assertEquals("Hi", $campaign->greeting_salutations);
        $this->assertEquals("FIRST_NAME", $campaign->greeting_name);
        $this->assertEquals("", $campaign->greeting_string);
        $this->assertEquals("http://www.constantcontact.com", $campaign->permalink_url);

        $this->assertEquals(
            "<html><body>Hi <a href=\"http://www.constantcontact.com\">Visit ConstantContact.com!</a> </body></html>",
            $campaign->email_content
        );
        $this->assertEqualS("HTML", $campaign->email_content_format);
        $this->assertEquals("", $campaign->style_sheet);
        $this->assertEquals("<text>Something to test</text>", $campaign->text_content);

        // message footer
        $this->assertEquals("Waltham", $campaign->message_footer->city);
        $this->assertEquals("MA", $campaign->message_footer->state);
        $this->assertEquals("US", $campaign->message_footer->country);
        $this->assertEquals("WSPIOrgName", $campaign->message_footer->organization_name);
        $this->assertEquals("1601 Trapelo RD", $campaign->message_footer->address_line_1);
        $this->assertEquals("suite 2", $campaign->message_footer->address_line_2);
        $this->assertEquals("box 4", $campaign->message_footer->address_line_3);
        $this->assertEquals("", $campaign->message_footer->international_state);
        $this->assertEquals("02451", $campaign->message_footer->postal_code);
        $this->assertEquals(true, $campaign->message_footer->include_forward_email);
        $this->assertEquals("WSPIForwardThisEmail", $campaign->message_footer->forward_email_link_text);
        $this->assertEquals(true, $campaign->message_footer->include_subscribe_link);
        $this->assertEquals("WSPISubscribeLinkText", $campaign->message_footer->subscribe_link_text);

        // tracking summary
        $this->assertEquals(15, $campaign->tracking_summary->sends);
        $this->assertEquals(10, $campaign->tracking_summary->opens);
        $this->assertEquals(10, $campaign->tracking_summary->clicks);
        $this->assertEquals(3, $campaign->tracking_summary->forwards);
        $this->assertEquals(2, $campaign->tracking_summary->unsubscribes);
        $this->assertEquals(18, $campaign->tracking_summary->bounces);
        $this->assertEquals(1, $campaign->tracking_summary->spam_count);

        // sent to contact lists
        $this->assertEquals(1, count($campaign->sent_to_contact_lists));
        $this->assertEquals(3, $campaign->sent_to_contact_lists[0]->id);

        //click through details
        $this->assertEquals("http://www.constantcontact.com", $campaign->click_through_details[0]->url);
        $this->assertEquals("1100394163874", $campaign->click_through_details[0]->url_uid);
        $this->assertEquals(10, $campaign->click_through_details[0]->click_count);
    }

    public function testAddCampaign()
    {
        $curlResponse = CurlResponse::create(JsonLoader::getCampaignJson(), array('http_code' => 201));
        $this->restClient->expects($this->once())
            ->method('post')
            ->with()
            ->will($this->returnValue($curlResponse));

        $campaign = $this->emailMarketingService->addCampaign('access_token', new Campaign());

        $this->assertEquals("1100394165290", $campaign->id);
        $this->assertEquals("CampaignName-05965ddb-12d2-43e5-b8f3-0c22ca487c3a", $campaign->name);
        $this->assertEquals("CampaignSubject", $campaign->subject);
        $this->assertEquals("SENT", $campaign->status);
        $this->assertEquals("From WSPI", $campaign->from_name);
        $this->assertEquals("wiz-20121206130519@l1.snoopy.roving.com", $campaign->from_email);
        $this->assertEquals("wiz-20121206130519@l1.snoopy.roving.com", $campaign->reply_to_email);
        $this->assertEquals("CUSTOM", $campaign->template_type);
        $this->assertEquals("2012-12-06T18:06:05.255Z", $campaign->created_date);
        $this->assertEquals("2012-12-06T18:06:40.342Z", $campaign->last_run_date);
        $this->assertEquals(false, $campaign->is_permission_reminder_enabled);
        $this->assertEquals("", $campaign->permission_reminder_text);
        $this->assertEquals(false, $campaign->is_view_as_webpage_enabled);
        $this->assertEquals("Having trouble viewing this email?", $campaign->view_as_web_page_text);
        $this->assertEquals("Click Here", $campaign->view_as_web_page_link_text);
        $this->assertEquals("Hi", $campaign->greeting_salutations);
        $this->assertEquals("FIRST_NAME", $campaign->greeting_name);
        $this->assertEquals("", $campaign->greeting_string);

        $this->assertEquals(
            "<html><body>Hi <a href=\"http://www.constantcontact.com\">Visit ConstantContact.com!</a> </body></html>",
            $campaign->email_content
        );

        $this->assertEqualS("HTML", $campaign->email_content_format);
        $this->assertEquals("", $campaign->style_sheet);
        $this->assertEquals("<text>Something to test</text>", $campaign->text_content);

        // message footer
        $this->assertEquals("Waltham", $campaign->message_footer->city);
        $this->assertEquals("MA", $campaign->message_footer->state);
        $this->assertEquals("US", $campaign->message_footer->country);
        $this->assertEquals("WSPIOrgName", $campaign->message_footer->organization_name);
        $this->assertEquals("1601 Trapelo RD", $campaign->message_footer->address_line_1);
        $this->assertEquals("suite 2", $campaign->message_footer->address_line_2);
        $this->assertEquals("box 4", $campaign->message_footer->address_line_3);
        $this->assertEquals("", $campaign->message_footer->international_state);
        $this->assertEquals("02451", $campaign->message_footer->postal_code);
        $this->assertEquals(true, $campaign->message_footer->include_forward_email);
        $this->assertEquals("WSPIForwardThisEmail", $campaign->message_footer->forward_email_link_text);
        $this->assertEquals(true, $campaign->message_footer->include_subscribe_link);
        $this->assertEquals("WSPISubscribeLinkText", $campaign->message_footer->subscribe_link_text);

        // tracking summary
        $this->assertEquals(15, $campaign->tracking_summary->sends);
        $this->assertEquals(10, $campaign->tracking_summary->opens);
        $this->assertEquals(10, $campaign->tracking_summary->clicks);
        $this->assertEquals(3, $campaign->tracking_summary->forwards);
        $this->assertEquals(2, $campaign->tracking_summary->unsubscribes);
        $this->assertEquals(18, $campaign->tracking_summary->bounces);

        // sent to contact lists
        $this->assertEquals(1, count($campaign->sent_to_contact_lists));
        $this->assertEquals(3, $campaign->sent_to_contact_lists[0]->id);

        //click through details
        $this->assertEquals("http://www.constantcontact.com", $campaign->click_through_details[0]->url);
        $this->assertEquals("1100394163874", $campaign->click_through_details[0]->url_uid);
        $this->assertEquals(10, $campaign->click_through_details[0]->click_count);
    }

    public function testUpdateCampaign()
    {
        $curlResponse = CurlResponse::create(JsonLoader::getCampaignJson(), array('http_code' => 200));
        $this->restClient->expects($this->once())
            ->method('put')
            ->with()
            ->will($this->returnValue($curlResponse));

        $campaign = $this->emailMarketingService->updateCampaign('access_token', new Campaign());

        $this->assertEquals("1100394165290", $campaign->id);
        $this->assertEquals("CampaignName-05965ddb-12d2-43e5-b8f3-0c22ca487c3a", $campaign->name);
        $this->assertEquals("CampaignSubject", $campaign->subject);
        $this->assertEquals("SENT", $campaign->status);
        $this->assertEquals("From WSPI", $campaign->from_name);
        $this->assertEquals("wiz-20121206130519@l1.snoopy.roving.com", $campaign->from_email);
        $this->assertEquals("wiz-20121206130519@l1.snoopy.roving.com", $campaign->reply_to_email);
        $this->assertEquals("CUSTOM", $campaign->template_type);
        $this->assertEquals("2012-12-06T18:06:05.255Z", $campaign->created_date);
        $this->assertEquals("2012-12-06T18:06:40.342Z", $campaign->last_run_date);
        $this->assertEquals(false, $campaign->is_permission_reminder_enabled);
        $this->assertEquals("", $campaign->permission_reminder_text);
        $this->assertEquals(false, $campaign->is_view_as_webpage_enabled);
        $this->assertEquals("Having trouble viewing this email?", $campaign->view_as_web_page_text);
        $this->assertEquals("Click Here", $campaign->view_as_web_page_link_text);
        $this->assertEquals("Hi", $campaign->greeting_salutations);
        $this->assertEquals("FIRST_NAME", $campaign->greeting_name);
        $this->assertEquals("", $campaign->greeting_string);

        $this->assertEquals(
            "<html><body>Hi <a href=\"http://www.constantcontact.com\">Visit ConstantContact.com!</a> </body></html>",
            $campaign->email_content
        );

        $this->assertEquals("HTML", $campaign->email_content_format);
        $this->assertEquals("", $campaign->style_sheet);
        $this->assertEquals("<text>Something to test</text>", $campaign->text_content);

        // message footer
        $this->assertEquals("Waltham", $campaign->message_footer->city);
        $this->assertEquals("MA", $campaign->message_footer->state);
        $this->assertEquals("US", $campaign->message_footer->country);
        $this->assertEquals("WSPIOrgName", $campaign->message_footer->organization_name);
        $this->assertEquals("1601 Trapelo RD", $campaign->message_footer->address_line_1);
        $this->assertEquals("suite 2", $campaign->message_footer->address_line_2);
        $this->assertEquals("box 4", $campaign->message_footer->address_line_3);
        $this->assertEquals("", $campaign->message_footer->international_state);
        $this->assertEquals("02451", $campaign->message_footer->postal_code);
        $this->assertEquals(true, $campaign->message_footer->include_forward_email);
        $this->assertEquals("WSPIForwardThisEmail", $campaign->message_footer->forward_email_link_text);
        $this->assertEquals(true, $campaign->message_footer->include_subscribe_link);
        $this->assertEquals("WSPISubscribeLinkText", $campaign->message_footer->subscribe_link_text);

        // tracking summary
        $this->assertEquals(15, $campaign->tracking_summary->sends);
        $this->assertEquals(10, $campaign->tracking_summary->opens);
        $this->assertEquals(10, $campaign->tracking_summary->clicks);
        $this->assertEquals(3, $campaign->tracking_summary->forwards);
        $this->assertEquals(2, $campaign->tracking_summary->unsubscribes);
        $this->assertEquals(18, $campaign->tracking_summary->bounces);

        // sent to contact lists
        $this->assertEquals(1, count($campaign->sent_to_contact_lists));
        $this->assertEquals(3, $campaign->sent_to_contact_lists[0]->id);

        //click through details
        $this->assertEquals("http://www.constantcontact.com", $campaign->click_through_details[0]->url);
        $this->assertEquals("1100394163874", $campaign->click_through_details[0]->url_uid);
        $this->assertEquals(10, $campaign->click_through_details[0]->click_count);
    }
}
