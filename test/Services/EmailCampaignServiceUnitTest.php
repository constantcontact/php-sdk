<?php

use Ctct\Components\ResultSet;
use Ctct\Components\EmailMarketing\Campaign;
use Ctct\Components\EmailMarketing\CampaignPreview;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Message\Response;

class EmailMarketingServiceUnitTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    private static $client;

    public static function setUpBeforeClass()
    {
        self::$client = new Client();
        $getCampaignsStream = Stream::factory(JsonLoader::getCampaignsJson());
        $getCampaignStream = Stream::factory(JsonLoader::getCampaignJson());
        $getPreviewStream = Stream::factory(JsonLoader::getPreviewJson());
        $mock = new Mock([
            new Response(200, array(), $getCampaignsStream),
            new Response(204, array()),
            new Response(400, array()),
            new Response(200, array(), $getCampaignStream),
            new Response(201, array(), $getCampaignStream),
            new Response(200, array(), $getCampaignStream),
            new Response(200, array(), $getPreviewStream)
        ]);
        self::$client->getEmitter()->attach($mock);
    }

    public function testGetCampaigns()
    {
        $response = self::$client->get('/')->json();
        $result = new ResultSet($response['results'], $response['meta']);
        $campaigns = array();
        foreach ($result->results as $campaign) {
            $campaigns[] = Campaign::create($campaign);
        }

        $this->assertInstanceOf('Ctct\Components\ResultSet', $result);
        $this->assertInstanceOf('Ctct\Components\EmailMarketing\Campaign', $campaigns[0]);
        $this->assertEquals("cGFnZU51bT0yJnBhZ2VTaXplPTM", $result->next);
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
        $response = self::$client->delete('/');
        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testDeleteCampaignFailed()
    {
        try {
            self::$client->delete('/');
            $this->fail("Delete did not fail");
        } catch (ClientException $e) {
            $this->assertEquals(400, $e->getCode());
        }
    }

    public function testGetCampaign()
    {
        $response = self::$client->get('/');

        $campaign = Campaign::create($response->json());
        $this->assertInstanceOf('Ctct\Components\EmailMarketing\Campaign', $campaign);
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
        $response = self::$client->post('/');

        $campaign = Campaign::create($response->json());
        $this->assertInstanceOf('Ctct\Components\EmailMarketing\Campaign', $campaign);
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
        $response = self::$client->put('/');

        $campaign = Campaign::create($response->json());
        $this->assertInstanceOf('Ctct\Components\EmailMarketing\Campaign', $campaign);
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

    public function testGetPreview() {
        $response = self::$client->get('/');

        $preview = CampaignPreview::create($response->json());
        $this->assertEquals("Subject Test", $preview->subject);
        $this->assertEquals("myemail@example.com", $preview->fromEmail);
        $this->assertEquals("myemail@example.com", $preview->replyToEmail);
        $htmlContent = "<head ><meta /></head><body><center><table bgcolor=\"#ffffff\" id=\"VWPLINK\" width=\"595\"><tr><td style=\"font-size: 8pt; font-family: Verdana, Arial, Helvetica, sans-serif; color: #000000;\" width=\"100%\">View this message as a web page\n<a >Click here\n</a></td></tr></table></center><center ><table bgcolor=\"#ffffff\" width=\"595\" ><tr ><td width=\"100%\" ><font color=\"#000000\" face=\"verdana,arial\" size=\"1\" ><div >As a reminder, you're receiving this email because you have expressed an interest in MyCompany. Don't forget to add from_email@example.com to your address book so we'll be sure to land in your inbox! You may unsubscribe if you no longer wish to receive our emails. <div >&nbsp;</div><div >You may <a >unsubscribe</a> if you no longer wish to receive our emails.</div></div></font></td></tr></table></center><img /><p>This is text of the email message.</p><br />\n<table bgcolor=\"#ffffff\" padding=\"0\" width=\"100%\" ><tr align=\"center\" ><td ><table bgcolor=\"#ffffff\" width=\"595\" ><tr ><td colspan=\"2\" ><font face=\"tahoma,sans-serif\" size=\"1\" ><b ><a >Click here to forward this message</a></b></font><br />\n<br />\n</td></tr>\n<tr ><td ><FooterContent ><a ><img /></a></FooterContent></td><td align=\"right\" ><font face=\"tahoma,sans-serif\" size=\"1\" ><FooterLogo ><a ><img /></a></FooterLogo></font>\n</td>\n</tr><tr ><td colspan=\"2\" ><font face=\"tahoma,sans-serif\" size=\"1\" ><FooterContent ><div >This email was sent to {Email Address} by <a >rmarcucella@constantcontact.com</a> <span style=\"color: #bababa;\" > | </span> &nbsp; </div>\n<div ><a >Update Profile/Email Address</a> <span style=\"color: #bababa;\" >|</span> Instant removal with <a >SafeUnsubscribe</a>&trade; <span style=\"color: #bababa;\" >|</span> <a >Privacy Policy</a>.</div></FooterContent></font>\n</td>\n</tr>\n<tr ><td colspan=\"2\" ><font face=\"tahoma,sans-serif\" size=\"1\" ><br />My Organization | 123 Maple Street | Suite 1 | Anytown | MA | 01444</font>\n</td>\n</tr>\n</table>\n</td>\n</tr>\n</table>\n<br />\n&lt;/body&gt;";
        $this->assertEquals($htmlContent, $preview->htmlContent);
        $textContent = "View this message as a web page\nClick here\nhttp://campaign.r20.l1.constantcontact.com/render?ca=025eff86-6378-4f53-9301-5897ecf50b30&c={Contact Id}&ch={Contact Id}\n\nAs a reminder, you're receiving this email because you have expressed an interest in MyCompany. Don't forget to add from_email@example.com to your address book so we'll be sure to land in your inbox! You may unsubscribe if you no longer wish to receive our emails. You may unsubscribe\nhttp://visitor.l1.constantcontact.com/do?p=un&m=001JZtDyxcvPiye1EthMqSLGA%3D%3D&ch={Contact Id}&ca=025eff86-6378-4f53-9301-5897ecf50b30\n if you no longer wish to receive our emails.\n------------------------------------------------------------\nThis is the text of the email message.\n\nClick here to forward this message\nhttp://ui.l1.constantcontact.com/sa/fwtf.jsp?llr=cqmhk9aab&m=1100394770946&ea=rmarcucella%40constantcontact.com&a=1100400205633\n\n\n\n\n\nThis email was sent to {Email Address} by rmarcucella@constantcontact.com.\n\nUpdate Profile/Email Address\nhttp://visitor.l1.constantcontact.com/do?p=oo&m=001JZtDyxcvPiye1EthMqSLGA%3D%3D&ch={Contact Id}&ca=025eff86-6378-4f53-9301-5897ecf50b30\n\n\nInstant removal with SafeUnsubscribe(TM)\nhttp://visitor.l1.constantcontact.com/do?p=un&m=001JZtDyxcvPiye1EthMqSLGA%3D%3D&ch={Contact Id}&ca=025eff86-6378-4f53-9301-5897ecf50b30\n\n\nPrivacy Policy:\nhttp://ui.l1.constantcontact.com/roving/CCPrivacyPolicy.jsp\n\n\n\n\n\nOnline Marketing by\nhttp://img.l1.constantcontact.com/letters/images/cc-logo-color-sm.gif\nhttp://www.constantcontact.com\n\n\n\nMy Organization | 123 Maple Street | Suite 1 | Anytown | MA | 01444\n\n\n\n\n\n\n\n\n";
        $this->assertEquals($textContent, $preview->textContent);
    }
}
