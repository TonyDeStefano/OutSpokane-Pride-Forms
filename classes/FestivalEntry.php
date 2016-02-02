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

	private $entry_type_id;

	/**
	 * FestivalEntry constructor.
	 *
	 * @param null $id
	 */
	public function __construct( $id=NULL ) {
		$this->setTableName(self::TABLE_NAME);
		parent::__construct( $id );
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