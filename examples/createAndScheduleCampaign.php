<!DOCTYPE HTML>
<html>
<head>
    <title>Constant Contact API v2 Create/Schedule Campaign Example</title>
    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>

<!--
README: Create and schedule an email marketing campaign for delivery
This example flow illustrates how to create a new email campaign, and schedule it to be sent to the selected contact lists(s). 
In order for this example to function properly, you must have a valid Constant Contact API Key as well as an access token. Both of these 
can be obtained from http://constantcontact.mashery.com.

NOTE: This example expects that your account already has a physical address set up in your Constant Contact account settings.
For more information on this, please visit: http://support2.constantcontact.com/articles/FAQ/2801#future%20emails
-->

<?php
// require the autoloader
require_once '../src/Ctct/autoload.php';

use Ctct\ConstantContact;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\EmailMarketing\Campaign;
use Ctct\Components\EmailMarketing\MessageFooter;
use Ctct\Components\EmailMarketing\Schedule;
use Ctct\Exceptions\CtctException;

// Enter your Constant Contact APIKEY and ACCESS_TOKEN
define("APIKEY", "ENTER YOUR API KEY");
define("ACCESS_TOKEN", "ENTER YOUR ACCESS TOKEN");

$cc = new ConstantContact(APIKEY);
$date = date('Y-m-d\TH:i:s\.000\Z', strtotime("+1 month"));

/**
 * Create an email campaign with the parameters provided
 * @param array $params associative array of parameters to create a campaign from
 */
function createCampaign(array $params)
{
    $cc = new ConstantContact(APIKEY);
    $campaign = new Campaign();
    $campaign->name = $params['name'];
    $campaign->subject = $params['subject'];
    $campaign->from_name = $params['from_name'];
    $campaign->from_email = $params['from_addr'];
    $campaign->greeting_string = $params['greeting_string'];
    $campaign->reply_to_email = $params['reply_to_addr'];
    $campaign->text_content = $params['text_content'];
    $campaign->email_content = $params['email_content'];
    $campaign->email_content_format = $params['format'];

    // add the selected list or lists to the campaign
    if (isset($params['lists'])) {
        if (count($params['lists']) > 1) {
            foreach ($params['lists'] as $list) {
                $campaign->addList($list);
            }
        } else {
            $campaign->addList($params['lists'][0]);
        }
    }

    return $cc->addEmailCampaign(ACCESS_TOKEN, $campaign);
}

/**
 * Create a schedule for a campaign - this is time the campaign will be sent out
 * @param $campaignId - Id of the campaign to be scheduled
 * @param $time - ISO 8601 formatted timestamp of when the campaign should be sent
 */
function createSchedule($campaignId, $time)
{
    $cc = new ConstantContact(APIKEY);
    $schedule = new Schedule();
    $schedule->scheduled_date = $time;
    return $cc->addEmailCampaignSchedule(ACCESS_TOKEN, $campaignId, $schedule);
}

// check to see if the form was submitted
if (isset($_POST['name'])) {

    // attempt to create a campaign with the fields submitted, displaying any errors that occur
    try {
        $campaign = createCampaign($_POST);
    } catch (CtctException $ex) {
        echo '<span class="label label-important">Error Creating Campaign</span>';
        echo '<div class="container alert-error"><pre class="failure-pre">';
        print_r($ex->getErrors());
        echo '</pre></div>';
        die();
    }

    // attempt to schedule a campaign with the fields submitted, displaying any errors that occur
    try {
        $schedule = createSchedule($campaign->id, $_POST['schedule_time']);
    } catch (CtctException $ex) {
        echo '<span class="label label-important">Error Scheduling Campaign</span>';
        echo '<div class="container alert-error"><pre class="failure-pre">';
        print_r($ex->getErrors());
        echo '</pre></div>';
        die();
    }

}

// attempt to get the lists in this account, displaying any errors that occur
try {
    $lists = $cc->getLists(ACCESS_TOKEN);
} catch (CtctException $ex) {
    echo '<div class="container alert-error"><pre class="failure-pre">';
    print_r($ex->getErrors());
    echo '</pre></div>';
    die();
}
?>

<body>
<div class="well">
    <h3>Create and Schedule a Campaign</h3>

    <form class="form-horizontal" name="emailForm" id="emailForm" method="POST" action="createAndScheduleCampaign.php">
        <div class="span6">
            <fieldset>
                <div class="control-group">
                    <label class="control-label" for="name">Campaign Name</label>

                    <div class="controls">
                        <input type="text" id="name" name="name" class="required" placeholder="Campaign Name">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="subject">Subject</label>

                    <div class="controls">
                        <input type="text" id="subject" name="subject" placeholder="Subject">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="from_name">From Name</label>

                    <div class="controls">
                        <input type="text" id="from_name" name="from_name" placeholder="From Name">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="from_addr">From Email</label>

                    <div class="controls">
                        <input type="email" id="from_addr" name="from_addr" placeholder="From Email">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="name">Text Content</label>

                    <div class="controls">
                        <textarea id="text_content" name="text_content" placeholder="Text Content"></textarea>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="email_content">Email Content</label>

                    <div class="controls">
                        <textarea id="email_content" name="email_content" placeholder="Email Content"></textarea>
                    </div>
                </div>
            </fieldset>
        </div>

        <div class="span6">
            <fieldset>
                <div class="control-group">
                    <label class="control-label" for="greeting_string">Greeting String</label>

                    <div class="controls">
                        <input type="text" id="greeting_string" name="greeting_string" placeholder="Greeting String">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="reply_to_addr">Reply-To Email</label>

                    <div class="controls">
                        <input type="email" id="reply_to_addr" name="reply_to_addr" placeholder="Reply To">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="format">Lists to send to: </label>

                    <div class="controls">
                        <select multiple="multiple" name="lists[]" size="8">
                            <?php
                            foreach ($lists as $list) {
                                echo '<option value="' . $list->id . '" >' . $list->name . '</option><br />';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="format">Send Time</label>

                    <div class="controls">
                        <input type="text" name="schedule_time" id="schedule_time"
                               value="<?php echo date('Y-m-d\TH:i:s\.000\Z', strtotime("+1 month"));
                               ; ?>"/>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="format">Email Content Format</label>

                    <div class="controls">
                        <input type="radio" id="name" name="format" value="HTML" checked> HTML </input>
                        <input type="radio" id="name" name="format" value="XHTML"> XHTML </input>
                    </div>
                </div>
            </fieldset>
        </div>
        <br clear="all"/>

        <div class="control-group">
            <label class="control-label">
                <div class="controls">
                    <input type="submit" value="Create & Schedule" class="btn btn-primary"/>
                </div>
        </div>

    </form>
</div>

<?php

// print the contents of the campaign to screen
if (isset($campaign)) {
    echo '<span class="label label-success">Campaign Created!</span>';
    echo '<div class="container alert-success"><pre class="success-pre">';
    print_r($campaign);
    echo '</pre></div>';
}

// print the contents of the schedule to screen
if (isset($schedule)) {
    echo '<span class="label label-success">Campaign Scheduled!</span>';
    echo '<div class="container alert-success"><pre class="success-pre">';
    print_r($schedule);
    echo '</pre></div>';
}
?>

</body>
</html>