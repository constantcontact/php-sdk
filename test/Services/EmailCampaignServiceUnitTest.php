<?php

use Ctct\Services\EmailCampaignService;
use Ctct\Util\RestClient;
use Ctct\Components\EmailCampaigns\EmailCampaign;
 
class EmailCampaignServiceUnitTest extends PHPUnit_Framework_TestCase{

	public function testGetCampaigns()
	{
        $rest_client = new MockRestClient(200, JsonLoader::getCampaignsJson());
		
		$campaign_service = new EmailCampaignService("apikey", $rest_client);
		$response = $campaign_service->getCampaigns('access_token');
		$campaigns = $response->results;
		
		$this->assertEquals("?next=cGFnZU51bT0yJnBhZ2VTaXplPTM", $response->next);
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
        $rest_client = new MockRestClient(204, null);
		
		$campaign_service = new EmailCampaignService("apikey", $rest_client);
		$response = $campaign_service->deleteCampaign('access_token', "1100368835463");
		
		$this->assertTrue($response);
	}
	
	public function testDeleteCampaignFailed()
	{
        $rest_client = new MockRestClient(400, JsonLoader::getCampaignsJson());
		
		$campaign_service = new EmailCampaignService("apikey", $rest_client);
		$response = $campaign_service->deleteCampaign('access_token', "1100368835463");
		
		$this->assertEquals(false, $response);
	}
	
	public function testGetCampaign()
	{
        $rest_client = new MockRestClient(201, JsonLoader::getCampaignJson());
		
		$campaign_service = new EmailCampaignService("apikey", $rest_client);
		$campaign = $campaign_service->getCampaign('access_token', 11109369315398);
		
		$this->assertEquals("1100394165290", $campaign->id);
		$this->assertEquals("CampaignName-05965ddb-12d2-43e5-b8f3-0c22ca487c3a", $campaign->name);
		$this->assertEquals("CampaignSubject", $campaign->subject);
		$this->assertEquals("SENT", $campaign->status);
		$this->assertEquals("From WSPI", $campaign->from_name);
		$this->assertEquals("wiz-20121206130519@l1.snoopy.roving.com", $campaign->from_email);
		$this->assertEquals("wiz-20121206130519@l1.snoopy.roving.com", $campaign->reply_to_email);
		$this->assertEquals("CUSTOM", $campaign->campaign_type);
		$this->assertEquals("2012-12-06T18:06:05.255Z", $campaign->created_date);
		$this->assertEquals("2012-12-06T18:06:05.255Z", $campaign->last_edit_date);
		$this->assertEquals("2012-12-06T18:06:40.342Z", $campaign->last_run_date);
		$this->assertEquals(false, $campaign->is_permission_reminder_enabled);
		$this->assertEquals("", $campaign->permission_reminder_text);
		$this->assertEquals(false, $campaign->is_view_as_webpage_enabled);
		$this->assertEquals("Having trouble viewing this email?", $campaign->view_as_web_page_text);
		$this->assertEquals("Click Here", $campaign->view_as_web_page_link_text);
		$this->assertEquals("Hi", $campaign->greeting_salutations);
		$this->assertEquals("FIRST_NAME", $campaign->greeting_name);
		$this->assertEquals("", $campaign->greeting_string);

		$this->assertEquals("<html><body>Hi <a href=\"http://www.constantcontact.com\">Visit ConstantContact.com!</a> </body></html>", $campaign->email_content);
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
	
	public function testAddCampaign()
	{
        $rest_client = new MockRestClient(204, JsonLoader::getCampaignJson());

		$campaign_service = new EmailCampaignService("apikey", $rest_client);
		$campaign = $campaign_service->addCampaign('access_token', new EmailCampaign());
		
		$this->assertEquals("1100394165290", $campaign->id);
		$this->assertEquals("CampaignName-05965ddb-12d2-43e5-b8f3-0c22ca487c3a", $campaign->name);
		$this->assertEquals("CampaignSubject", $campaign->subject);
		$this->assertEquals("SENT", $campaign->status);
		$this->assertEquals("From WSPI", $campaign->from_name);
		$this->assertEquals("wiz-20121206130519@l1.snoopy.roving.com", $campaign->from_email);
		$this->assertEquals("wiz-20121206130519@l1.snoopy.roving.com", $campaign->reply_to_email);
		$this->assertEquals("CUSTOM", $campaign->campaign_type);
		$this->assertEquals("2012-12-06T18:06:05.255Z", $campaign->created_date);
		$this->assertEquals("2012-12-06T18:06:05.255Z", $campaign->last_edit_date);
		$this->assertEquals("2012-12-06T18:06:40.342Z", $campaign->last_run_date);
		$this->assertEquals(false, $campaign->is_permission_reminder_enabled);
		$this->assertEquals("", $campaign->permission_reminder_text);
		$this->assertEquals(false, $campaign->is_view_as_webpage_enabled);
		$this->assertEquals("Having trouble viewing this email?", $campaign->view_as_web_page_text);
		$this->assertEquals("Click Here", $campaign->view_as_web_page_link_text);
		$this->assertEquals("Hi", $campaign->greeting_salutations);
		$this->assertEquals("FIRST_NAME", $campaign->greeting_name);
		$this->assertEquals("", $campaign->greeting_string);

		$this->assertEquals("<html><body>Hi <a href=\"http://www.constantcontact.com\">Visit ConstantContact.com!</a> </body></html>", $campaign->email_content);
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
        $rest_client = new MockRestClient(200, JsonLoader::getCampaignJson());
		
		$campaign_service = new EmailCampaignService("apikey", $rest_client);
		$campaign = $campaign_service->updateCampaign('access_token', new EmailCampaign());
		
		$this->assertEquals("1100394165290", $campaign->id);
		$this->assertEquals("CampaignName-05965ddb-12d2-43e5-b8f3-0c22ca487c3a", $campaign->name);
		$this->assertEquals("CampaignSubject", $campaign->subject);
		$this->assertEquals("SENT", $campaign->status);
		$this->assertEquals("From WSPI", $campaign->from_name);
		$this->assertEquals("wiz-20121206130519@l1.snoopy.roving.com", $campaign->from_email);
		$this->assertEquals("wiz-20121206130519@l1.snoopy.roving.com", $campaign->reply_to_email);
		$this->assertEquals("CUSTOM", $campaign->campaign_type);
		$this->assertEquals("2012-12-06T18:06:05.255Z", $campaign->created_date);
		$this->assertEquals("2012-12-06T18:06:05.255Z", $campaign->last_edit_date);
		$this->assertEquals("2012-12-06T18:06:40.342Z", $campaign->last_run_date);
		$this->assertEquals(false, $campaign->is_permission_reminder_enabled);
		$this->assertEquals("", $campaign->permission_reminder_text);
		$this->assertEquals(false, $campaign->is_view_as_webpage_enabled);
		$this->assertEquals("Having trouble viewing this email?", $campaign->view_as_web_page_text);
		$this->assertEquals("Click Here", $campaign->view_as_web_page_link_text);
		$this->assertEquals("Hi", $campaign->greeting_salutations);
		$this->assertEquals("FIRST_NAME", $campaign->greeting_name);
		$this->assertEquals("", $campaign->greeting_string);

		$this->assertEquals("<html><body>Hi <a href=\"http://www.constantcontact.com\">Visit ConstantContact.com!</a> </body></html>", $campaign->email_content);
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
}
