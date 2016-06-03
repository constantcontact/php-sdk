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
class RegistrantPromoCodeInfo extends Component
{
	/**
	 * The promocode name
	 * @var string
	 */
	public $code_name;

	/**
	 * Type of promocode redeemed, ACCESS or DISCOUNT
	 * @var string
	 */
	public $code_type;

	/**
	 * Amount of each discount, if discount_type = AMOUNT
	 * @var float
	 */
	public $discount_amount;

	/**
	 * Percentage of discount, if discount_type = PERCENTAGE
	 * @var float|int
	 */
	public $discount_percent;

	/**
	 * Discount scope - FEE_LIST or ORDER_TOTAL
	 * @var string
	 */
	public $discount_scope;

	/**
	 * Discount type - either PERCENTAGE or AMOUNT
	 * @var string
	 */
	public $discount_type;

	/**
	 * Number of promocodes redeemed in this order
	 * @var int
	 */
	public $redemption_count;

	/**
	 * Factory method to create a EventList object from an array
	 * @param array $props - Associative array of initial properties to set
	 * @return RegistrantPromoCodeInfo
	 */
	public static function create(array $props)
	{
		$registrant_promo_code_info = new RegistrantPromoCodeInfo();
		$registrant_promo_code_info->code_name = parent::getValue($props, "code_name");
		$registrant_promo_code_info->code_type = parent::getValue($props, "code_type");
		$registrant_promo_code_info->discount_amount = parent::getValue($props, "discount_amount");
		$registrant_promo_code_info->discount_percent = parent::getValue($props, "discount_percent");
		$registrant_promo_code_info->discount_scope = parent::getValue($props, "discount_scope");
		$registrant_promo_code_info->discount_type = parent::getValue($props, "discount_type");
		$registrant_promo_code_info->redemption_count = parent::getValue($props, "redemption_count");

		return $registrant_promo_code_info;
	}
}
