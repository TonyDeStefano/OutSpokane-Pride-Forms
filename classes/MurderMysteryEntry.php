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
	const TICKET_PRICE = 35;
	const UPGRADED_TICKET_PRICE = 50;
	const TABLE_PRICE = 350;
	const UPGRADED_TABLE_PRICE = 500;
	const MAX_TICKETS = 20;

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
		$this->read();
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

	/**
	 *
	 */
	public function create()
	{
		global $wpdb;

		$wpdb->insert(
			$wpdb->prefix . self::TABLE_NAME,
			array(
				'entry_year' => $this->entry_year,
				'email' => $this->email,
				'phone' => $this->phone,
				'organization' => $this->organization,
				'first_name' => $this->first_name,
				'last_name' => $this->last_name,
				'address' => $this->address,
				'city' => $this->city,
				'state' => $this->state,
				'zip' => $this->zip,
				'qty' => $this->qty,
				'price_per_qty' => $this->price_per_qty,
				'payment_method_id' => $this->payment_method_id,
				'vegetarian_qty' => $this->vegetarian_qty,
				'is_sponsor' => ($this->isSponsor()) ? 1 : 0,
				'is_upgraded' => ($this->isUpgraded()) ? 1 : 0,
				'created_at' => $this->getCreatedAt( 'Y-m-d H:i:s' ),
				'updated_at' => $this->getUpdatedAt( 'Y-m-d H:i:s' )
			),
			array(
				'%d',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%d',
				'%f',
				'%d',
				'%d',
				'%d',
				'%d',
				'%s',
				'%s',
			)
		);

		$this->id = $wpdb->insert_id;
	}

	/**
	 *
	 */
	public function read()
	{
		if ( $row = parent::read() )
		{
			$this->loadFromRow( $row );
		}
	}

	/**
	 *
	 */
	public function update()
	{
		if ( $this->id !== NULL )
		{
			parent::update();
		}
	}

	/**
	 * @param \stdClass $row
	 */
	public function loadFromRow( \stdClass $row )
	{
		parent::loadFromRow( $row );
		$this
			->setIsSponsor( $row->is_sponsor )
			->setIsUpgraded( $row->is_upgraded )
			->setVegetarianQty( $row->vegetarian_qty );
	}
}