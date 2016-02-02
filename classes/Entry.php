<?php
/**
 * Created by PhpStorm.
 * User: Tony DeStefano
 * Date: 2/1/16
 * Time: 3:58 PM
 */

namespace OutSpokane;


class Entry {

	const PAYMENT_METHOD_CASH = 1;
	const PAYMENT_METHOD_CARD = 2;
	const PAYMENT_METHOD_CHECK = 3;
	const PAYMENT_METHOD_MO = 4;

	protected $table_name;
	protected $id;
	protected $entry_year;
	protected $email;
	protected $phone;
	protected $first_name;
	protected $last_name;
	protected $address;
	protected $city;
	protected $state;
	protected $zip;
	protected $qty;
	protected $price_per_qty;
	protected $payment_method_id;
	protected $paid_at;
	protected $payment_amount;
	protected $created_at;
	protected $updated_at;

	/**
	 * Entry constructor.
	 *
	 * @param null $id
	 */
	public function __construct( $id=NULL ) {

		$this
			->setId($id)
			->read();
	}

	/**
	 *
	 */
	public function read()
	{
		if ($this->id !== NULL && $this->table_name !== NULL)
		{

		}
	}

	/**
	 * @return mixed
	 */
	public function getTableName() {
		return $this->table_name;
	}

	/**
	 * @param mixed $table_name
	 *
	 * @return Entry
	 */
	public function setTableName( $table_name ) {
		$this->table_name = $table_name;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param mixed $id
	 *
	 * @return Entry
	 */
	public function setId( $id ) {
		$this->id = (is_numeric($id)) ? abs(round($id)) : NULL;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getEntryYear() {
		return $this->entry_year;
	}

	/**
	 * @param mixed $entry_year
	 *
	 * @return Entry
	 */
	public function setEntryYear( $entry_year ) {
		$this->entry_year = (is_numeric($entry_year)) ? abs(round($entry_year)) : NULL;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @param mixed $email
	 *
	 * @return Entry
	 */
	public function setEmail( $email ) {
		$this->email = $email;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getPhone() {
		return $this->phone;
	}

	/**
	 * @param mixed $phone
	 *
	 * @return Entry
	 */
	public function setPhone( $phone ) {
		$this->phone = $phone;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getFirstName() {
		return $this->first_name;
	}

	/**
	 * @param mixed $first_name
	 *
	 * @return Entry
	 */
	public function setFirstName( $first_name ) {
		$this->first_name = $first_name;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getLastName() {
		return $this->last_name;
	}

	/**
	 * @param mixed $last_name
	 *
	 * @return Entry
	 */
	public function setLastName( $last_name ) {
		$this->last_name = $last_name;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * @param mixed $address
	 *
	 * @return Entry
	 */
	public function setAddress( $address ) {
		$this->address = $address;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getCity() {
		return $this->city;
	}

	/**
	 * @param mixed $city
	 *
	 * @return Entry
	 */
	public function setCity( $city ) {
		$this->city = $city;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getState() {
		return $this->state;
	}

	/**
	 * @param mixed $state
	 *
	 * @return Entry
	 */
	public function setState( $state ) {
		$this->state = $state;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getZip() {
		return $this->zip;
	}

	/**
	 * @param mixed $zip
	 *
	 * @return Entry
	 */
	public function setZip( $zip ) {
		$this->zip = $zip;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getQty() {
		return ($this->qty === NULL) ? 0 : round($this->qty);
	}

	/**
	 * @param mixed $qty
	 *
	 * @return Entry
	 */
	public function setQty( $qty ) {
		$this->qty = (is_numeric($qty)) ? abs(round($qty)) : NULL;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getPricePerQty() {
		return ($this->price_per_qty === NULL) ? 0 : round($this->price_per_qty, 2);
	}

	/**
	 * @param mixed $price_per_qty
	 *
	 * @return Entry
	 */
	public function setPricePerQty( $price_per_qty ) {
		$this->price_per_qty = (is_numeric($price_per_qty)) ? abs(round($price_per_qty, 2)) : NULL;

		return $this;
	}

	public function getPaymentMethod() {
		switch ($this->payment_method_id)
		{
			case 1:
				return 'Cash';
			case 2:
				return 'Credit Card';
			case 3:
				return 'Check';
			case 4:
				return 'Money Order';
			default:
				return '';
		}
	}

	/**
	 * @return mixed
	 */
	public function getPaymentMethodId() {
		return $this->payment_method_id;
	}

	/**
	 * @param mixed $payment_method_id
	 *
	 * @return Entry
	 */
	public function setPaymentMethodId( $payment_method_id ) {
		$this->payment_method_id = (in_array($payment_method_id, array(1, 2, 3, 4))) ? $payment_method_id : NULL;

		return $this;
	}

	/**
	 * @param null $format
	 *
	 * @return bool|null|string
	 */
	public function getPaidAt( $format=NULL ) {
		if ($this->paid_at === NULL)
		{
			return ($format === NULL) ? $this->paid_at : '';
		}

		return ($format === NULL) ? $this->paid_at : date($format, $this->paid_at);
	}

	/**
	 * @param mixed $paid_at
	 *
	 * @return Entry
	 */
	public function setPaidAt( $paid_at ) {
		$this->paid_at = (is_numeric($paid_at) || $paid_at === NULL) ? $paid_at : strtotime($paid_at);

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getPaymentAmount() {
		return ($this->payment_amount === NULL) ? 0 : round($this->payment_amount, 2);
	}

	/**
	 * @param mixed $payment_amount
	 *
	 * @return Entry
	 */
	public function setPaymentAmount( $payment_amount ) {
		$this->payment_amount = (is_numeric($payment_amount)) ? abs(round($payment_amount, 2)) : NULL;

		return $this;
	}

	/**
	 * @param null $format
	 *
	 * @return bool|string
	 */
	public function getCreatedAt( $format=NULL ) {
		return ($format === NULL) ? $this->created_at : date($format, $this->created_at);
	}

	/**
	 * @param mixed $created_at
	 *
	 * @return Entry
	 */
	public function setCreatedAt( $created_at ) {
		$this->created_at = (is_numeric($created_at) || $created_at === NULL) ? $created_at : strtotime($created_at);

		return $this;
	}

	/**
	 * @param null $format
	 *
	 * @return bool|string
	 */
	public function getUpdatedAt( $format=NULL ) {
		return ($format === NULL) ? $this->updated_at : date($format, $this->updated_at);
	}

	/**
	 * @param mixed $updated_at
	 *
	 * @return Entry
	 */
	public function setUpdatedAt( $updated_at ) {
		$this->updated_at = (is_numeric($updated_at) || $updated_at === NULL) ? $updated_at : strtotime($updated_at);

		return $this;
	}
}