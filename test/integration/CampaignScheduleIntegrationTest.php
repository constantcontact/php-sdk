<?php

use Ctct\ConstantContact;
use Ctct\Components\EmailMarketing\Schedule;
use Ctct\Components\EmailMarketing\Campaign;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\EmailMarketing\TestSend;

require_once 'config.inc';

class CampaignScheduleIntegrationTest extends PHPUnit_Framework_TestCase{
        
    public $cc; 

    const CONTACT_KEY = "contact";
    const CAMPAIGN_KEY = "campaign";
    const SCHEDULE_KEY = "schedule";
    const SCHEDULE_DATE_FORMAT = "Y-m-d\TH:i:s\.000\Z";

    public function setUp()
    {
        $this->cc = new ConstantContact(APIKEY);
    }

    public function testAddSchedule()
    {
        // create the list to send a campaign to
        $list = new ContactList();
        $list->name = self::createRandomName();
        $list->status = "ACTIVE";
        $returnList = $this->cc->addList(ACCESS_TOKEN, $list);
        $this->assertNotNull($returnList);

        // create the contact as a member of the created list
        $contact = new Contact();
        $contact->addEmail(self::createRandomEmail());
        $contact->addList($returnList);
        $returnContact = $this->cc->addContact(ACCESS_TOKEN, $contact);
        $this->assertNotNull($returnContact);

        // create the campaign to schedule
        $campaign = new Campaign();
        $campaignName = "Int Test " . time();
        $campaign->name = $campaignName;
        $campaign->subject = "my subject";
        $campaign->from_name = "David Jellesma";
        $campaign->from_email = VERIFIED_EMAIL_ADDRESS;
        $campaign->reply_to_email = VERIFIED_EMAIL_ADDRESS;
        $campaign->greeting_string = "Greetings Subjects!";
        $campaign->text_content = "This is my text";
        $campaign->email_content_format = "HTML";
        $campaign->email_content = "<html><body>This is my content</body></html>";
        $returnCampaign = $this->cc->addEmailCampaign(ACCESS_TOKEN, $campaign);   
        $this->assertNotNull($returnCampaign);
        
        // schedule the campaign to be sent
        $schedule = new Schedule();
        $scheduled_date = date(self::SCHEDULE_DATE_FORMAT, strtotime("+3 months"));
        $schedule->scheduled_date = $scheduled_date;
        $returnSchedule = $this->cc->addEmailCampaignSchedule(ACCESS_TOKEN, $returnCampaign->id, $schedule);
        $this->assertNotNull($returnSchedule);

        // return all the data used for other tests and final clean up
        return array(
            self::CAMPAIGN_KEY => $returnCampaign,
            self::CONTACT_KEY  => $returnContact,
            self::SCHEDULE_KEY => $returnSchedule
        );
    }

    /**
     * @depends testAddSchedule
     */ 
    public function testGetCampaignSchedules(array $params)
    {
        $schedules = $this->cc->getEmailCampaignSchedules(self::ACCESS_TOKEN, $param[self::CAMPAIGN_KEY]);
        $this->assertNotNull($schedules);
        $this->assertEquals(1, count($schedules));
        $this->assertEquals($params[self::SCHEDULE_KEY]->scheduled_date, $schedules[0]->scheduled_date);
    }

    /**
     * @depends testAddSchedule
     */ 
    public function testGetCampaignSchedule(array $params)
    {
        $schedule = $this->cc->getEmailCampaignSchedule(self::ACCESS_TOKEN, $params[self::CAMPAIGN_KEY], $params[self::SCHEDULE_KEY]);
        $this->assertNotNull($schedule);
        $this->assertEquals(1, $schedule->schedule_id);
        $this->assertEquals($params[self::SCHEDULE_KEY]->scheduled_date, $schedule->scheduled_date);
    }

    /**
     * @depends testAddSchedule
     */ 
    public function testUpdateCampaignSchedule(array $params)
    {
        $schedule = $params[self::SCHEDULE_KEY];
        $newScheduledDate = date(self::SCHEDULE_DATE_FORMAT, strtotime("+4 months"));
        $schedule->scheduled_date = $newScheduledDate;
        $returnSchedule = $this->cc->updateEmailCampaignSchedule(ACCESS_TOKEN, $params[self::CAMPAIGN_KEY], $schedule);
        $this->assertEquals($returnSchedule->scheduled_date, $newScheduledDate);
    }

    /**
     * @depends testAddSchedule
     */ 
    public function testDeleteCampaignSchedule(array $params)
    {
        $response = $this->cc->deleteEmailCampaignSchedule(ACCESS_TOKEN, $paras[self::CAMPAIGN_KEY], $params[self::SCHEDULE_KEY]);
        $this->assertTrue($response);
    }

    /**
     * @depends testAddSchedule
     */ 
    public function testSendTest(array $params)
    {
        $emailAddress = $params[CONTACT_KEY]->email_addresses[0]->email_address;

        $testSend = new TestSend();
        $testSend->addEmail($emailAddress);
        $testSend->format = "HTML";
        $returnTestSend = $this->cc->sendEmailCampaignTest(ACCESS_TOKEN, $params[self::CAMPAIGN_KEY], $testSend);
        $this->assertNotNull($returnTestSend);
        $this->assertContains($emailAddress);
    }

    /**
     * @depends testAddSchedule 
     */ 
    public function cleanUp(array $params)
    {
        $this->cc->deleteEmailCampaign(ACCESS_TOKEN, $params[self::CAMPAIGN_KEY]);
        $this->cc->deleteContact(ACCESS_TOKEN, $params[self::CONTACT_KEY]);
    }

    private static function createRandomEmail($domain = 'snoopy.roving.com')
    {
        return sprintf(time() . '%s%s%s', mt_rand(0, 99), mt_rand(0, 99), mt_rand(0, 99)) . '@' . $domain;
    }

    private static function createRandomName()
    {
        return "List " . sprintf(time() . '%s%s%s', mt_rand(0, 99), mt_rand(0, 99), mt_rand(0, 99));
    }  
}
