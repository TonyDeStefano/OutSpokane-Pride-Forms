<?php
/**
 * Created by PhpStorm.
 * User: Tony DeStefano
 * Date: 2/1/16
 * Time: 4:00 PM
 */

namespace OutSpokane;


class MurderMysteryEntry extends Entry {

	const TABLE_NAME = 'out_spokane_murder_mystery_entries';

	/**
	 * MurderMysteryEntry constructor.
	 *
	 * @param null $id
	 */
	public function __construct( $id=NULL ) {
		$this->setTableName(self::TABLE_NAME);
		parent::__construct( $id );
	}
}