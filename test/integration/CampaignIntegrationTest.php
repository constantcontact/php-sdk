<?php

use Ctct\ConstantContact;
use Ctct\Components\EmailMarketing\Campaign;
use Ctct\Exceptions\CtctException;

class CampaignIntegrationTest extends PHPUnit_Framework_TestCase{
        
    public $cc; 

    public function setUp()
    {
        $this->cc = new ConstantContact(APIKEY);
    }

    public function testAddEmailCampaign()
    {
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

        $returnedCampaign = $this->cc->addEmailCampaign(ACCESS_TOKEN, $campaign);    
        $this->assertNotNull($returnedCampaign);
        $this->assertEquals($campaignName, $returnedCampaign->name);
        $this->assertEquals(VERIFIED_EMAIL_ADDRESS, $returnedCampaign->from_email);
        
        return $returnedCampaign;
    }

    /**
     * @depends testAddEmailCampaign
     */ 
    public function testGetEmailCampaign(Campaign $campaign)
    {
        $returnedCampaign = $this->cc->getEmailCampaign(ACCESS_TOKEN, $campaign->id);
        $this->assertEquals($campaign->id, $returnedCampaign->id);
        $this->assertEquals($campaign->subject, $returnedCampaign->subject);

        return $returnedCampaign;
    }

    /**
     * @depends testAddEmailCampaign
     */ 
    public function testUpdateEmailCampaign(Campaign $campaign)
    {
        $subject = "An even newer subject line";
        $campaign->subject = $subject;
        
        //TODO: BUG
        $campaign->message_footer->state = "MA";
        $campaign->message_footer->country = "US";

        $returnedCampaign = $this->cc->updateEmailCampaign(ACCESS_TOKEN, $campaign);

        $this->assertEquals($campaign->id, $returnedCampaign->id);
        $this->assertEquals($campaign->subject, $returnedCampaign->subject);
    }

    public function testGetEmailCampaigns()
    {
        $resultSet = $this->cc->getEmailCampaigns(ACCESS_TOKEN);
        $this->assertGreaterThan(0, count($resultSet->results));
    }

    public function testGetEmailCampaignsWithLimit()
    {
        $resultSet = $this->cc->getEmailCampaigns(ACCESS_TOKEN, 1);
        $this->assertEquals(1, count($resultSet->results));
        $this->assertNotNull($resultSet->next);
        return $resultSet->next;
    }   

    /**
     * @depends testGetEmailCampaignsWithLimit
     */ 
    public function testGetEmailCampaignsWithLimitNextPage($next)
    {
        $this->markTestSkipped("WSPI-3326: Query parameter validation failing when including 'next' and 'api_key' parameters");
        $resultSet = $this->cc->getEmailCampaigns(ACCESS_TOKEN, $next);
        $this->assertEquals(1, count($resultSet->results));
        $this->assertNotNull($resultSet->next);
    }   

    /**
     * @depends testAddEmailCampaign
     */ 
    public function testDeleteEmailCampaign(Campaign $campaign) 
    {
        $result = $this->cc->deleteEmailCampaign(ACCESS_TOKEN, $campaign);
        $this->assertTrue($result);
    }
}
