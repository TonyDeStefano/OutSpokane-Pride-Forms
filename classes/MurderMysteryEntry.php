<?php

namespace OutSpokane;

class MurderMysteryEntry extends Entry {

	const TABLE_NAME = 'out_spokane_murder_mystery_entries';
	const TICKET_PRICE = 50;
	const VIP_TABLE_PRICE = 500;
	const SPONSOR_PRICE = 0;
	const MAX_TICKETS = 20;

	private $vegetarian_qty;
	private $is_sponsor = FALSE;
	private $is_vip = FALSE;
	private $meals;
	private $is_upgraded = FALSE;
	private $tickets_sent = FALSE;

	/**
	 * MurderMysteryEntry constructor.
	 *
	 * @param null $id
	 */
	public function __construct( $id = NULL )
	{
		$this->setTableName( self::TABLE_NAME );
		parent::__construct( $id );
		$this->read();
	}

	/**
	 * @return mixed
	 */
	public function getVegetarianQty()
	{
		return ( $this->vegetarian_qty === NULL ) ? 0 : $this->vegetarian_qty;
	}

	/**
	 * @param mixed $vegetarian_qty
	 *
	 * @return MurderMysteryEntry
	 */
	public function setVegetarianQty( $vegetarian_qty )
	{
		$this->vegetarian_qty = ( is_numeric( $vegetarian_qty ) ) ? abs( round( $vegetarian_qty ) ) : NULL;

		return $this;
	}

	/**
	 * @return boolean
	 */
	public function isSponsor()
	{
		return ( $this->is_sponsor === TRUE ) ? TRUE : FALSE;
	}

	/**
	 * @param boolean $is_sponsor
	 *
	 * @return MurderMysteryEntry
	 */
	public function setIsSponsor( $is_sponsor )
	{
		$this->is_sponsor = ( $is_sponsor == 1 || $is_sponsor === TRUE ) ? TRUE : FALSE;

		return $this;
	}

	/**
	 * @return boolean
	 */
	public function isUpgraded()
	{
		return ( $this->is_upgraded === TRUE );
	}

	/**
	 * @param boolean $is_upgraded
	 *
	 * @return MurderMysteryEntry
	 */
	public function setIsUpgraded( $is_upgraded )
	{
		$this->is_upgraded = ( $is_upgraded == 1 || $is_upgraded === TRUE ) ? TRUE : FALSE;

		return $this;
	}

    /**
     * @return bool
     */
    public function isVip()
    {
        return ( $this->is_vip === TRUE );
    }

    /**
     * @param bool $is_vip
     *
     * @return MurderMysteryEntry
     */
    public function setIsVip( $is_vip )
    {
        $this->is_vip = ( $is_vip == 1 || $is_vip === TRUE ) ? TRUE : FALSE;

        return $this;
    }

    /**
     * @param bool $as_array
     *
     * @return array|string
     */
    public function getMeals( $as_array = FALSE )
    {
        if ( $as_array )
        {
            return ( strlen( $this->meals ) > 0 ) ? json_decode( $this->meals, TRUE ) : array();
        }

        return ( strlen( $this->meals ) > 0 ) ? $this->meals : '';
    }

    /**
     * @param string|array|null $meals
     *
     * @return MurderMysteryEntry
     */
    public function setMeals( $meals )
    {
        if ( is_array( $meals ) )
        {
            $meals = json_encode( $meals );
        }

        $this->meals = $meals;

        return $this;
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
	 * @return MurderMysteryEntry
	 */
	public function setTicketsSent( $tickets_sent )
	{
		$this->tickets_sent = ( $tickets_sent === TRUE || $tickets_sent == 1 ) ? TRUE : FALSE;

		return $this;
	}

	/**
	 * @param bool $use_parent
	 *
	 * @return array
	 */
	public function getPaymentMethods( $use_parent = FALSE )
	{
		if ( $use_parent )
		{
			return parent::getPaymentMethods();
		}

		return array (
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
			array (
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
				'qty' => $this->qty,
				'price_per_qty' => $this->price_per_qty,
				'payment_method_id' => $this->payment_method_id,
				'vegetarian_qty' => $this->vegetarian_qty,
				'is_sponsor' => ( $this->isSponsor() ) ? 1 : 0,
				'is_vip' => ( $this->isVip() ) ? 1 : 0,
				'meals' => $this->meals,
				'created_at' => $this->getCreatedAt( 'Y-m-d H:i:s' ),
				'updated_at' => $this->getUpdatedAt( 'Y-m-d H:i:s' )
			),
			array (
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
			array (
				'vegetarian_qty' => $this->vegetarian_qty,
				'tickets_sent' => ( $this->wereTicketsSent() ) ? 1 : 0,
				'is_sponsor' => ( $this->isSponsor() ) ? 1 : 0,
				'is_upgraded' => ( $this->isUpgraded() ) ? 1 : 0
			),
			array (
				'id' => $this->id
			),
			array (
				'%d',
				'%d',
				'%d',
				'%d'
			),
			array (
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
			->setIsSponsor( $row->is_sponsor )
			->setIsUpgraded( $row->is_upgraded )
            ->setIsVip( $row->is_vip )
            ->setMeals( $row->meals )
			->setVegetarianQty( $row->vegetarian_qty )
			->setTicketsSent( $row->tickets_sent );
	}

    /**
     * @return array
     */
	public static function getMealTypes()
    {
        /*
         * Don't remove items from this list, just change active to 0 for ones no longer needed.
         * And add any new ones to the end (for backwards compatibility)
         * */
        return array(
            array(
                'name' => 'Chicken Parmesan',
                'active' => TRUE
            ),
            array(
                'name' => 'Chicken Fettuccine',
                'active' => TRUE
            ),
            array(
                'name' => 'Prime Rib',
                'active' => TRUE
            ),
            array(
                'name' => 'Eggplant Marinara w/ Gluten Free Spaghetti + Veggies w/ Garlic and Olive Oil',
                'active' => TRUE
            ),
        );
    }
}