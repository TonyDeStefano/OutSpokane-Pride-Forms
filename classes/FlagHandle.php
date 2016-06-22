<?php

namespace OutSpokane;

class FlagHandle extends Entry {

	const TABLE_NAME = 'out_spokane_flag_handles';
	const PRICE_PER_HANDLE = 50;
	
	private $message;

	/**
	 * FlagHandle constructor.
	 *
	 * @param null $id
	 */
	public function __construct( $id=NULL )
	{
		$this->setTableName( self::TABLE_NAME );
		parent::__construct( $id );
		$this->read();
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
				'state' => substr( $this->state, 0 , 2 ),
				'zip' => $this->zip,
				'message' => $this->message,
				'qty' => $this->qty,
				'price_per_qty' => $this->price_per_qty,
				'payment_method_id' => $this->payment_method_id,
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
				'%s',
				'%d',
				'%f',
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
		}

		$wpdb->update(
			$wpdb->prefix . $this->table_name,
			array(
				'message' => $this->message
			),
			array(
				'id' => $this->id
			),
			array(
				'%s'
			),
			array(
				'%d'
			)
		);
	}

	/**
	 * @param \stdClass $row
	 */
	public function loadFromRow( \stdClass $row )
	{
		parent::loadFromRow( $row );
		$this->setMessage( $row->message );
	}

	/**
	 * @return mixed
	 */
	public function getMessage()
	{
		return ( $this->message === NULL ) ? '' : $this->message;
	}

	/**
	 * @param mixed $message
	 *
	 * @return FlagHandle
	 */
	public function setMessage( $message )
	{
		$this->message = $message;

		return $this;
	}
}