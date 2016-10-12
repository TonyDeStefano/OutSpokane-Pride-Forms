<?php

namespace OutSpokane;

class Donation extends Entry {

	const TABLE_NAME = 'out_spokane_donations';

	private $donation_amount;

	/**
	 * Donation constructor.
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
	public function getDonationAmount() {
		return ($this->donation_amount === NULL) ? 0 : $this->donation_amount;
	}

	/**
	 * @param mixed $donation_amount
	 *
	 * @return Donation
	 */
	public function setDonationAmount( $donation_amount )
	{
		$donation_amount = preg_replace( '/[^0-9.]*/', '', $donation_amount );
		$this->donation_amount = ( is_numeric( $donation_amount ) ) ? abs ( round( $donation_amount, 2 ) ) : NULL;

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
			self::PAYMENT_METHOD_CARD => $this->getPaymentMethod( self::PAYMENT_METHOD_CARD )
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
				'state' => substr( $this->state, 0, 2 ),
				'zip' => $this->zip,
				'donation_amount' => $this->getDonationAmount(),
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
				'%f',
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
					'donation_amount' => $this->donation_amount
				),
				array(
					'id' => $this->id
				),
				array(
					'%f'
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
		$this->setDonationAmount( $row->donation_amount );
	}

	/**
	 * @return float
	 */
	public function getTotal()
	{
		return $this->getDonationAmount();
	}

	/**
	 * @return float
	 */
	public function getAmountDue()
	{
		return round( $this->getTotal() - $this->getPaymentAmount(), 2);
	}
}