<?php

namespace OutSpokane;

class Sponsorship extends Entry {

	const TABLE_NAME = 'out_spokane_sponsorships';

	const LEVEL_FRIEND = 'Friend';
	const LEVEL_BRONZE = 'Bronze';
	const LEVEL_SILVER = 'Silver';
	const LEVEL_GOLD = 'Gold';
	const LEVEL_PLATINUM = 'Platinum';
	const LEVEL_DIAMOND = 'Diamond';

	const LEVEL_DEFAULT_MIN_VALUE_FRIEND = 250;
	const LEVEL_DEFAULT_MIN_VALUE_BRONZE = 500;
	const LEVEL_DEFAULT_MIN_VALUE_SILVER = 1000;
	const LEVEL_DEFAULT_MIN_VALUE_GOLD = 2500;
	const LEVEL_DEFAULT_MIN_VALUE_PLATINUM = 5000;
	const LEVEL_DEFAULT_MIN_VALUE_DIAMOND = 7500;

	private $amount;
	private $position;
	private $local_first_name;
	private $local_last_name;
	private $local_position;
	private $local_address;
	private $local_city;
	private $local_state;
	private $local_zip;
	private $local_email;
	private $local_phone;
	private $level;
	private $url;

	/**
	 * Sponsorship constructor.
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
	public static function getLevels()
	{
		return array(
			self::LEVEL_FRIEND => self::LEVEL_DEFAULT_MIN_VALUE_FRIEND,
			self::LEVEL_BRONZE => self::LEVEL_DEFAULT_MIN_VALUE_BRONZE,
			self::LEVEL_SILVER => self::LEVEL_DEFAULT_MIN_VALUE_SILVER,
			self::LEVEL_GOLD => self::LEVEL_DEFAULT_MIN_VALUE_GOLD,
			self::LEVEL_PLATINUM => self::LEVEL_DEFAULT_MIN_VALUE_PLATINUM,
			self::LEVEL_DIAMOND => self::LEVEL_DEFAULT_MIN_VALUE_DIAMOND,
		);
	}

	/**
	 * @param $amount
	 *
	 * @return string
	 */
	public static function getLevelFromAmount( $amount )
	{
		$level_name = '';
		$last_amount = 0;

		foreach ( self::getLevels() as $name => $min_amount )
		{
			if ( $amount >= $min_amount && $amount > $last_amount )
			{
				$level_name = $name;
				$last_amount = $min_amount;
			}
		}

		return $level_name;
	}

	/**
	 * @return $this
	 */
	public function setLevelFromAmount()
	{
		if ( $this->amount !== NULL && $this->amount >= self::LEVEL_DEFAULT_MIN_VALUE_FRIEND )
		{
			$this->setLevel( self::getLevelFromAmount( $this->amount ) );
		}
		
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getAmount() {
		return ($this->amount === NULL) ? 0 : $this->amount;
	}

	/**
	 * @param mixed $amount
	 *
	 * @return Sponsorship
	 */
	public function setAmount( $amount )
	{
		$amount = preg_replace( '/[^0-9.]*/', '', $amount );
		$this->amount = ( is_numeric( $amount ) ) ? abs ( round( $amount, 2 ) ) : NULL;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getPosition()
	{
		return ( $this->position === NULL ) ? '' : $this->position;
	}

	/**
	 * @param mixed $position
	 *
	 * @return Sponsorship
	 */
	public function setPosition( $position )
	{
		$this->position = $position;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getLocalFirstName()
	{
		return ( $this->local_first_name === NULL ) ? '' : $this->local_first_name;
	}

	/**
	 * @param mixed $local_first_name
	 *
	 * @return Sponsorship
	 */
	public function setLocalFirstName( $local_first_name )
	{
		$this->local_first_name = $local_first_name;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getLocalLastName()
	{
		return ( $this->local_last_name === NULL ) ? '' : $this->local_last_name;
	}

	/**
	 * @param mixed $local_last_name
	 *
	 * @return Sponsorship
	 */
	public function setLocalLastName( $local_last_name )
	{
		$this->local_last_name = $local_last_name;

		return $this;
	}

	public function getLocalName()
	{
		return $this->getLocalFirstName() . ' ' . $this->getLocalLastName();
	}

	/**
	 * @return mixed
	 */
	public function getLocalPosition()
	{
		return ( $this->local_position === NULL ) ? '' : $this->local_position;
	}

	/**
	 * @param mixed $local_position
	 *
	 * @return Sponsorship
	 */
	public function setLocalPosition( $local_position )
	{
		$this->local_position = $local_position;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getLocalAddress()
	{
		return ( $this->local_address === NULL ) ? '' : $this->local_address;
	}

	/**
	 * @param mixed $local_address
	 *
	 * @return Sponsorship
	 */
	public function setLocalAddress( $local_address )
	{
		$this->local_address = $local_address;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getLocalCity()
	{
		return ( $this->local_city === NULL ) ? '' : $this->local_city;
	}

	/**
	 * @param mixed $local_city
	 *
	 * @return Sponsorship
	 */
	public function setLocalCity( $local_city )
	{
		$this->local_city = $local_city;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getLocalState()
	{
		return ( $this->local_state === NULL ) ? '' : $this->local_state;
	}

	/**
	 * @param mixed $local_state
	 *
	 * @return Sponsorship
	 */
	public function setLocalState( $local_state )
	{
		$this->local_state = $local_state;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getLocalZip()
	{
		return ( $this->local_zip === NULL ) ? '' : $this->local_zip;
	}

	/**
	 * @param mixed $local_zip
	 *
	 * @return Sponsorship
	 */
	public function setLocalZip( $local_zip )
	{
		$this->local_zip = $local_zip;

		return $this;
	}

	public function getLocalCSZ()
	{
		return $this->city . ( ( strlen( $this->city ) > 0 ) ? ', ' : '' ) . $this->state . ' ' . $this->zip;
	}

	/**
	 * @return mixed
	 */
	public function getLocalEmail()
	{
		return ( $this->local_email === NULL ) ? '' : $this->local_email;
	}

	/**
	 * @param mixed $local_email
	 *
	 * @return Sponsorship
	 */
	public function setLocalEmail( $local_email )
	{
		$this->local_email = $local_email;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getLocalPhone()
	{
		return ( $this->local_phone === NULL ) ? '' : $this->local_phone;
	}

	/**
	 * @param mixed $local_phone
	 *
	 * @return Sponsorship
	 */
	public function setLocalPhone( $local_phone )
	{
		$this->local_phone = $local_phone;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getLevel()
	{
		return ( $this->level === NULL ) ? '' : $this->level;
	}

	/**
	 * @param mixed $level
	 *
	 * @return Sponsorship
	 */
	public function setLevel( $level )
	{
		$this->level = $level;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getUrl()
	{
		if ( strlen( $this->url ) == 0 )
		{
			return '';
		}
		else
		{
			return ( ( substr( strtolower( $this->url ), 0, 4 ) !== 'http' ) ? 'http://' : '' ) . $this->url;
		}
	}

	/**
	 * @param mixed $url
	 *
	 * @return Sponsorship
	 */
	public function setUrl( $url )
	{
		$this->url = $url;

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
			self::PAYMENT_METHOD_CHECK => $this->getPaymentMethod( self::PAYMENT_METHOD_CHECK ),
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
				'position' => $this->position,
				'address' => $this->address,
				'city' => $this->city,
				'state' => substr( $this->state, 0, 2 ),
				'zip' => $this->zip,
				'local_email' => $this->local_email,
				'local_phone' => $this->local_phone,
				'local_position' => $this->local_position,
				'local_first_name' => $this->local_first_name,
				'local_last_name' => $this->local_last_name,
				'local_address' => $this->local_address,
				'local_city' => $this->local_city,
				'local_state' => ( $this->local_state !== NULL ) ? substr( $this->local_state, 0, 2 ) : NULL,
				'local_zip' => $this->local_zip,
				'level' => $this->level,
				'url' => $this->url,
				'amount' => $this->getAmount(),
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
					'amount' => $this->amount,
					'position' => $this->position,
					'local_position' => $this->local_position,
					'local_email' => $this->local_email,
					'local_phone' => $this->local_phone,
					'local_first_name' => $this->local_first_name,
					'local_last_name' => $this->local_last_name,
					'local_address' => $this->local_address,
					'local_city' => $this->local_city,
					'local_state' => ( $this->local_state !== NULL ) ? substr( $this->local_state, 0, 2 ) : NULL,
					'local_zip' => $this->local_zip,
					'level' => $this->level,
					'url' => $this->url
				),
				array(
					'id' => $this->id
				),
				array(
					'%f',
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
					'%s',
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
			->setAmount( $row->amount )
			->setUrl( $row->url )
			->setPosition( $row->position )
			->setLocalPosition( $row->local_position )
			->setLevel( $row->level )
			->setLocalFirstName( $row->local_first_name )
			->setLocalLastName( $row->local_last_name )
			->setLocalAddress( $row->local_address )
			->setLocalCity( $row->local_city )
			->setLocalState( $row->local_state )
			->setLocalZip( $row->local_zip )
			->setLocalPhone( $row->local_phone )
			->setLocalEmail( $row->local_email );
	}

	/**
	 * @return float
	 */
	public function getTotal()
	{
		return $this->getAmount();
	}

	/**
	 * @return float
	 */
	public function getAmountDue()
	{
		return round( $this->getTotal() - $this->getPaymentAmount(), 2);
	}
}