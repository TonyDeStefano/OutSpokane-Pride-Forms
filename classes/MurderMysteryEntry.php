<?php
/**
 * Created by PhpStorm.
 * User: Tony DeStefano
 * Date: 2/1/16
 * Time: 4:00 PM
 */

namespace OutSpokane;


class MurderMysteryEntry extends Entry {

	const TABLE_NAME = 'out_spokane_murder_mystery_entries';

	private $vegetarian_qty;
	private $is_sponsor = FALSE;
	private $is_upgraded = FALSE;

	/**
	 * MurderMysteryEntry constructor.
	 *
	 * @param null $id
	 */
	public function __construct( $id=NULL ) {
		$this->setTableName(self::TABLE_NAME);
		parent::__construct( $id );
	}

	/**
	 * @return mixed
	 */
	public function getVegetarianQty() {
		return ($this->vegetarian_qty === NULL) ? 0 : $this->vegetarian_qty;
	}

	/**
	 * @param mixed $vegetarian_qty
	 *
	 * @return MurderMysteryEntry
	 */
	public function setVegetarianQty( $vegetarian_qty ) {
		$this->vegetarian_qty = (is_numeric($vegetarian_qty)) ? abs(round($vegetarian_qty)) : NULL;

		return $this;
	}

	/**
	 * @return boolean
	 */
	public function isSponsor() {
		return ($this->is_sponsor === TRUE) ? TRUE : FALSE;
	}

	/**
	 * @param boolean $is_sponsor
	 *
	 * @return MurderMysteryEntry
	 */
	public function setIsSponsor( $is_sponsor ) {
		$this->is_sponsor = ($is_sponsor == 1 || $is_sponsor === TRUE) ? TRUE : FALSE;

		return $this;
	}

	/**
	 * @return boolean
	 */
	public function isUpgraded() {
		return ($this->is_upgraded === TRUE) ? TRUE : FALSE;
	}

	/**
	 * @param boolean $is_upgraded
	 *
	 * @return MurderMysteryEntry
	 */
	public function setIsUpgraded( $is_upgraded ) {
		$this->is_upgraded = ($is_upgraded == 1 || $is_upgraded === TRUE) ? TRUE : FALSE;

		return $this;
	}

	/**
	 * @param bool $use_parent
	 *
	 * @return array
	 */
	public function getPaymentMethods( $use_parent=FALSE )
	{
		if ( $use_parent )
		{
			return parent::getPaymentMethods();
		}

		return array(
			self::PAYMENT_METHOD_CARD => $this->getPaymentMethod( self::PAYMENT_METHOD_CARD ),
			self::PAYMENT_METHOD_CHECK => $this->getPaymentMethod( self::PAYMENT_METHOD_CHECK )
		);
	}
}