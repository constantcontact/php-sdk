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
class Promocode extends Component
{
	/**
	 * Name of the promotional code visible to registrants, between 4 - 12 characters, cannot contain spaces or special character (_ is OK); each code_name must be unique
	 * @var string
	 */
	public $code_name;

	/**
	 * Type of promocode redeemed, ACCESS or DISCOUNT
	 *  ACCESS - applies to a specific fee with has_restricted_access` = true, `fee_list` must include only a single `fee_id`.
	 *  DISCOUNT - when set to DISCOUNT, you must specify either a `discount_percent` or a `discount_amount`
	 * @var string
	 */
	public $code_type;

	/**
	 * Specifies a fixed discount amount, minimum of 0.01, is required when `code_type` = DISCOUNT, but not using `discount_percent`
	 * @var float
	 */
	public $discount_amount;

	/**
	 * Specifies a discount percentage, from 1% - 100%, is required when `code_type` = DISCOUNT, but not using `discount_amount`
	 * @var float|int
	 */
	public $discount_percent;

	/**
	 * Discount scope - FEE_LIST or ORDER_TOTAL
	 * @var string
	 */
	public $discount_scope;

	/**
	 * Discount types:
	 *  PERCENT - discount is a percentage specified by `discount_percent`
	 *  AMOUNT - discount is a fixed amount, specified by `discount_amount`
	 * @var string
	 */
	public $discount_type;

	/**
	 * Identifies the fees to which the promocode applies;
	 *  If `code_type` = ACCESS promocode applies to a single fee with `has_restricted_access` = true, include only 1 fee id
	 *  If `code_type` = DISCOUNT and `discount_scope` = ORDER_TOTAL, do not include any `fee_ids`
	 *  If `code_type` = DISCOUNT and `discount_scope` = FEE_LIST, then include all `fee_ids` to which the discount applies
	 * @var array
	 */
	public $fee_ids;

	/**
	 * Unique ID for the event promotional code
	 * @var string (50)
	 */
	public $id;

	/**
	 * When set to true, promocode cannot be redeemed; when false, promocode can be redeemed; default = false.
	 * @var boolean
	 */
	public $is_paused;

	/**
	 * Number of promocodes available for redemption; -1 = unlimited.
	 * @var integer
	 */
	public $quantity_available;

	/**
	 * Total number of promocodes available for redemption; -1 = unlimited.
	 * @var integer
	 */
	public $quantity_total;

	/**
	 * Number of promocodes that have been redeemed; starts at 0.
	 * @var integer
	 */
	public $quantity_used;

	/**
	 * Status of the promocode:
	 *  LIVE - promocode is available to be redeemed
	 *  PAUSED - promocode is not available for redemption
	 *  DEPLETED - no more promocodes remain, quantity_available = 0
	 * @var string
	 */
	public $status;

	/**
	 * Factory method to create a EventList object from an array
	 * @param array $props - Associative array of initial properties to set
	 * @return RegistrantPromoCodeInfo
	 */
	public static function create(array $props)
	{
		$promocode = new RegistrantPromoCodeInfo();
		$promocode->code_name = parent::getValue($props, "code_name");
		$promocode->code_type = parent::getValue($props, "code_type");
		$promocode->discount_amount = parent::getValue($props, "discount_amount");
		$promocode->discount_percent = parent::getValue($props, "discount_percent");
		$promocode->discount_scope = parent::getValue($props, "discount_scope");
		$promocode->discount_type = parent::getValue($props, "discount_type");
		$promocode->id = parent::getValue($props, "id");
		$promocode->fee_ids = parent::getValue($props, "fee_ids");
		$promocode->is_paused = parent::getValue($props, "is_paused");
		$promocode->quantity_available = parent::getValue($props, "quantity_available");
		$promocode->quantity_total = parent::getValue($props, "quantity_total");
		$promocode->quantity_used = parent::getValue($props, "quantity_used");
		$promocode->status = parent::getValue($props, "status");

		return $promocode;
	}
}
