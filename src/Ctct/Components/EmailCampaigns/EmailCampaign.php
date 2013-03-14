<?php
namespace Ctct\Components\EmailCampaigns;
 
use Ctct\Components\Component;
use Ctct\Util\Config;
use Ctct\Components\EmailCampaigns\MessageFooter;
use Ctct\Components\Tracking\TrackingSummary;
use Ctct\Components\EmailCampaigns\ClickThroughDetails;
use Ctct\Components\Contacts\ContactList;

/**
 * Represents a single Campaign in Constant Contact
 *
 * @package     Components
 * @subpackage     EmailCampaigns
 * @author         Constant Contact
 */
class EmailCampaign extends Component
{
    public $id;
    public $name;
    public $subject;
    public $status;
    public $from_name;
    public $from_email;
    public $reply_to_email;
    public $template_type;
    public $created_date;
    public $modified_date;
    public $last_send_date;
    public $last_edit_date;
    public $last_run_date;
    public $next_run_date;
    public $is_permission_reminder_enabled;
    public $permission_reminder_text;
    public $is_view_as_webpage_enabled;
    public $view_as_web_page_text;
    public $view_as_web_page_link_text;
    public $greeting_salutations;
    public $greeting_name;
    public $greeting_string;
    public $message_footer;
    public $tracking_summary;
    public $email_content;
    public $email_content_format;
    public $style_sheet;
    public $text_content;
    public $sent_to_contact_lists = array();
    public $click_through_details = array();

    /**
     * Factory method to create a Campaign object from an array
     * @param array $props - associative array of initial properties to set
     * @return Campaign
     */
    public static function create(array $props)
    {
        $campaign = new EmailCampaign();
        $campaign->id = parent::getValue($props, "id");
        $campaign->name = parent::getValue($props, "name");
        $campaign->subject = parent::getValue($props, "subject");
        $campaign->from_name = parent::getValue($props, "from_name");
        $campaign->from_email = parent::getValue($props, "from_email");
        $campaign->reply_to_email = parent::getValue($props, "reply_to_email");
        $campaign->template_type = parent::getValue($props, "template_type");
        $campaign->created_date = parent::getValue($props, "created_date");
        $campaign->modified_date = parent::getValue($props, "modified_date");
        $campaign->last_send_date = parent::getValue($props, "last_send_date");
        $campaign->last_edit_date = parent::getValue($props, "last_edit_date");
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
     * @return EmailCampaign
     */
    public static function createSummary(array $props)
    {
        $campaign = new EmailCampaign();
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
        unset($this->last_send_date);
        unset($this->id);
        unset($this->created_date);
        unset($this->last_run_date);
        unset($this->next_run_date);
        unset($this->tracking_summary);
        unset($this->click_through_details);
        unset($this->last_edit_date);

        if (is_null($this->message_footer)) {
            unset($this->message_footer);
        }

        if (empty($this->sent_to_contact_lists)) {
            unset($this->sent_to_contact_lists);
        }
    
        return json_encode($this);
    }
}
