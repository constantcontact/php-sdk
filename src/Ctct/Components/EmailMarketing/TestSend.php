<?php
namespace Ctct\Components\EmailMarketing;

use Ctct\Components\Component;

/**
 * Represents a campaign Test Send in Constant Contact
 *
 * @package        Components
 * @subpackage     EmailMarketing
 * @author         Constant Contact
 */
class TestSend extends Component
{
    /**
     * Format of the email to send (HTML, TEXT, HTML_AND_TEXT)
     * @var string
     */
    public $format;

    /**
     * Personal message to send along with the test send
     * @var
     */
    public $personal_message;

    /**
     * Array of email addresses to send the test send to
     * @var array
     */
    public $email_addresses = array();

    /**
     * Factory method to create a TestSend object from an array
     * @param array $props - associative array of initial properties to set
     * @return TestSend
     */
    public static function create(array $props)
    {
        $test_send = new TestSend();
        $test_send->format = parent::getValue($props, "format");
        $test_send->personal_message = parent::getValue($props, "personal_message");

        foreach ($props['email_addresses'] as $email_address) {
            $test_send->email_addresses[] = $email_address;
        }

        return $test_send;
    }

    /**
     * Add an email address to the set of addresses to send the test send too
     * @param string $email_address
     */
    public function addEmail($email_address)
    {
        $this->email_addresses[] = $email_address;
    }

    /**
     * Create json used for a POST/PUT request, also handles removing attributes that will cause errors if sent
     * @return string
     */
    public function toJson()
    {
        $testSend = clone $this;
        if ($testSend->personal_message == null) {
            unset($testSend->personal_message);
        }
        return json_encode($testSend);
    }
}
