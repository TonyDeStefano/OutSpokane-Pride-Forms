<?php
/**
 * Created by PhpStorm.
 * User: Tony DeStefano
 * Date: 2/1/16
 * Time: 3:59 PM
 */

namespace OutSpokane;


class ParadeEntry extends Entry {

	const TABLE_NAME = 'out_spokane_parade_entries';
	const ENTRY_TYPE_WALK_BANNER = 'Walking with Banner';
	const ENTRY_TYPE_WALK_GROUP = 'Walking/Performing Group';
	const ENTRY_TYPE_BIKE = 'Bicycle(s)';
	const ENTRY_TYPE_MOTORCYCLE = 'Motorcycle(s)';
	const ENTRY_TYPE_CAR = 'Car(s)';
	const ENTRY_TYPE_TRUCK = 'Truck(s)/Van(s)';
	const ENTRY_TYPE_TRAILER_FLOAT = 'Trailer/Float';
	const ENTRY_TYPE_OTHER = 'Other';
	const FLOAT_PARKING_SPACE_COST = 18;

	private $entry_types;
	private $float_parking_spaces;
	private $float_parking_space_cost;
	private $donation_amount;
	private $description;
	private $needs_amped_sound = FALSE;
	private $group_size;

	/**
	 * ParadeEntry constructor.
	 *
	 * @param null $id
	 */
	public function __construct( $id=NULL ) {
		$this->setTableName(self::TABLE_NAME);
		parent::__construct( $id );
		$this->read();
	}

	/**
	 * @return array
	 */
	public function getEntryTypeList()
	{
		return array(
			self::ENTRY_TYPE_WALK_BANNER,
			self::ENTRY_TYPE_WALK_GROUP,
			self::ENTRY_TYPE_BIKE,
			self::ENTRY_TYPE_MOTORCYCLE,
			self::ENTRY_TYPE_CAR,
			self::ENTRY_TYPE_TRUCK,
			self::ENTRY_TYPE_TRAILER_FLOAT,
			self::ENTRY_TYPE_OTHER
		);
	}

	/**
	 * @param bool $as_json
	 *
	 * @return array|mixed|string|void
	 */
	public function getEntryTypes( $as_json=FALSE ) {
		$entry_types = ($this->entry_types === NULL) ? array() : $this->entry_types;
		return ($as_json) ? json_encode($entry_types) : $entry_types;
	}

	/**
	 * @param mixed $entry_types
	 *
	 * @return ParadeEntry
	 */
	public function setEntryTypes( $entry_types ) {
		if ($entry_types === NULL)
		{
			$this->entry_types = NULL;
		}
		else
		{
			$this->entry_types = ( is_array( $entry_types ) ) ? $entry_types : json_decode( $entry_types, TRUE );
		}

		return $this;
	}

	/**
	 * @param $entry_type
	 *
	 * @return $this
	 */
	public function addEntryType( $entry_type )
	{
		if ($this->entry_types === NULL)
		{
			$this->entry_types = array();
		}

		$this->entry_types[] = $entry_type;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getFloatParkingSpaces() {
		return ($this->float_parking_spaces === NULL) ? 0 : $this->float_parking_spaces;
	}

	/**
	 * @param mixed $float_parking_spaces
	 *
	 * @return ParadeEntry
	 */
	public function setFloatParkingSpaces( $float_parking_spaces ) {
		$this->float_parking_spaces = (is_numeric($float_parking_spaces)) ? abs(round($float_parking_spaces)) : NULL;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getFloatParkingSpaceCost() {
		return ($this->float_parking_space_cost === NULL) ? 0 : $this->float_parking_space_cost;
	}

	/**
	 * @param mixed $float_parking_space_cost
	 *
	 * @return ParadeEntry
	 */
	public function setFloatParkingSpaceCost( $float_parking_space_cost ) {
		$this->float_parking_space_cost = (is_numeric($float_parking_space_cost)) ? abs(round($float_parking_space_cost, 2)) : NULL;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDonationAmount() {
		return ($this->donation_amount === NULL) ? 0 : $this->donation_amount;
	}

	/**
	 * @param mixed $donation_amount
	 *
	 * @return ParadeEntry
	 */
	public function setDonationAmount( $donation_amount ) {
		$this->donation_amount = (is_numeric($donation_amount)) ? abs(round($donation_amount, 2)) : NULL;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDescription() {
		return ($this->description === NULL) ? '' : $this->description;
	}

	/**
	 * @param mixed $description
	 *
	 * @return ParadeEntry
	 */
	public function setDescription( $description ) {
		$this->description = $description;

		return $this;
	}

	/**
	 * @return boolean
	 */
	public function needsAmpedSound() {
		return ($this->needs_amped_sound === TRUE) ? TRUE : FALSE;
	}

	/**
	 * @param $needs_amped_sound
	 *
	 * @return $this
	 */
	public function setNeedsAmpedSound( $needs_amped_sound ) {
		$this->needs_amped_sound = ($needs_amped_sound == 1 || $needs_amped_sound === TRUE) ? TRUE : FALSE;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getGroupSize() {
		return ($this->group_size === NULL) ? 0 : $this->group_size;
	}

	/**
	 * @param $group_size
	 *
	 * @return $this
	 */
	public function setGroupSize( $group_size ) {
		$this->group_size = (is_numeric($group_size)) ? abs(round($group_size)) : NULL;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getPaymentMethods()
	{
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
				'qty' => 1,
				'price_per_qty' => $this->price_per_qty,
				'payment_method_id' => $this->payment_method_id,
				'entry_types' => $this->getEntryTypes( TRUE ),
				'float_parking_spaces' => $this->float_parking_spaces,
				'float_parking_space_cost' => $this->float_parking_space_cost,
				'donation_amount' => $this->donation_amount,
				'description' => $this->description,
				'needs_amped_sound' => $this->needs_amped_sound,
				'group_size' => $this->group_size,
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
				'%s',
				'%d',
				'%f',
				'%f',
				'%s',
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
		global $wpdb;

		if ( $this->id !== NULL )
		{
			parent::update();

			$wpdb->update(
				$wpdb->prefix . $this->table_name,
				array(
					'entry_types' => $this->getEntryTypes( TRUE ),
					'float_parking_spaces' => $this->float_parking_spaces,
					'float_parking_space_cost' => $this->float_parking_space_cost,
					'donation_amount' => $this->donation_amount,
					'description' => $this->description,
					'needs_amped_sound' => $this->needs_amped_sound,
					'group_size' => $this->group_size
				),
				array(
					'id' => $this->id
				),
				array(
					'%s',
					'%d',
					'%f',
					'%f',
					'%s',
					'%d',
					'%d'
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
			->setEntryTypes( $row->entry_types )
			->setNeedsAmpedSound( $row->needs_amped_sound )
			->setGroupSize( $row->group_size )
			->setFloatParkingSpaces( $row->float_parking_spaces )
			->setFloatParkingSpaceCost( $row->float_parking_space_cost )
			->setDonationAmount( $row->donation_amount )
			->setDescription( $row->description );
	}

	/**
	 * @return float
	 */
	public function getTotal()
	{
		return ($this->getFloatParkingSpaces() * $this->getFloatParkingSpaceCost()) + $this->getDonationAmount();
	}

	/**
	 * @return float
	 */
	public function getAmountDue()
	{
		return round( $this->getTotal() - $this->getPaymentAmount(), 2);
	}
}