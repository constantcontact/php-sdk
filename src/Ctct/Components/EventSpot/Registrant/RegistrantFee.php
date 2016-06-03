<?php
namespace Ctct\Components\EventSpot\Registrant;

use Ctct\Components\Component;

/**
 * Represents a single Promo Code
 *
 * @package        Components
 * @subpackage     EventSpot\Registrant
 * @author         Katz Web Services, Inc.
 */
class RegistrantFee extends Component
{
	/**
	 * Unique ID of the fee that the registrant paid
	 * @var string
	 */
	public $id;

	/**
	 * Full name of the registrant or guest
	 * @var string
	 */
	public $name;

	/**
	 * The name of the fee that the registrant paid
	 * @var string
	 */
	public $type;

	/**
	 * Percentage of discount, if discount_type = PERCENTAGE
	 * @var int
	 */
	public $quantity;

	/**
	 * The fee amount
	 * @var float
	 */
	public $amount;

	/**
	 * Identifies if the fee paid was an early, late, or regular fee
	 * @var string
	 */
	public $fee_period_type;

	/**
	 * The type of promotional code redeemed, ACCESS or DISCOUNT
	 * @internal May be deprecated.
	 * @var string
	 */
	public $promo_type;

	/**
	 * Factory method to create a EventList object from an array
	 * @param array $props - Associative array of initial properties to set
	 * @return RegistrantPromoCodeInfo
	 */
	public static function create(array $props)
	{
		$registrant_fee = new RegistrantFee();
		$registrant_fee->id = parent::getValue($props, "id");
		$registrant_fee->name = parent::getValue($props, "name");
		$registrant_fee->type = parent::getValue($props, "type");
		$registrant_fee->quantity = parent::getValue($props, "quantity");
		$registrant_fee->amount = parent::getValue($props, "amount");
		$registrant_fee->fee_period_type = parent::getValue($props, "fee_period_type");
		$registrant_fee->promo_type = parent::getValue($props, "promo_type");

		return $registrant_fee;
	}
}
