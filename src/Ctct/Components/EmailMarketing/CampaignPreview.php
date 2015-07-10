<?php
namespace Ctct\Components\EmailMarketing;

use Ctct\Components\Component;

class CampaignPreview extends Component {
    /**
     * Email address set as the from email
     * @var String
     */
    public $fromEmail;

    /**
     * Email address set as the reply-to email
     * @var String
     */
    public $replyToEmail;

    /**
     * Full HTML content of the campaign
     * @var String
     */
    public $htmlContent;

    /**
     * Text version content of the campaign
     * @var
     */
    public $textContent;

    /**
     * Subject of the email
     * @var String
     */
    public $subject;

    /**
     * Factory method to create a CampaignPreview object from an array
     * @param array $props - associative array of initial properties to set
     * @return CampaignPreview
     */
    public static function create(array $props) {
        $preview = new CampaignPreview();
        $preview->fromEmail = parent::getValue($props, "from_email");
        $preview->replyToEmail = parent::getValue($props, "reply_to_email");
        $preview->htmlContent = parent::getValue($props, "preview_email_content");
        $preview->textContent = parent::getValue($props, "preview_text_content");
        $preview->subject = parent::getValue($props, "subject");
        return $preview;
    }
}