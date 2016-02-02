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

	/**
	 * FestivalEntry constructor.
	 *
	 * @param null $id
	 */
	public function __construct( $id=NULL ) {
		$this->setTableName(self::TABLE_NAME);
		parent::__construct( $id );
	}
}