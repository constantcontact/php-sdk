<?php
namespace Ctct\Components\EmailMarketing;

use Ctct\Components\Component;
use Ctct\Util\Config;
use Ctct\Components\Tracking\TrackingSummary;
use Ctct\Components\Contacts\ContactList;
use Ctct\Exceptions\IllegalArgumentException;

/**
 * Represents a single Campaign in Constant Contact
 *
 * @package        Components
 * @subpackage     EmailMarketing
 * @author         Constant Contact
 */
class Campaign extends Component
{
    /**
     * Unique identifier for the email campaign
     * @var string
     */
    public $id;

    /**
     * Name of the email campaign; each email campaign name must be unique within a user's account
     * @var string
     */
    public $name;

    /**
     * The Subject Line for the email campaign
     * @var string
     */
    public $subject;

    /**
     * Current status of the email campaign
     * @var string
     */
    public $status;

    /**
     * Name displayed in the From field to indicate whom the email is from
     * @var string
     */
    public $from_name;

    /**
     * The email address the email campaign originated from, this must be a verified email address for the account owner
     * @var string
     */
    public $from_email;

    /**
     * The reply-to email address for the email campaign, this must be a verified email address for the account owner
     * @var string
     */
    public $reply_to_email;

    /**
     * The template used to create the email campaign
     * @var string
     */
    public $template_type;

    /**
     * Date the email campaign was last sent to contacts, in ISO-8601 format
     * @var string
     */
    public $created_date;

    /**
     * Date the email campaign was last modified, in ISO-8601 format
     * @var string
     */
    public $modified_date;

    /**
     * Date the email campaign was last run, in ISO-8601 format
     * @var string
     */
    public $last_run_date;

    /**
     * Date the email campaign is next scheduled to run and be sent to contacts, in ISO-8601 format
     * @var string
     */
    public $next_run_date;

    /**
     * If true, displays permission_reminder_text at top of email message
     * @var boolean
     */
    public $is_permission_reminder_enabled;

    /**
     * Text to be displayed at the top of the email if is_permission_reminder_enabled is true
     * @var string
     */
    public $permission_reminder_text;

    /**
     * If true, displays the text and link specified in permission_reminder_text to view web page
     * version of email message
     * @var string
     */
    public $is_view_as_webpage_enabled;

    /**
     * Text to be displayed if is_view_as_webpage_enabled is true
     * @var string
     */
    public $view_as_web_page_text;

    /**
     * Text that will be displayed as the link if is_view_as_webpage_enabled is true
     * @var string
     */
    public $view_as_web_page_link_text;

    /**
     * The salutation used in the email message (e.g. Dear)
     * @var string
     */
    public $greeting_salutations;

    /**
     * This is the personalized content for each contact that will be used in the greeting
     * @var string
     */
    public $greeting_name;

    /**
     * Specifies the greeting text used if not using greeting_name and greeting_salutations
     * @var string
     */
    public $greeting_string;

    /**
     * Defines the content of the email campaign message footer
     * @var MessageFooter
     */
    public $message_footer;

    /**
     * Campaign Tracking summary data for this campaign
     * @var TrackingSummary
     */
    public $tracking_summary;

    /**
     * The full HTML or XHTML content of the email campaign
     * @var string
     */
    public $email_content;

    /**
     * Specifies the email campaign message format, valid values: HTML, XHTML
     * @var string
     */
    public $email_content_format;

    /**
     * Style sheet used in the email
     * @var string
     */
    public $style_sheet;

    /**
     * The content for the text-only version of the email campaign which is viewed by recipients
     * whose email client does not accept HTML email
     * @var string
     */
    public $text_content;

    /**
     * Unique IDs of the contact lists the email campaign message is sent to
     * @var array
     */
    public $sent_to_contact_lists = array();

    /**
     * Tracking summary data for this email campaign
     * @var array
     */
    public $click_through_details = array();

    /**
     * URL of the permalink for this email campaign if it exists
     * @var string
     */
    public $permalink_url;

    /**
     * Factory method to create a Campaign object from an array
     * @param array $props - associative array of initial properties to set
     * @return Campaign
     */
    public static function create(array $props)
    {
        $campaign = new Campaign();
        $campaign->id = parent::getValue($props, "id");
        $campaign->name = parent::getValue($props, "name");
        $campaign->subject = parent::getValue($props, "subject");
        $campaign->from_name = parent::getValue($props, "from_name");
        $campaign->from_email = parent::getValue($props, "from_email");
        $campaign->reply_to_email = parent::getValue($props, "reply_to_email");
        $campaign->template_type = parent::getValue($props, "template_type");
        $campaign->created_date = parent::getValue($props, "created_date");
        $campaign->modified_date = parent::getValue($props, "modified_date");
        $campaign->last_run_date = parent::getValue($props, "last_run_date");
        $campaign->next_run_date = parent::getValue($props, "next_run_date");
        $campaign->status = parent::getValue($props, "status");
        $campaign->is_permission_reminder_enabled = parent::getValue($props, "is_permission_reminder_enabled");
        $campaign->permission_reminder_text = parent::getValue($props, "permission_reminder_text");
        $campaign->is_view_as_webpage_enabled = parent::getValue($props, "is_view_as_webpage_enabled");
        $campaign->view_as_web_page_text = parent::getValue($props, "view_as_web_page_text");
        $campaign->view_as_web_page_link_text = parent::getValue($props, "view_as_web_page_link_text");
        $campaign->greeting_salutations = parent::getValue($props, "greeting_salutations");
        $campaign->greeting_name = parent::getValue($props, "greeting_name");
        $campaign->greeting_string = parent::getValue($props, "greeting_string");

        if (array_key_exists("message_footer", $props)) {
            $campaign->message_footer = MessageFooter::create($props['message_footer']);
        }

        if (array_key_exists("tracking_summary", $props)) {
            $campaign->tracking_summary = TrackingSummary::create($props['tracking_summary']);
        }

        $campaign->email_content = parent::getValue($props, "email_content");
        $campaign->email_content_format = parent::getValue($props, "email_content_format");
        $campaign->style_sheet = parent::getValue($props, "style_sheet");
        $campaign->text_content = parent::getValue($props, "text_content");
        $campaign->permalink_url = parent::getValue($props, "permalink_url");

        if (array_key_exists('sent_to_contact_lists', $props)) {
            foreach ($props['sent_to_contact_lists'] as $sent_to_contact_list) {
                $campaign->sent_to_contact_lists[] = ContactList::create($sent_to_contact_list);
            }
        }

        if (array_key_exists('click_through_details', $props)) {
            foreach ($props['click_through_details'] as $click_through_details) {
                $campaign->click_through_details[] = ClickThroughDetails::create($click_through_details);
            }
        }

        return $campaign;
    }

    /**
     * Factory method to create a Campaign object from an array
     * @param array $props - associative array of initial properties to set
     * @return Campaign
     */
    public static function createSummary(array $props)
    {
        $campaign = new Campaign();
        $campaign->id = parent::getValue($props, "id");
        $campaign->name = parent::getValue($props, "name");
        $campaign->status = parent::getValue($props, "status");
        $campaign->modified_date = parent::getValue($props, "modified_date");

        // remove unused fields
        foreach ($campaign as $key => $value) {
            if ($value == null) {
                unset($campaign->$key);
            }
        }

        return $campaign;
    }

    /**
     * Add a contact list to set of lists associated with this email
     * @param mixed $contact_list - Contact list id, or ContactList object
     * @throws IllegalArgumentException
     */
    public function addList($contact_list)
    {
        if ($contact_list instanceof ContactList) {
            $list = $contact_list;
        } elseif (is_numeric($contact_list)) {
            $list = new ContactList($contact_list);
        } else {
            throw new IllegalArgumentException(sprintf(Config::get('errors.id_or_object'), 'ContactList'));
        }

        $this->sent_to_contact_lists[] = $list;
    }

    /**
     * Create json used for a POST/PUT request, also handles removing attributes that will cause errors if sent
     * @return string
     */
    public function toJson()
    {
        $campaign = clone $this;
        unset($campaign->id);
        unset($campaign->created_date);
        unset($campaign->last_run_date);
        unset($campaign->next_run_date);
        unset($campaign->tracking_summary);
        unset($campaign->click_through_details);

        if (is_null($campaign->message_footer)) {
            unset($campaign->message_footer);
        }

        if (empty($campaign->sent_to_contact_lists)) {
            unset($campaign->sent_to_contact_lists);
        } else {

            // remove sent_to_contact_lists fields that cause errors
            foreach ($campaign->sent_to_contact_lists as $list) {
                unset($list->name);
                unset($list->contact_count);
                unset($list->status);
            }
        }

        return json_encode($campaign);
    }
}
