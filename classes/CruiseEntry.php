<?php
/**
 * Created by PhpStorm.
 * User: Tony DeStefano
 * Date: 2/1/16
 * Time: 3:59 PM
 */

namespace OutSpokane;


class CruiseEntry extends Entry{

	const TABLE_NAME = 'out_spokane_cruise_entries';
	const PRICE_PER_TICKET = 20;
	const MAX_TICKETS = 20;

	private $tickets_sent = FALSE;
	private $is_will_call = FALSE;

	/**
	 * CruiseEntry constructor.
	 *
	 * @param null $id
	 */
	public function __construct( $id=NULL ) {
		$this->setTableName(self::TABLE_NAME);
		parent::__construct( $id );
		$this->read();
	}

	/**
	 * @return boolean
	 */
	public function wereTicketsSent()
	{
		return ( $this->tickets_sent === TRUE );
	}

	/**
	 * @param boolean $tickets_sent
	 *
	 * @return $this
	 */
	public function setTicketsSent( $tickets_sent )
	{
		$this->tickets_sent = ( $tickets_sent === TRUE || $tickets_sent == 1 ) ? TRUE : FALSE;

		return $this;
	}

    /**
     * @return bool
     */
	public function isWillCall()
    {
        return ( $this->is_will_call === TRUE );
    }

    /**
     * @param $is_will_call
     *
     * @return $this
     */
    public function setIsWillCall( $is_will_call )
    {
        $this->is_will_call = ( $is_will_call === TRUE || $is_will_call == 1 ) ? TRUE : FALSE;

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
				'qty' => $this->qty,
				'price_per_qty' => $this->price_per_qty,
				'payment_method_id' => $this->payment_method_id,
				'is_will_call' => ( $this->isWillCall() ) ? 1 : 0,
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
				'tickets_sent' => ( $this->wereTicketsSent() ) ? 1 : 0,
                'is_will_call' => ( $this->isWillCall() ) ? 1 : 0
			),
			array(
				'id' => $this->id
			),
			array(
				'%d',
                '%d'
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
		$this
            ->setTicketsSent( $row->tickets_sent )
            ->setIsWillCall( $row->is_will_call );
	}
}