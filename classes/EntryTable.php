<?php
/**
 * Created by PhpStorm.
 * User: Tony DeStefano
 * Date: 2/2/16
 * Time: 4:05 PM
 */

namespace OutSpokane;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class EntryTable extends \WP_List_Table {

	private $table;

	/**
	 * EntryTable constructor.
	 *
	 * @param string $table
	 */
	public function __construct( $table )
	{
		$this->setTable( $table );

		parent::__construct( array(
			'singular' => 'Entry',
			'plural' => 'Entries',
			'ajax' => TRUE
		) );
	}

	/**
	 * @return mixed
	 */
	public function getTable() {
		return $this->table;
	}

	/**
	 * @param mixed $table
	 *
	 * @return EntryTable
	 */
	public function setTable( $table ) {
		$this->table = $table;

		return $this;
	}

	/**
	 * @return array
	 */
	public function get_columns()
	{
		$return = array(
			'created_at' => 'Entry Date',
			'entry_year' => 'Year',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'organization' => 'Organization',
			'qty' => 'Tickets',
			'amount_due' => 'Total Due',
			'payment_amount' => 'Total Paid',
			'paid_at' => 'Paid On',
			'payment_method_id' => 'Method',
			'edit' => ''
		);

		if ( $this->table == FestivalEntry::TABLE_NAME )
		{
			unset( $return['qty'] );
		}
		elseif ( $this->table == CruiseEntry::TABLE_NAME )
		{
			unset( $return['organization'] );
		}

		return $return;
	}

	/**
	 * @return array
	 */
	public function get_sortable_columns()
	{
		$return =  array(
			'created_at' => array( 'created_at', TRUE ),
			'entry_year' => array( 'entry_year', TRUE ),
			'first_name' => array( 'first_name', TRUE ),
			'last_name' => array( 'last_name', TRUE ),
			'organization' => array( 'organization', TRUE ),
			'qty' => array( 'qty', TRUE ),
			'amount_due' => array( 'amount_due', TRUE ),
			'payment_amount' => array( 'payment_amount', TRUE ),
			'paid_at' => array( 'paid_at', TRUE ),
			'payment_method_id' => array( 'payment_method_id', TRUE )
		);

		if ( $this->table == FestivalEntry::TABLE_NAME )
		{
			unset( $return['qty'] );
		}
		elseif ( $this->table == CruiseEntry::TABLE_NAME )
		{
			unset( $return['organization'] );
		}

		return $return;
	}

	/**
	 * @param object $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch( $column_name ) {
			case 'entry_year':
			case 'first_name':
			case 'last_name':
			case 'organization':
			case 'qty':
				return $item->$column_name;
			case 'created_at':
			case 'paid_at':
				return ( $item->$column_name === NULL ) ? '' : date('n/j/Y', strtotime( $item->$column_name ) );
			case 'payment_method_id':
				return Entry::getPaymentMethod( $item->$column_name );
			case 'payment_amount':
				return '$' . number_format( ( $item->$column_name === NULL) ? 0 : $item->$column_name, 2 );
			case 'amount_due':
				return '$' . number_format( ( $item->price_per_qty === NULL ) ? 0 : $item->price_per_qty * $item->qty, 2 );
			case 'edit':
				return '<a href="?page=' . $_REQUEST['page'] . '&action=edit&id=' . $item->id . '" class="button-primary">' . __('Edit') . '</a>';
			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}

	/**
	 *
	 */
	public function prepare_items()
	{
		global $wpdb;

		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();

		$sql = "
			SELECT
				*
			FROM
				" . $wpdb->prefix . $this->table;
		if ( isset( $_GET[ 'orderby' ] ) )
		{
			foreach ( $sortable as $s )
			{
				if ( $s[ 0 ] == $_GET[ 'orderby' ] )
				{
					$sql .= "
						ORDER BY " . $_GET[ 'orderby' ] . " " . ( ( isset( $_GET['order']) && strtolower( $_GET['order'] == 'desc' ) ) ? "DESC" : "ASC" );
					break;
				}
			}
		}
		else
		{
			$sql .= "
				ORDER BY id DESC";
		}

		$total_items = $wpdb->query($sql);

		$max_per_page = 10;
		$paged = ( isset( $_GET[ 'paged' ] ) && is_numeric( $_GET['paged'] ) ) ? abs( round( $_GET[ 'paged' ])) : 1;
		$total_pages = ceil( $total_items / $max_per_page );

		if ( $paged > $total_pages )
		{
			$paged = $total_pages;
		}

		$offset = ( $paged - 1 ) * $max_per_page;
		$offset = ( $offset < 0 ) ? 0 : $offset; //MySQL freaks out about LIMIT -10, 10 type stuff.

		$sql .= "
			LIMIT " . $offset . ", " . $max_per_page;

		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'total_pages' => $total_pages,
			'per_page' => $max_per_page
		) );

		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->items = $wpdb->get_results( $sql );
	}

	/**
	 * @param $item
	 *
	 * @return string
	 */
	public function column_col_title( $item )
	{
		$actions = array(
			'edit' => sprintf( '<a href="?page=%s&action=%s&id=%s">%s</a>', $_REQUEST['page'], 'edit', $item->id, __( 'Edit' ) ),
			'delete' => sprintf( '<a href="?page=%s&action=%s&id=%s">%s</a>', $_REQUEST['page'], 'delete', $item->id, __( 'Delete' ) )
		);

		return sprintf( '%1$s %2$s', $item->title, $this->row_actions( $actions ) );
	}
}