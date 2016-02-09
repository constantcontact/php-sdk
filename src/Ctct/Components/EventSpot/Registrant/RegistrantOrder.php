<?php
namespace Ctct\Components\EventSpot\Registrant;

use Ctct\Components\Component;
use Ctct\Components\EventSpot\Registrant\RegistrantFee;

/**
 * Represents an order placed by an event Registrant
 *
 * @package        Components
 * @subpackage     EventSpot\Registrant
 * @author         Katz Web Services, Inc.
 */
class RegistrantOrder extends Component
{
	/**
	 * Order ID
	 * @var string
	 */
	public $order_id;

	/**
	 * Date and time the order was placed, in ISO-8601 format.
	 * @var string
	 */
	public $order_date;

	/**
	 * Currency type used
	 * @var string
	 */
	public $currency_type;

	/**
	 * Identifies if the fee paid was an early, late, or regular fee
	 * @var float
	 */
	public $total;

	/**
	 * An array of fee properties.
	 * @var RegistrantFee[]
	 */
	public $fees = array();

	/**
	 * Factory method to create a EventList object from an array
	 * @param array $props - Associative array of initial properties to set
	 * @return RegistrantPromoCodeInfo
	 */
	public static function create(array $props)
	{
		$registrant_order = new RegistrantOrder();
		$registrant_order->order_id = parent::getValue($props, "order_id");
		$registrant_order->order_date = parent::getValue($props, "order_date");
		$registrant_order->currency_type = parent::getValue($props, "currency_type");
		$registrant_order->total = parent::getValue($props, "total");

		if( isset($props['fees']) ) {
			foreach ( $props['fees'] as $fee ) {
				$registrant_order->fees[] = RegistrantFee::create($fee);
			}
		}

		return $registrant_order;
	}
}
