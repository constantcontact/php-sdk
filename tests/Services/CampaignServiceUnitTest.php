<?php

use Ctct\Services\CampaignService;
use Ctct\Util\RestClient;
use Ctct\Components\Campaigns\Campaign;
 
class CampaignServiceUnitTest extends PHPUnit_Framework_TestCase{

	public function testGetCampaigns()
	{
        $rest_client = new MockRestClient(200, JsonLoader::getCampaignsJson());
		
		$campaign_service = new CampaignService($rest_client);
		$campaigns = $campaign_service->getCampaigns('access_token');
		
		$this->assertEquals("1100371240640", $campaigns[0]->id);
		$this->assertEquals("Email Created 2012/11/29, 4:13 PM", $campaigns[0]->name);
		$this->assertEquals("SENT", $campaigns[0]->status);
		$this->assertEquals("2012-11-29T16:15:17.468Z", $campaigns[0]->created_date);
		$this->assertEquals(false, $campaigns[0]->is_permission_reminder_enabled);
		$this->assertEquals(false, $campaigns[0]->is_view_as_webpage_enabled);
		$this->assertEquals(true, $campaigns[0]->is_visible_in_ui);
		
		$this->assertEquals("1100368835463", $campaigns[1]->id);
		$this->assertEquals("CampaignNdddasdsdme2", $campaigns[1]->name);
		$this->assertEquals("DRAFT", $campaigns[1]->status);
		$this->assertEquals("2012-10-16T16:14:34.221Z", $campaigns[1]->created_date);
		$this->assertEquals(false, $campaigns[1]->is_permission_reminder_enabled);
		$this->assertEquals(false, $campaigns[1]->is_view_as_webpage_enabled);
		$this->assertEquals(true, $campaigns[1]->is_visible_in_ui);
	}
	
	public function testDeleteCampaign()
	{
        $rest_client = new MockRestClient(204, null);
		
		$campaign_service = new CampaignService($rest_client);
		$response = $campaign_service->deleteCampaign('access_token', "1100368835463");
		
		$this->assertTrue($response);
	}
	
	public function testDeleteCampaignFailed()
	{
        $rest_client = new MockRestClient(400, JsonLoader::getCampaignsJson());
		
		$campaign_service = new CampaignService($rest_client);
		$response = $campaign_service->deleteCampaign('access_token', "1100368835463");
		
		$this->assertEquals(false, $response);
	}
	
	public function testGetCampaign()
	{
        $rest_client = new MockRestClient(201, JsonLoader::getCampaignJson());
		
		$campaign_service = new CampaignService($rest_client);
		$campaign = $campaign_service->getCampaign('access_token', 11109369315398);
		
		$this->assertEquals("11109369315398", $campaign->id);
		$this->assertEquals("Innovation Day Email", $campaign->name);
		$this->assertEquals("CTCT Alumni Newsletter", $campaign->subject);
		$this->assertEquals("DRAFT", $campaign->status);
		$this->assertEquals("CTCT", $campaign->from_name);
		$this->assertEquals("test@roving.com", $campaign->from_email);
		$this->assertEquals("test@roving.com", $campaign->reply_to_email);
		$this->assertEquals("STOCK", $campaign->campaign_type);
		$this->assertEquals("2012-09-07T10:01:51.847Z", $campaign->created_date);
		$this->assertEquals("2012-09-07T10:01:51.847Z", $campaign->last_edit_date);
		$this->assertEquals("http://myemail.constantcontact.com/.html?soid=1109020606536&aid=cvMftVppYKI", $campaign->share_page_url);
		$this->assertEquals(true, $campaign->is_permission_reminder_enabled);
		$this->assertEquals("", $campaign->permission_reminder_text);
		$this->assertEquals(true, $campaign->is_view_as_webpage_enabled);
		$this->assertEquals("Having trouble viewing this email?", $campaign->view_as_web_page_text);
		$this->assertEquals("Click here", $campaign->view_as_web_page_link_text);
		$this->assertEquals("", $campaign->greeting_salutations);
		$this->assertEquals("NONE", $campaign->greeting_name);
		$this->assertEquals("", $campaign->greeting_string);
		
		// message footer
		$this->assertEquals("Waltham", $campaign->message_footer->city);
		$this->assertEquals("MA", $campaign->message_footer->state);
		$this->assertEquals("us", $campaign->message_footer->country);
		$this->assertEquals("CTCT", $campaign->message_footer->organization_name);
		$this->assertEquals("1601 Trapelo Rd", $campaign->message_footer->address_line_1);
		$this->assertEquals("addr2", $campaign->message_footer->address_line_2);
		$this->assertEquals("addr3", $campaign->message_footer->address_line_3);
		$this->assertEquals("MX", $campaign->message_footer->international_state);
		$this->assertEquals("02451", $campaign->message_footer->postal_code);
		$this->assertEquals(true, $campaign->message_footer->include_forward_email);
		$this->assertEquals("Forward this email", $campaign->message_footer->forward_email_link_text);
		$this->assertEquals(true, $campaign->message_footer->include_subscribe_link);
		$this->assertEquals("Subscribe Me!", $campaign->message_footer->subscribe_link_text);
		
		// tracking summary
		$this->assertEquals(10, $campaign->tracking_summary->sends);
		$this->assertEquals(5, $campaign->tracking_summary->opens);
		$this->assertEquals(2, $campaign->tracking_summary->clicks);
		$this->assertEquals(1, $campaign->tracking_summary->forwards);
		$this->assertEquals(0, $campaign->tracking_summary->unsubscribes);
		$this->assertEquals(1, $campaign->tracking_summary->bounces);
		$this->assertEquals(11109369315398, $campaign->tracking_summary->campaign_id);
		
		$this->assertEquals("PENDING", $campaign->archive_status);
		$this->assertEquals(false, $campaign->is_visible_in_ui);
		$this->assertEquals("http://archive.constantcontact.com/fs039/1234/archive/456.html", $campaign->archive_url);
		
		// sent to contact lists
		$this->assertEquals(2, $campaign->sent_to_contact_lists[0]->id);
		
		//click through details
		$this->assertEquals("http://www.google.com", $campaign->click_through_details[0]->url);
		$this->assertEquals("1100371042104", $campaign->click_through_details[0]->url_uid);
		$this->assertEquals(4, $campaign->click_through_details[0]->click_count);
		
		$this->assertEquals("http://www.microsoft.com", $campaign->click_through_details[1]->url);
		$this->assertEquals("1100341242106", $campaign->click_through_details[1]->url_uid);
		$this->assertEquals(2, $campaign->click_through_details[1]->click_count);
		
		$this->assertEquals("http://www.yahoo.com", $campaign->click_through_details[2]->url);
		$this->assertEquals("1100371242405", $campaign->click_through_details[2]->url_uid);
		$this->assertEquals(2, $campaign->click_through_details[2]->click_count);
		
	}
	
	public function testAddCampaign()
	{
        $rest_client = new MockRestClient(204, JsonLoader::getCampaignJson());

		$campaign_service = new CampaignService($rest_client);
		$campaign = $campaign_service->addCampaign('access_token', new Campaign());
		
		$this->assertEquals("11109369315398", $campaign->id);
		$this->assertEquals("Innovation Day Email", $campaign->name);
		$this->assertEquals("CTCT Alumni Newsletter", $campaign->subject);
		$this->assertEquals("DRAFT", $campaign->status);
		$this->assertEquals("CTCT", $campaign->from_name);
		$this->assertEquals("test@roving.com", $campaign->from_email);
		$this->assertEquals("test@roving.com", $campaign->reply_to_email);
		$this->assertEquals("STOCK", $campaign->campaign_type);
		$this->assertEquals("2012-09-07T10:01:51.847Z", $campaign->created_date);
		$this->assertEquals("2012-09-07T10:01:51.847Z", $campaign->last_edit_date);
		$this->assertEquals("http://myemail.constantcontact.com/.html?soid=1109020606536&aid=cvMftVppYKI", $campaign->share_page_url);
		$this->assertEquals(true, $campaign->is_permission_reminder_enabled);
		$this->assertEquals("", $campaign->permission_reminder_text);
		$this->assertEquals(true, $campaign->is_view_as_webpage_enabled);
		$this->assertEquals("Having trouble viewing this email?", $campaign->view_as_web_page_text);
		$this->assertEquals("Click here", $campaign->view_as_web_page_link_text);
		$this->assertEquals("", $campaign->greeting_salutations);
		$this->assertEquals("NONE", $campaign->greeting_name);
		$this->assertEquals("", $campaign->greeting_string);
		
		// message footer
		$this->assertEquals("Waltham", $campaign->message_footer->city);
		$this->assertEquals("MA", $campaign->message_footer->state);
		$this->assertEquals("us", $campaign->message_footer->country);
		$this->assertEquals("CTCT", $campaign->message_footer->organization_name);
		$this->assertEquals("1601 Trapelo Rd", $campaign->message_footer->address_line_1);
		$this->assertEquals("addr2", $campaign->message_footer->address_line_2);
		$this->assertEquals("addr3", $campaign->message_footer->address_line_3);
		$this->assertEquals("MX", $campaign->message_footer->international_state);
		$this->assertEquals("02451", $campaign->message_footer->postal_code);
		$this->assertEquals(true, $campaign->message_footer->include_forward_email);
		$this->assertEquals("Forward this email", $campaign->message_footer->forward_email_link_text);
		$this->assertEquals(true, $campaign->message_footer->include_subscribe_link);
		$this->assertEquals("Subscribe Me!", $campaign->message_footer->subscribe_link_text);
		
		// tracking summary
		$this->assertEquals(10, $campaign->tracking_summary->sends);
		$this->assertEquals(5, $campaign->tracking_summary->opens);
		$this->assertEquals(2, $campaign->tracking_summary->clicks);
		$this->assertEquals(1, $campaign->tracking_summary->forwards);
		$this->assertEquals(0, $campaign->tracking_summary->unsubscribes);
		$this->assertEquals(1, $campaign->tracking_summary->bounces);
		$this->assertEquals(11109369315398, $campaign->tracking_summary->campaign_id);
		
		$this->assertEquals("PENDING", $campaign->archive_status);
		$this->assertEquals(false, $campaign->is_visible_in_ui);
		$this->assertEquals("http://archive.constantcontact.com/fs039/1234/archive/456.html", $campaign->archive_url);
	}

	public function testUpdateCampaign()
	{
        $rest_client = new MockRestClient(200, JsonLoader::getCampaignJson());
		
		$campaign_service = new CampaignService($rest_client);
		$campaign = $campaign_service->updateCampaign('access_token', new Campaign());
		
		$this->assertEquals("11109369315398", $campaign->id);
		$this->assertEquals("Innovation Day Email", $campaign->name);
		$this->assertEquals("CTCT Alumni Newsletter", $campaign->subject);
		$this->assertEquals("DRAFT", $campaign->status);
		$this->assertEquals("CTCT", $campaign->from_name);
		$this->assertEquals("test@roving.com", $campaign->from_email);
		$this->assertEquals("test@roving.com", $campaign->reply_to_email);
		$this->assertEquals("STOCK", $campaign->campaign_type);
		$this->assertEquals("2012-09-07T10:01:51.847Z", $campaign->created_date);
		$this->assertEquals("2012-09-07T10:01:51.847Z", $campaign->last_edit_date);
		$this->assertEquals("http://myemail.constantcontact.com/.html?soid=1109020606536&aid=cvMftVppYKI", $campaign->share_page_url);
		$this->assertEquals(true, $campaign->is_permission_reminder_enabled);
		$this->assertEquals("", $campaign->permission_reminder_text);
		$this->assertEquals(true, $campaign->is_view_as_webpage_enabled);
		$this->assertEquals("Having trouble viewing this email?", $campaign->view_as_web_page_text);
		$this->assertEquals("Click here", $campaign->view_as_web_page_link_text);
		$this->assertEquals("", $campaign->greeting_salutations);
		$this->assertEquals("NONE", $campaign->greeting_name);
		$this->assertEquals("", $campaign->greeting_string);
		
		// message footer
		$this->assertEquals("Waltham", $campaign->message_footer->city);
		$this->assertEquals("MA", $campaign->message_footer->state);
		$this->assertEquals("us", $campaign->message_footer->country);
		$this->assertEquals("CTCT", $campaign->message_footer->organization_name);
		$this->assertEquals("1601 Trapelo Rd", $campaign->message_footer->address_line_1);
		$this->assertEquals("addr2", $campaign->message_footer->address_line_2);
		$this->assertEquals("addr3", $campaign->message_footer->address_line_3);
		$this->assertEquals("MX", $campaign->message_footer->international_state);
		$this->assertEquals("02451", $campaign->message_footer->postal_code);
		$this->assertEquals(true, $campaign->message_footer->include_forward_email);
		$this->assertEquals("Forward this email", $campaign->message_footer->forward_email_link_text);
		$this->assertEquals(true, $campaign->message_footer->include_subscribe_link);
		$this->assertEquals("Subscribe Me!", $campaign->message_footer->subscribe_link_text);
		
		// tracking summary
		$this->assertEquals(10, $campaign->tracking_summary->sends);
		$this->assertEquals(5, $campaign->tracking_summary->opens);
		$this->assertEquals(2, $campaign->tracking_summary->clicks);
		$this->assertEquals(1, $campaign->tracking_summary->forwards);
		$this->assertEquals(0, $campaign->tracking_summary->unsubscribes);
		$this->assertEquals(1, $campaign->tracking_summary->bounces);
		$this->assertEquals(11109369315398, $campaign->tracking_summary->campaign_id);
		
		$this->assertEquals("PENDING", $campaign->archive_status);
		$this->assertEquals(false, $campaign->is_visible_in_ui);
		$this->assertEquals("http://archive.constantcontact.com/fs039/1234/archive/456.html", $campaign->archive_url);
	}
}
