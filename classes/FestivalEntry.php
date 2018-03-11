<?php
/**
 * Created by PhpStorm.
 * User: Tony DeStefano
 * Date: 2/1/16
 * Time: 3:59 PM
 */

namespace OutSpokane;


class FestivalEntry extends Entry {

	const TABLE_NAME = 'out_spokane_festival_entries';

	const ENTRY_TYPE_NON_PROFIT = 1;
	const ENTRY_TYPE_POLITICAL = 2;
	const ENTRY_TYPE_COMMERCIAL = 3;
	const ENTRY_TYPE_SPONSOR = 4;

	const ENTRY_FEE_NON_PROFIT = 50;
	const ENTRY_FEE_POLITICAL = 150;
	const ENTRY_FEE_COMMERCIAL = 200;
	const ENTRY_FEE_SPONSOR = 0;

	const CORNER_BOOTH_FEE = 50;

	private $entry_type_id;
	private $is_corner_booth = FALSE;
	private $price_for_corner_booth;
	private $description;

	/**
	 * FestivalEntry constructor.
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
	public function getEntryTypeId() {
		return $this->entry_type_id;
	}

	/**
	 * @param mixed $entry_type_id
	 *
	 * @return FestivalEntry
	 */
	public function setEntryTypeId( $entry_type_id ) {
		$this->entry_type_id = (in_array($entry_type_id, array(1, 2, 3, 4))) ? $entry_type_id : NULL;

		return $this;
	}

	/**
	 * @param null $entry_type_id
	 *
	 * @return string
	 */
	public function getEntryType( $entry_type_id=NULL )
	{
		$entry_type_id = ($entry_type_id === NULL) ? $this->entry_type_id : $entry_type_id;

		switch ( $entry_type_id )
		{
			case self::ENTRY_TYPE_NON_PROFIT:
				return 'Non Profit';
			case self::ENTRY_TYPE_POLITICAL:
				return 'Political';
			case self::ENTRY_TYPE_COMMERCIAL:
				return 'Commercial';
			case self::ENTRY_TYPE_SPONSOR:
				return 'Sponsor';
			default:
				return '';
		}
	}

	/**
	 * @return array
	 */
	public function getEntryTypes()
	{
		return array(
			self::ENTRY_TYPE_NON_PROFIT => $this->getEntryType( self::ENTRY_TYPE_NON_PROFIT ),
			self::ENTRY_TYPE_POLITICAL => $this->getEntryType( self::ENTRY_TYPE_POLITICAL ),
			self::ENTRY_TYPE_COMMERCIAL => $this->getEntryType( self::ENTRY_TYPE_COMMERCIAL ),
			self::ENTRY_TYPE_SPONSOR => $this->getEntryType( self::ENTRY_TYPE_SPONSOR ),
		);
	}

	/**
	 * @param $entry_type_id
	 *
	 * @return int
	 */
	public static function getEntryTypePrice( $entry_type_id )
	{
		switch ( $entry_type_id )
		{
			case self::ENTRY_TYPE_NON_PROFIT:
				return self::ENTRY_FEE_NON_PROFIT;
			case self::ENTRY_TYPE_POLITICAL:
				return self::ENTRY_FEE_POLITICAL;
			case self::ENTRY_TYPE_COMMERCIAL:
				return self::ENTRY_FEE_COMMERCIAL;
			case self::ENTRY_TYPE_SPONSOR:
				return self::ENTRY_FEE_SPONSOR;
			default:
				return 0;
		}
	}

	/**
	 * @return boolean
	 */
	public function isCornerBooth() {
		return $this->is_corner_booth;
	}

	/**
	 * @param $is_corner_booth
	 *
	 * @return $this
	 */
	public function setIsCornerBooth( $is_corner_booth ) {
		$this->is_corner_booth = ($is_corner_booth === TRUE || $is_corner_booth == 1) ? TRUE : FALSE;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getPriceForCornerBooth() {
		return ($this->price_for_corner_booth === NULL) ? 0 : $this->price_for_corner_booth;
	}

	/**
	 * @param mixed $price_for_corner_booth
	 *
	 * @return FestivalEntry
	 */
	public function setPriceForCornerBooth( $price_for_corner_booth ) {
		$this->price_for_corner_booth = (is_numeric($price_for_corner_booth)) ? abs(round($price_for_corner_booth, 2)) : NULL;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDescription()
	{
		return ( $this->description === NULL ) ? '' : $this->description;
	}

	/**
	 * @param mixed $description
	 *
	 * @return FestivalEntry
	 */
	public function setDescription( $description )
	{
		$this->description = $description;

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
				'state' => substr( $this->state, 0 , 2 ),
				'zip' => $this->zip,
				'qty' => 1,
				'price_per_qty' => $this->price_per_qty,
				'payment_method_id' => $this->payment_method_id,
				'entry_type_id' => $this->entry_type_id,
				'is_corner_booth' => ( $this->is_corner_booth ? 1 : 0 ),
				'price_for_corner_booth' => $this->price_for_corner_booth,
				'description' => $this->getDescription(),
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
				'%f',
				'%s',
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
		global $wpdb;

		if ( $this->id !== NULL )
		{
			parent::update();

			$wpdb->update(
				$wpdb->prefix . $this->table_name,
				array(
					'entry_type_id' => $this->entry_type_id,
					'is_corner_booth' => $this->is_corner_booth,
					'price_for_corner_booth' => $this->price_for_corner_booth,
					'description' => $this->getDescription()
				),
				array(
					'id' => $this->id
				),
				array(
					'%d',
					'%d',
					'%f',
					'%s'
				),
				array(
					'%d'
				)
			);
		}
	}

	/**
	 * @param \stdClass $row
	 */
	public function loadFromRow( \stdClass $row )
	{
		parent::loadFromRow( $row );
		$this
			->setEntryTypeId( $row->entry_type_id )
			->setIsCornerBooth( $row->is_corner_booth )
			->setPriceForCornerBooth( $row->price_for_corner_booth )
			->setDescription( $row->description );
	}

	/**
	 * @return float
	 */
	public function getTotal()
	{
		return parent::getTotal() + ( ( $this->is_corner_booth ) ? $this->getPriceForCornerBooth() : 0 );
	}

	/**
	 * @return float
	 */
	public function getAmountDue()
	{
		return round( $this->getTotal() - $this->getPaymentAmount(), 2);
	}
}