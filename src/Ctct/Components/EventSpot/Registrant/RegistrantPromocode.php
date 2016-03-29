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
class RegistrantPromoCode extends Component
{
	/**
	 * Unique identifier of the contact list
	 * @var float|int
	 */
	public $total_discount;

	/**
	 * Displays information for any promocodes redeemed with this order.
	 * @var RegistrantPromoCodeInfo
	 */
	public $promo_code_info;

	/**
	 * Factory method to create a EventList object from an array
	 * @param array $props - Associative array of initial properties to set
	 * @return RegistrantPromoCode
	 */
	public static function create(array $props)
	{
		$registrant_promo_code = new RegistrantPromoCode();
		$registrant_promo_code->total_discount = parent::getValue($props, "total_discount");
		$registrant_promo_code->promo_code_info = RegistrantPromoCodeInfo::create(parent::getValue($props, "promo_code_info"));
		return $registrant_promo_code;
	}
}
