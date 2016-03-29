<?php
namespace Ctct\Components\EventSpot;

use Ctct\Components\Component;
use Ctct\Components\EventSpot\Registrant\RegistrantOrder;
use Ctct\Components\EventSpot\Registrant\RegistrantPromoCode;

/**
 * Represents a single Contact List
 *
 * @package        Components
 * @subpackage     Contacts
 * @author         Constant Contact
 */
class PaymentSummary extends Component
{
	/**
	 * Displays the payment_status and payment_type
	 *   payment_status - Registrant's payment status:
	 *      PENDING - default for cash (at door) or check payments
	 *      NA - default free event status
	 *      INCOMPLETE - default PayPal status
	 *      FAILED - PayPal payment failed
	 *      REFUNDED - Fee refunded to registrant (for PayPal only)
	 *      PAID
	 * @var string
	 */
	public $payment_status;

	/**
	 * Payment type registrant used - CHECK, DOOR, ONLINE_CREDIT_CARD_PROCESSOR, PAYPAL, GOOGLE_CHECKOUT
	 * @var string (100)
	 */
	public $payment_type;

	/**
	 * Array of properties showing the registrant's total order.
	 * @var RegistrantOrder
	 */
	public $order;

	/**
	 * @var RegistrantPromoCode
	 */
	public $promo_code;

	/**
	 * Factory method to create a EventList object from an array
	 * @param array $props - Associative array of initial properties to set
	 * @return PaymentSummary
	 */
	public static function create(array $props)
	{
		$payment_summary = new PaymentSummary();
		$payment_summary->payment_status = parent::getValue($props, "payment_status");
		$payment_summary->payment_type = parent::getValue($props, "payment_type");

		if( isset($props["order"])) {
			$payment_summary->order = RegistrantOrder::create( parent::getValue( $props, "order" ) );
		}

		if (!empty($props['promo_code'])) {
			$payment_summary->promo_code = RegistrantPromoCode::create($props['promo_code']);
		}

		return $payment_summary;
	}

	public function toJson()
	{
		return json_encode($this);
	}
}
