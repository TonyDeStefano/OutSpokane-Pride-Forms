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
	const VERSION_JS = '1.2.1';
	const VERSION_CSS = '1.0.1';

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
					`tickets_sent` TINYINT(4) DEFAULT NULL,
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
					`description` TEXT DEFAULT NULL,
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
					`tickets_sent` TINYINT(4) DEFAULT NULL,
					`created_at` DATETIME DEFAULT NULL,
					`updated_at` DATETIME DEFAULT NULL,
					PRIMARY KEY (`id`)
				)";
			$sql .= $charset_collate . ";"; // new line to avoid PHP Storm syntax error
			dbDelta( $sql );
		}

		/* donations table */
		$table = $wpdb->prefix . Donation::TABLE_NAME;
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
					`payment_method_id` INT(11) DEFAULT NULL,
					`paid_at` DATETIME DEFAULT NULL,
					`payment_amount` DECIMAL(11,2) DEFAULT NULL,
					`payment_confirmation_number` VARCHAR(50) DEFAULT NULL,
					`notes` TEXT DEFAULT NULL,
					`donation_amount` DECIMAL(11,2) DEFAULT NULL,
					`qty` INT(11) DEFAULT NULL,
					`price_per_qty` DECIMAL(11,2) DEFAULT NULL,
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
		/*
		global $wpdb;

		if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
		{
			return;
		}

		$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . CruiseEntry::TABLE_NAME );
		$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . FestivalEntry::TABLE_NAME );
		$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . MurderMysteryEntry::TABLE_NAME );
		$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . ParadeEntry::TABLE_NAME );
		$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . Donation::TABLE_NAME );
		*/
	}

	/**
	 *
	 */
	public function init()
	{
		$parts = explode('?', $_SERVER['REQUEST_URI']);
		$this->base_page = $parts[0];

		wp_enqueue_script( 'out-spokane-pride-forms-js', plugin_dir_url( dirname( __FILE__ ) ) . 'js/pride-forms.js', array( 'jquery' ), (WP_DEBUG) ? time() : self::VERSION_JS, TRUE );
		wp_localize_script( 'out-spokane-pride-forms-js', 'prideforms', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'entry_nonce' => wp_create_nonce( 'entry-nonce' )
		) );
		wp_enqueue_script( 'out-spokane-stripe', 'https://js.stripe.com/v2/', array( 'jquery' ), time(), TRUE);

		wp_enqueue_style( 'out-spokane-pride-forms-bootstrap-grid', plugin_dir_url( dirname( __FILE__ ) ) . 'css/grid12.css', array(), (WP_DEBUG) ? time() : self::VERSION_CSS );
		wp_enqueue_style( 'out-spokane-pride-forms-bootstrap-tables', plugin_dir_url( dirname( __FILE__ ) ) . 'css/bootstrap-tables.css', array(), (WP_DEBUG) ? time() : self::VERSION_CSS );
		wp_enqueue_style( 'out-spokane-pride-forms-css', plugin_dir_url( dirname( __FILE__ ) ) . 'css/pride-forms.css', array(), (WP_DEBUG) ? time() : self::VERSION_CSS );
	}

	/**
	 *
	 */
	public function enqueueAdminScripts()
	{
		wp_enqueue_script( 'out-spokane-admin-js', plugin_dir_url( dirname( __FILE__ ) ) . 'js/admin.js', array( 'jquery' ), (WP_DEBUG) ? time() : self::VERSION_JS, TRUE );
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
			'corner_booth' => 'yes'
		), $attributes );

		switch ( $this->getAttribute('form') )
		{
			case 'cruise':
			case 'festival':
			case 'murder_mystery':
			case 'parade':
			case 'donation':
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
		if ( isset( $_POST['pride_export'] ) )
		{
			Entry::exportToCsv();
			exit;
		}

		if ( isset( $_POST['edit_outspokane_entry'] ) )
		{
			switch ( $_POST['form'] )
			{
				case 'festival':
					$entry = new FestivalEntry( $_POST['id'] );
					break;
				case 'cruise':
					$entry = new CruiseEntry( $_POST['id'] );
					break;
				case 'parade':
					$entry = new ParadeEntry( $_POST['id'] );
					break;
				case 'donation':
					$entry = new Donation( $_POST['id'] );
					break;
				default:
					$entry = new MurderMysteryEntry( $_POST['id'] );
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
				->setQty( $_POST['qty'] );

			if ( $_POST['form'] == 'festival' )
			{
				$entry
					->setEntryTypeId( $_POST['entry_type_id'] )
					->setIsCornerBooth( $_POST['is_corner_booth'] )
					->setPricePerQty( preg_replace( '/[^0-9\.]/', '', $_POST['price_per_qty'] ) )
					->setPriceForCornerBooth( preg_replace( '/[^0-9\.]/', '', $_POST['price_for_corner_booth'] ) )
					->setDescription( $_POST['description'] );
			}
			elseif ( $_POST['form'] == 'cruise' )
			{
				$entry->setPricePerQty( preg_replace( '/[^0-9\.]/', '', $_POST['price_per_qty'] ) );
			}
			elseif ( $_POST['form'] == 'parade' )
			{
				$entry
					->setEntryTypes( $_POST['parade_entry_type'] )
					->setDescription( $_POST['description'] )
					->setFloatParkingSpaces( $_POST['float_parking_spaces'] )
					->setFloatParkingSpaceCost( preg_replace( '/[^0-9\.]/', '', $_POST['float_parking_space_cost'] ) )
					->setNeedsAmpedSound( $_POST['needs_amped_sound'] )
					->setGroupSize( $_POST['group_size'] );
			}
			elseif ( $_POST['form'] == 'murder_mystery' )
			{
				$entry
					->setIsSponsor( $_POST['is_sponsor'] )
					->setPricePerQty( preg_replace( '/[^0-9\.]/', '', $_POST['price_per_qty'] ) )
					->setIsUpgraded( $_POST['is_upgraded'] )
					->setVegetarianQty( $_POST['vegetarian_qty'] );
			}

			$entry->update();
			header( 'Location:admin.php?page=' . $_POST['return'] . '&action=view&id=' . $entry->getId() );
			exit;
		}

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
								case 'donation':
									$entry = new Donation( $parts[1] );
									$title = 'Donation';
									break;
								default: /* 'parade' */
									$entry = new ParadeEntry( $parts[1] );
									$title = 'Pride Parade Entry';
							}

							if ( $entry->getCreatedAt() !== NULL && isset( $_POST['stripeToken'] ) && strlen( $_POST['stripeToken'] ) > 0 )
							{
								$stripe_keys = Entry::getStripeKeys();
								Stripe::setApiKey( $stripe_keys['secret'] );
								Stripe::setApiVersion( '2016-03-07' );

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
		add_submenu_page('outspokane', 'Donations', 'Donations', 'manage_options', 'outspokane_donation', array($this, 'showDonations'));

		/* I guess this is how to add a page without adding a menu */
		add_submenu_page(NULL, 'Edit Entry', 'Edit Entry', 'manage_options', 'outspokane_edit_entry', array($this, 'editEntry'));
	}

	public function registerSettings()
	{
		register_setting( 'outspokane_settings', 'pride_forms_stripe_test_secret_key' );
		register_setting( 'outspokane_settings', 'pride_forms_stripe_test_pub_key' );
		register_setting( 'outspokane_settings', 'pride_forms_stripe_live_secret_key' );
		register_setting( 'outspokane_settings', 'pride_forms_stripe_live_pub_key' );
		register_setting( 'outspokane_settings', 'pride_forms_stripe_mode' );
		register_setting( 'outspokane_settings', 'pride_forms_disable_cruise_form' );
		register_setting( 'outspokane_settings', 'pride_forms_disable_donation_form' );
		register_setting( 'outspokane_settings', 'pride_forms_disable_festival_form' );
		register_setting( 'outspokane_settings', 'pride_forms_disable_murder_mystery_form' );
		register_setting( 'outspokane_settings', 'pride_forms_disable_parade_form' );
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

	/**
	 *
	 */
	public function showDonations()
	{
		include( dirname( __DIR__ ) . '/includes/donations.php');
	}

	/**
	 * 
	 */
	public function editEntry()
	{
		include( dirname( __DIR__ ) . '/includes/edit_entry.php');
	}

	/**
	 * 
	 */
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
			elseif ( $_POST['form'] == 'donation' && ( preg_replace( '/[^0-9\.]/', '', $_POST['donation_amount'] ) == '' || preg_replace( '/[^0-9\.]/', '', $_POST['donation_amount'] ) == 0 ) )
			{
				$response['success'] = 0;
				$response['error'] = 'Please enter a valid donation amount ' . preg_replace( '/[^0-9\.]/', '', $_POST['donation_amount'] );
			}
			else
			{
				switch ( $_POST['form'] )
				{
					case 'cruise':

						$subject = 'Cruise';
						$entry = new CruiseEntry;
						$entry
							->setQty( $_POST['qty'] )
							->setPricePerQty( CruiseEntry::PRICE_PER_TICKET )
							->setPaymentMethodId( Entry::PAYMENT_METHOD_CARD );

						break;

					case 'festival':

						$subject = 'Pride Festival';
						$entry = new FestivalEntry;
						$entry
							->setQty( 1 )
							->setDescription( $_POST['description'] )
							->setEntryTypeId( $_POST['entry_type_id'] )
							->setPriceForCornerBooth( FestivalEntry::CORNER_BOOTH_FEE )
							->setPricePerQty( $entry->getEntryTypePrice( $_POST['entry_type_id'] ) )
							->setIsCornerBooth( ( $_POST['entry_type_id'] == FestivalEntry::ENTRY_TYPE_SPONSOR ) ? FALSE : $_POST['corner_booth'] );

						break;

					case 'murder_mystery':

						$subject = 'Murder Mystery';
						/** @var MurderMysteryEntry $entry */
						$entry = new MurderMysteryEntry;
						$entry
							->setIsUpgraded( $_POST['is_upgraded'] )
							->setVegetarianQty( $_POST['vegetarian_qty'] );

						if ( $_POST['is_sponsor'] == 1 )
						{
							$entry
								->setQty( 1 )
								->setIsSponsor( TRUE )
								->setPricePerQty( ($entry->isUpgraded()) ? MurderMysteryEntry::UPGRADED_TABLE_PRICE : MurderMysteryEntry::TABLE_PRICE );
						}
						else
						{
							$entry
								->setQty( $_POST['qty'] )
								->setIsSponsor( FALSE )
								->setPricePerQty( ($entry->isUpgraded()) ? MurderMysteryEntry::UPGRADED_TICKET_PRICE : MurderMysteryEntry::TICKET_PRICE );
						}

						break;

					case 'donation':

						$subject = 'Donation';
						$entry = new Donation;
						$entry->setDonationAmount( preg_replace( '/[^0-9\.]/', '', $_POST['donation_amount'] ) );
						break;

					default: /* 'parade' */

						$subject = 'Pride Parade';
						$entry = new ParadeEntry;
						$entry
							->setQty( 1 )
							->setEntryTypes( stripslashes( $_POST['entry_types'] ) )
							->setFloatParkingSpaces( $_POST['float_parking_spaces'] )
							->setFloatParkingSpaceCost( ParadeEntry::FLOAT_PARKING_SPACE_COST )
							->setDonationAmount( preg_replace( '/[^0-9\.]/', '', $_POST['donation_amount'] ) )
							->setDescription( $_POST['description'] )
							->setNeedsAmpedSound( $_POST['needs_amped_sound'] )
							->setGroupSize( preg_replace( '/\D/', '', $_POST['group_size'] ) );
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
					->setUpdatedAt( time() )
					->create();

				$fields = array(
					'Entry Year',
					'Organization',
					'First Name',
					'Last Name',
					'Email',
					'Phone',
					'Address',
					'City',
					'State',
					'Zip',
					'Qty'
				);

				$subject = 'OutSpokane Receipt - ' . $entry->getEntryYear() . ' ' . $subject ;
				$body = '
					<p>Thank you! Below are the details of your transaction:</p>
					<table>
						<tr>
							<td><strong>Title:</strong></td>
							<td>' . $entry->getEntryYear() . ' ' . $subject . '</td>
						</tr>';

				foreach ( $fields as $field )
				{
					$body .= '
						<tr>
							<td><strong>' . $field . ':</strong></td>
							<td>' . $entry->getRaw( strtolower( str_replace( ' ', '_', $field ) ) ) . '</td>
						</tr>';
				}


				$body .= '
						<tr>
							<td><strong>Total:</strong></td>
							<td>$' . number_format( $entry->getTotal(), 2 ) . '</td>
						</tr>
					</table>
					<p>View the complete details of your transaction here:</p>
					<p><a href="https://outspokane.org' . $_POST['path'] . '?txid=' . $entry->getCreatedAt() . '-' . $entry->getId() . '">https://outspokane.org' . $_POST['path'] . '?txid=' . $entry->getCreatedAt() . '-' . $entry->getId() . '</a></p>';


				$headers = array(
					'Content-Type: text/html; charset=UTF-8',
					'From info@outspokane.org'
				);
				wp_mail( $_POST['email'], $subject, $body, $headers );
				wp_mail( 'info@outspokane.org', 'BCC: ' . $subject, $body, $headers );

				$response['txid'] = $entry->getCreatedAt() . '-' . $entry->getId();
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

	/**
	 *
	 */
	public function updateEntryNotes()
	{
		if ( isset($_POST['form']) && isset($_POST['id']) && isset($_POST['notes']) )
		{
			switch ($_POST['form'])
			{
				case 'cruise':
					$entry = new CruiseEntry( $_POST['id'] );
					break;
				case 'festival':
					$entry = new FestivalEntry( $_POST['id'] );
					break;
				case 'murder_mystery':
					$entry = new MurderMysteryEntry( $_POST['id'] );
					break;
				case 'donation':
					$entry = new Donation( $_POST['id'] );
					break;
				default: /* parade */
					$entry = new ParadeEntry( $_POST['id'] );
			}

			if ( $entry->getCreatedAt() !== NULL )
			{

				$entry
					->setNotes( $_POST['notes'] )
					->update();

				echo 1;
				exit;
			}
		}

		echo 0;
	}

	/**
	 *
	 */
	public function updateEntryDetails()
	{
		switch ($_POST['form'])
		{
			case 'cruise':
				$entry = new CruiseEntry( $_POST['id'] );
				break;
			case 'festival':
				$entry = new FestivalEntry( $_POST['id'] );
				break;
			case 'murder_mystery':
				$entry = new MurderMysteryEntry( $_POST['id'] );
				break;
			case 'donation':
				$entry = new Donation( $_POST['id'] );
				break;
			default: /* parade */
				$entry = new ParadeEntry( $_POST['id'] );
		}

		if ( $entry->getCreatedAt() !== NULL )
		{
			if ( $_POST['form'] == 'murder_mystery' )
			{
				$entry
					->setVegetarianQty( $_POST['vegetarian_qty'] )
					->setTicketsSent( $_POST['tickets_sent'] );
			}
			elseif ( $_POST['form'] == 'cruise' )
			{
				$entry->setTicketsSent( $_POST['tickets_sent'] );
			}
		}

		$entry->update();

		echo 1;
		exit;
	}

	/**
	 *
	 */
	public function updateEntryPayment()
	{
		if ( isset($_POST['form']) && isset($_POST['id']) && isset($_POST['payment_method_id']) )
		{
			switch ($_POST['form'])
			{
				case 'cruise':
					$entry = new CruiseEntry( $_POST['id'] );
					break;
				case 'festival':
					$entry = new FestivalEntry( $_POST['id'] );
					break;
				case 'murder_mystery':
					$entry = new MurderMysteryEntry( $_POST['id'] );
					break;
				case 'donation':
					$entry = new Donation( $_POST['id'] );
					break;
				default: /* parade */
					$entry = new ParadeEntry( $_POST['id'] );
			}

			if ( $entry->getCreatedAt() !== NULL )
			{
				$entry->setPaymentMethodId( $_POST['payment_method_id'] );
				if ( $entry->getPaymentMethodId() === NULL )
				{
					$entry
						->setPaymentAmount( NULL )
						->setPaidAt( NULL );
				}
				else
				{
					$entry
						->setPaymentAmount( $entry->getTotal() )
						->setPaidAt( time() );
				}

				$entry->update();

				echo 1;
				exit;
			}
		}

		echo 0;
	}

	public function deleteEntry()
	{
		if ( isset($_POST['form']) && isset($_POST['id']) )
		{
			switch ($_POST['form'])
			{
				case 'cruise':
					$entry = new CruiseEntry( $_POST['id'] );
					break;
				case 'festival':
					$entry = new FestivalEntry( $_POST['id'] );
					break;
				case 'murder_mystery':
					$entry = new MurderMysteryEntry( $_POST['id'] );
					break;
				case 'donation':
					$entry = new Donation( $_POST['id'] );
					break;
				default: /* parade */
					$entry = new ParadeEntry( $_POST['id'] );
			}

			$entry->delete();
			echo 'outspokane_' . $_POST['form'];
			exit;
		}

		echo 0;
	}
}