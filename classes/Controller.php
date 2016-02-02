<?php
/**
 * Created by PhpStorm.
 * User: Tony DeStefano
 * Date: 2/1/16
 * Time: 3:58 PM
 */

namespace OutSpokane;


class Controller {

	const VERSION = '1.0.0';

	public $action = '';
	public $data = '';
	public $return = '';
	public $attributes = array();
	public $base_page = '';

	/**
	 *
	 */
	public function activate()
	{
		add_option( 'pride_forms_version', self::VERSION );

		require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
		global $wpdb;

		/* create tables */
		$charset_collate = '';
		if ( ! empty( $wpdb->charset ) )
		{
			$charset_collate .= "DEFAULT CHARACTER SET " . $wpdb->charset;
		}
		if ( ! empty( $wpdb->collate ) )
		{
			$charset_collate .= " COLLATE " . $wpdb->collate;
		}

		/* cruise_entries table */
		$table = $wpdb->prefix . CruiseEntry::TABLE_NAME;
		if( $wpdb->get_var( "SHOW TABLES LIKE '" . $table . "'" ) != $table ) {
			$sql = "
				CREATE TABLE `" . $table . "`
				(
					`id` INT(11) NOT NULL AUTO_INCREMENT,
					`entry_year` INT(11) DEFAULT NULL,
					`email` VARCHAR(50) DEFAULT NULL,
					`phone` VARCHAR(50) DEFAULT NULL,
					`organization` VARCHAR(150) DEFAULT NULL,
					`first_name` VARCHAR(50) DEFAULT NULL,
					`last_name` VARCHAR(50) DEFAULT NULL,
					`address` VARCHAR(50) DEFAULT NULL,
					`city` VARCHAR(50) DEFAULT NULL,
					`state` VARCHAR(2) DEFAULT NULL,
					`zip` VARCHAR(10) DEFAULT NULL,
					`qty` INT(11) DEFAULT NULL,
					`price_per_qty` DECIMAL(11,2) DEFAULT NULL,
					`payment_method_id` INT(11) DEFAULT NULL,
					`paid_at` DATETIME DEFAULT NULL,
					`payment_amount` DECIMAL(11,2) DEFAULT NULL,
					`payment_confirmation_number` VARCHAR(50) DEFAULT NULL,
					`notes` TEXT DEFAULT NULL,
					`created_at` DATETIME DEFAULT NULL,
					`updated_at` DATETIME DEFAULT NULL,
					PRIMARY KEY (`id`)
				)";
			$sql .= $charset_collate . ";"; // new line to avoid PHP Storm syntax error
			dbDelta( $sql );
		}

		/* festival_entries table */
		$table = $wpdb->prefix . FestivalEntry::TABLE_NAME;
		if( $wpdb->get_var( "SHOW TABLES LIKE '" . $table . "'" ) != $table ) {
			$sql = "
				CREATE TABLE `" . $table . "`
				(
					`id` INT(11) NOT NULL AUTO_INCREMENT,
					`entry_type_id` INT(11) DEFAULT NULL,
					`entry_year` INT(11) DEFAULT NULL,
					`email` VARCHAR(50) DEFAULT NULL,
					`phone` VARCHAR(50) DEFAULT NULL,
					`organization` VARCHAR(150) DEFAULT NULL,
					`first_name` VARCHAR(50) DEFAULT NULL,
					`last_name` VARCHAR(50) DEFAULT NULL,
					`address` VARCHAR(50) DEFAULT NULL,
					`city` VARCHAR(50) DEFAULT NULL,
					`state` VARCHAR(2) DEFAULT NULL,
					`zip` VARCHAR(10) DEFAULT NULL,
					`qty` INT(11) DEFAULT NULL,
					`price_per_qty` DECIMAL(11,2) DEFAULT NULL,
					`payment_method_id` INT(11) DEFAULT NULL,
					`paid_at` DATETIME DEFAULT NULL,
					`payment_amount` DECIMAL(11,2) DEFAULT NULL,
					`payment_confirmation_number` VARCHAR(50) DEFAULT NULL,
					`notes` TEXT DEFAULT NULL,
					`created_at` DATETIME DEFAULT NULL,
					`updated_at` DATETIME DEFAULT NULL,
					PRIMARY KEY (`id`)
				)";
			$sql .= $charset_collate . ";"; // new line to avoid PHP Storm syntax error
			dbDelta( $sql );
		}

		/* parade_entries table */
		$table = $wpdb->prefix . ParadeEntry::TABLE_NAME;
		if( $wpdb->get_var( "SHOW TABLES LIKE '" . $table . "'" ) != $table ) {
			$sql = "
				CREATE TABLE `" . $table . "`
				(
					`id` INT(11) NOT NULL AUTO_INCREMENT,
					`entry_year` INT(11) DEFAULT NULL,
					`email` VARCHAR(50) DEFAULT NULL,
					`phone` VARCHAR(50) DEFAULT NULL,
					`organization` VARCHAR(150) DEFAULT NULL,
					`first_name` VARCHAR(50) DEFAULT NULL,
					`last_name` VARCHAR(50) DEFAULT NULL,
					`address` VARCHAR(50) DEFAULT NULL,
					`city` VARCHAR(50) DEFAULT NULL,
					`state` VARCHAR(2) DEFAULT NULL,
					`zip` VARCHAR(10) DEFAULT NULL,
					`qty` INT(11) DEFAULT NULL,
					`price_per_qty` DECIMAL(11,2) DEFAULT NULL,
					`payment_method_id` INT(11) DEFAULT NULL,
					`paid_at` DATETIME DEFAULT NULL,
					`payment_amount` DECIMAL(11,2) DEFAULT NULL,
					`payment_confirmation_number` VARCHAR(50) DEFAULT NULL,
					`notes` TEXT DEFAULT NULL,
					`entry_types` TEXT DEFAULT NULL,
					`float_parking_spaces` INT(11) DEFAULT NULL,
					`donation_amount` DECIMAL(11,2) DEFAULT NULL,
					`description` TEXT DEFAULT NULL,
					`needs_amped_sound` TINYINT(4) DEFAULT NULL,
					`group_size` INT(11) DEFAULT NULL,
					`created_at` DATETIME DEFAULT NULL,
					`updated_at` DATETIME DEFAULT NULL,
					PRIMARY KEY (`id`)
				)";
			$sql .= $charset_collate . ";"; // new line to avoid PHP Storm syntax error
			dbDelta( $sql );
		}

		/* murder_mystery_entries table */
		$table = $wpdb->prefix . MurderMysteryEntry::TABLE_NAME;
		if( $wpdb->get_var( "SHOW TABLES LIKE '" . $table . "'" ) != $table ) {
			$sql = "
				CREATE TABLE `" . $table . "`
				(
					`id` INT(11) NOT NULL AUTO_INCREMENT,
					`entry_year` INT(11) DEFAULT NULL,
					`email` VARCHAR(50) DEFAULT NULL,
					`phone` VARCHAR(50) DEFAULT NULL,
					`organization` VARCHAR(150) DEFAULT NULL,
					`first_name` VARCHAR(50) DEFAULT NULL,
					`last_name` VARCHAR(50) DEFAULT NULL,
					`address` VARCHAR(50) DEFAULT NULL,
					`city` VARCHAR(50) DEFAULT NULL,
					`state` VARCHAR(2) DEFAULT NULL,
					`zip` VARCHAR(10) DEFAULT NULL,
					`qty` INT(11) DEFAULT NULL,
					`vegetarian_qty` INT(11) DEFAULT NULL,
					`is_sponsor` TINYINT(4) DEFAULT NULL,
					`is_upgraded` TINYINT(4) DEFAULT NULL,
					`price_per_qty` DECIMAL(11,2) DEFAULT NULL,
					`payment_method_id` INT(11) DEFAULT NULL,
					`paid_at` DATETIME DEFAULT NULL,
					`payment_amount` DECIMAL(11,2) DEFAULT NULL,
					`payment_confirmation_number` VARCHAR(50) DEFAULT NULL,
					`notes` TEXT DEFAULT NULL,
					`created_at` DATETIME DEFAULT NULL,
					`updated_at` DATETIME DEFAULT NULL,
					PRIMARY KEY (`id`)
				)";
			$sql .= $charset_collate . ";"; // new line to avoid PHP Storm syntax error
			dbDelta( $sql );
		}
	}

	/**
	 *
	 */
	public function init()
	{
		if ( !session_id() )
		{
			session_start();
		}

		$parts = explode('?', $_SERVER['REQUEST_URI']);
		$this->base_page = $parts[0];

		wp_enqueue_script( 'out-spokane-pride-forms-js', plugin_dir_url( dirname( __FILE__ ) ) . 'js/pride-forms.js', array( 'jquery' ), time(), TRUE );
		wp_enqueue_style( 'out-spokane-pride-forms-bootstrap-grid', plugin_dir_url( dirname( __FILE__ ) ) . 'css/grid12.css', array(), time() );
		wp_enqueue_style( 'out-spokane-pride-forms-bootstrap-tables', plugin_dir_url( dirname( __FILE__ ) ) . 'css/bootstrap-tables.css', array(), time() );
		wp_enqueue_style( 'out-spokane-pride-forms-css', plugin_dir_url( dirname( __FILE__ ) ) . 'css/pride-forms.css', array(), time() );
	}

	public function param( $param, $default='' )
	{
		return (isset($_REQUEST[$param])) ? htmlspecialchars($_REQUEST[$param]) : $default;
	}

	public function queryVars( $vars )
	{
		$vars[] = 'sq_action';
		$vars[] = 'sq_data';
		return $vars;
	}

	/**
	 * @param $attributes
	 *
	 * @return string
	 */
	public function shortCode( $attributes )
	{
		$this->attributes = shortcode_atts( array(
			'form' => '',
			'year' => date( 'Y' )
		), $attributes );

		switch ( $this->getAttribute('form') )
		{
			case 'cruise':
			case 'festival':
			case 'murder_mystery':
			case 'parade':
				return $this->return . $this->returnOutputFromPage( $this->getAttribute('form') );
		}

		return $this->return;
	}

	/**
	 * @param $page
	 *
	 * @return string
	 */
	private function returnOutputFromPage( $page )
	{
		ob_start();
		include( dirname( __DIR__ ) . '/includes/' . $page . '.php' );
		return ob_get_clean();
	}

	/**
	 * @param $attribute
	 *
	 * @return string
	 */
	public function getAttribute( $attribute )
	{
		if (array_key_exists($attribute, $this->attributes))
		{
			return strtolower($this->attributes[$attribute]);
		}

		return '';
	}

	/**
	 *
	 */
	public function formCapture()
	{

	}
}