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

	private $entry_types;
	private $float_parking_spaces;
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
	 * @param boolean $needs_amped_sound
	 */
	public function setNeedsAmpedSound( $needs_amped_sound ) {
		$this->needs_amped_sound = ($needs_amped_sound == 1 || $needs_amped_sound === TRUE) ? TRUE : FALSE;
	}

	/**
	 * @return mixed
	 */
	public function getGroupSize() {
		return ($this->group_size === NULL) ? 0 : $this->group_size;
	}

	/**
	 * @param mixed $group_size
	 */
	public function setGroupSize( $group_size ) {
		$this->group_size = (is_numeric($group_size)) ? abs(round($group_size)) : NULL;
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
}