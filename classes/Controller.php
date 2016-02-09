<?php
/**
 * Created by PhpStorm.
 * User: Tony DeStefano
 * Date: 2/1/16
 * Time: 3:58 PM
 */

namespace OutSpokane;

use \Stripe\Stripe;
use \Stripe\Charge;
use \Stripe\Error\Card;

class Controller {

	const VERSION = '1.0.0';

	public $action = '';
	public $data = '';
	public $return = '';
	public $attributes = array();
	public $base_page = '';
	public $error = '';
	public $successes = '';

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
					`is_corner_booth` TINYINT(4) DEFAULT NULL,
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
					`price_for_corner_booth` DECIMAL(11,2) DEFAULT NULL,
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
	public static function uninstall()
	{
		global $wpdb;

		if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
		{
			return;
		}

		$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . CruiseEntry::TABLE_NAME );
		$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . FestivalEntry::TABLE_NAME );
		$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . MurderMysteryEntry::TABLE_NAME );
		$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . ParadeEntry::TABLE_NAME );
	}

	/**
	 *
	 */
	public function init()
	{
		$parts = explode('?', $_SERVER['REQUEST_URI']);
		$this->base_page = $parts[0];

		wp_enqueue_script( 'out-spokane-pride-forms-js', plugin_dir_url( dirname( __FILE__ ) ) . 'js/pride-forms.js', array( 'jquery' ), time(), TRUE );
		wp_localize_script( 'out-spokane-pride-forms-js', 'prideforms', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'entry_nonce' => wp_create_nonce( 'entry-nonce' )
		) );
		wp_enqueue_script( 'out-spokane-stripe', 'https://js.stripe.com/v2/', array( 'jquery' ), time(), TRUE);

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
			'year' => date( 'Y' ),
			'corner_booth_option' => 'yes'
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
		if ( isset( $_POST['pride_action'] ) )
		{
			if ( wp_verify_nonce( $_POST['_wpnonce'], 'pride-nonce' ) )
			{
				if ( $_POST['pride_action'] == 'cc' )
				{
					$parts = explode( '-', $_POST['txid'] );
					if ( count( $parts ) == 2 )
					{
						if ( is_numeric( $parts[1] ) )
						{
							switch ( $_POST['form'] )
							{
								case 'cruise':
									$entry = new CruiseEntry( $parts[1] );
									$title = 'Pride Cruise';
									break;
								case 'festival':
									$entry = new FestivalEntry( $parts[1] );
									$title = 'Pride Festival Entry';
									break;
								case 'murder_mystery':
									$entry = new MurderMysteryEntry( $parts[1] );
									$title = 'Murder Mystery Ticket';
									break;
								default: /* 'parade' */
									$entry = new ParadeEntry( $parts[1] );
									$title = 'Pride Parade Entry';
							}

							if ( $entry->getCreatedAt() !== NULL )
							{
								$stripe_keys = Entry::getStripeKeys();
								Stripe::setApiKey( $stripe_keys['secret'] );

								try
								{
									/** @var \Stripe\Charge $charge */
									$charge = Charge::create( array(
										'amount' => round( $entry->getAmountDue() * 100 ),
										'currency' => 'usd',
										'source' => $_POST['stripeToken'],
										'description' => $entry->getEntryYear() . ' ' . $title
									) );

									$entry
										->setPaidAt( time() )
										->setPaymentMethodId( Entry::PAYMENT_METHOD_CARD )
										->setPaymentAmount( $entry->getAmountDue() )
										->setPaymentConfirmationNumber( $charge->id )
										->update();

									header( 'Location:' . $_POST['_wp_http_referer'] );
									exit;
								}
								catch ( Card $e )
								{
									/* card was declined */
								}
							}
						}
					}
				}
			}
		}
	}

	/**
	 *
	 */
	public function addMenus()
	{
		add_menu_page('OutSpokane', 'OutSpokane', 'manage_options', 'outspokane', array( $this, 'showSettingsPage' ), 'dashicons-flag');
		add_submenu_page('outspokane', 'Settings', 'Settings', 'manage_options', 'outspokane');
		add_submenu_page('outspokane', 'Short Code', 'Short Code', 'manage_options', 'outspokane_shortcode', array($this, 'showShortCode'));
		add_submenu_page('outspokane', 'Cruise Entries', 'Cruise Entries', 'manage_options', 'outspokane_cruise', array($this, 'showCruiseEntries'));
		add_submenu_page('outspokane', 'Parade Entries', 'Parade Entries', 'manage_options', 'outspokane_parade', array($this, 'showParadeEntries'));
		add_submenu_page('outspokane', 'Festival Entries', 'Festival Entries', 'manage_options', 'outspokane_festival', array($this, 'showFestivalEntries'));
		add_submenu_page('outspokane', 'Murder Mystery Entries', 'Murder Mystery Entries', 'manage_options', 'outspokane_murder_mystery', array($this, 'showMurderMysteryEntries'));
	}

	public function registerSettings()
	{
		register_setting( 'outspokane_settings', 'pride_forms_stripe_test_secret_key' );
		register_setting( 'outspokane_settings', 'pride_forms_stripe_test_pub_key' );
		register_setting( 'outspokane_settings', 'pride_forms_stripe_live_secret_key' );
		register_setting( 'outspokane_settings', 'pride_forms_stripe_live_pub_key' );
		register_setting( 'outspokane_settings', 'pride_forms_stripe_mode' );
	}

	/**
	 *
	 */
	public function showSettingsPage()
	{
		include( dirname( __DIR__ ) . '/includes/settings.php');
	}

	/**
	 *
	 */
	public function showShortCode()
	{
		include( dirname( __DIR__ ) . '/includes/shortcode.php');
	}

	/**
	 *
	 */
	public function showCruiseEntries()
	{
		include( dirname( __DIR__ ) . '/includes/cruise_entries.php');
	}

	/**
	 *
	 */
	public function showParadeEntries()
	{
		include( dirname( __DIR__ ) . '/includes/parade_entries.php');
	}

	/**
	 *
	 */
	public function showFestivalEntries()
	{
		include( dirname( __DIR__ ) . '/includes/festival_entries.php');
	}

	/**
	 *
	 */
	public function showMurderMysteryEntries()
	{
		include( dirname( __DIR__ ) . '/includes/murder_mystery_entries.php');
	}

	public function handleNewAjaxEntry()
	{
		$response = array(
			'success' => 1,
			'error' => ''
		);

		if ( wp_verify_nonce($_POST['entry_nonce'], 'entry-nonce'))
		{
			if ( ! filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL ) )
			{
				$response['success'] = 0;
				$response['error'] = 'The email address you entered is not valid';
			}
			else
			{
				switch ( $_POST['form'] ) {
					case 'cruise':
						$entry = new CruiseEntry;
						break;
					case 'festival':
						$entry = new FestivalEntry;
						break;
					case 'murder_mystery':
						$entry = new MurderMysteryEntry;
						break;
					default: /* 'parade' */
						$entry = new ParadeEntry;
				}

				$entry
					->setEntryYear( $_POST['entry_year'] )
					->setOrganization( $_POST['organization'] )
					->setFirstName( $_POST['first_name'] )
					->setLastName( $_POST['last_name'] )
					->setEmail( $_POST['email'] )
					->setPhone( $_POST['phone'] )
					->setAddress( $_POST['address'] )
					->setCity( $_POST['city'] )
					->setState( $_POST['state'] )
					->setZip( $_POST['zip'] )
					->setCreatedAt( time() )
					->setUpdatedAt( time() );

				switch ( $_POST['form'] )
				{
					case 'cruise':

						/** @var CruiseEntry $entry */
						$entry
							->setQty( $_POST['qty'] )
							->setPricePerQty( CruiseEntry::PRICE_PER_TICKET )
							->setPaymentMethodId( Entry::PAYMENT_METHOD_CARD )
							->create();

						$response['txid'] = $entry->getCreatedAt() . '-' . $entry->getId();

						break;

					case 'festival':

						/** @var FestivalEntry $entry */
						$entry
							->setQty( $_POST['qty'] )
							->setEntryTypeId( $_POST['entry_type_id'] )
							->setPriceForCornerBooth( FestivalEntry::CORNER_BOOTH_FEE )
							->setPricePerQty( $entry->getEntryTypePrice( $_POST['entry_type_id'] ) )
							->setIsCornerBooth( ( $_POST['entry_type_id'] == FestivalEntry::ENTRY_TYPE_SPONSOR ) ? FALSE : $_POST['corner_booth'] )
							->create();

						$response['txid'] = $entry->getCreatedAt() . '-' . $entry->getId();

						break;

					case 'murder_mystery':


						break;

					default: /* 'parade' */


				}
			}
		}
		else
		{
			$response['success'] = 0;
			$response['error'] = 'There was a problem. Please try again.';
		}

		header( 'Content-Type: application/json' );
		echo json_encode( $response );
		exit;
	}
}