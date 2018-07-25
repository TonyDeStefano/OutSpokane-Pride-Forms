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
	const PAYMENT_METHOD_SQUARE = 5;

	protected $table_name;
	protected $id;
	protected $entry_year;
	protected $email;
	protected $phone;
	protected $organization;
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
	protected $payment_confirmation_number;
	protected $notes;
	protected $created_at;
	protected $updated_at;

	/**
	 * Entry constructor.
	 *
	 * @param null $id
	 */
	public function __construct( $id=NULL ) {

		$this->setId( $id );
	}

	/**
	 * @return array|null|object|void
	 */
	public function read()
	{
		global $wpdb;

		if ( $this->id !== NULL && $this->table_name != '' )
		{
			$sql = $wpdb->prepare("
				SELECT
					*
				FROM
					" . $wpdb->prefix . $this->table_name . "
				WHERE
					id = %d",
				$this->id
			);

			if ( $row = $wpdb->get_row( $sql ) )
			{
				return $row;
			}
		}

		return NULL;
	}

	public function update()
	{
		global $wpdb;

		if ($this->id !== NULL && $this->table_name !== NULL)
		{
			$this->setUpdatedAt( time() );

			$wpdb->update(
				$wpdb->prefix . $this->table_name,
				array(
					'entry_year' => $this->entry_year,
					'email' => $this->email,
					'phone' => $this->phone,
					'organization' => $this->organization,
					'first_name' => $this->first_name,
					'last_name' => $this->last_name,
					'address' => $this->address,
					'city' => $this->city,
					'state' => substr( $this->state, 0, 2),
					'zip' => $this->zip,
					'qty' => $this->qty,
					'price_per_qty' => $this->price_per_qty,
					'payment_method_id' => $this->payment_method_id,
					'paid_at' => ( $this->paid_at === NULL ) ? NULL : $this->getPaidAt( 'Y-m-d H:i:s' ),
					'payment_amount' => $this->payment_amount,
					'payment_confirmation_number' => $this->payment_confirmation_number,
					'notes' => $this->notes,
					'updated_at' => $this->getUpdatedAt( 'Y-m-d H:i:s' )
				),
				array(
					'id' => $this->id
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
					'%d',
					'%f',
					'%d',
					'%s',
					'%f',
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
		$this
			->setId( $row->id )
			->setEntryYear( $row->entry_year )
			->setEmail( $row->email )
			->setPhone( $row->phone )
			->setOrganization( $row->organization )
			->setFirstName( $row->first_name )
			->setLastName( $row->last_name )
			->setAddress( $row->address )
			->setCity( $row->city )
			->setState( $row->state )
			->setZip( $row->zip )
			->setPaymentMethodId( $row->payment_method_id )
			->setPaidAt( $row->paid_at )
			->setPaymentAmount( $row->payment_amount )
			->setPaymentConfirmationNumber( $row->payment_confirmation_number )
			->setNotes( $row->notes )
			->setCreatedAt( $row->created_at )
			->setUpdatedAt( $row->updated_at );

		if ( isset( $row->qty ) )
		{
			$this
				->setQty( $row->qty )
				->setPricePerQty( $row->price_per_qty );
		}
	}

	/**
	 *
	 */
	public function create()
	{

	}

	/**
	 *
	 */
	public function delete()
	{
		global $wpdb;
		if ($this->id !== NULL && $this->table_name !== NULL)
		{
			$wpdb->delete(
				$wpdb->prefix . $this->table_name,
				array(
					'id' => $this->id
				),
				array(
					'%d'
				)
			);
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
	 * @return $this
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
	 * @return $this
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
	 * @return $this
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
	 * @return $this
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
	 * @return $this
	 */
	public function setPhone( $phone ) {
		$this->phone = $phone;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getOrganization() {
		return $this->organization;
	}

	/**
	 * @param mixed $organization
	 *
	 * @return $this
	 */
	public function setOrganization( $organization ) {
		$this->organization = $organization;

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
	 * @return $this
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
	 * @return $this
	 */
	public function setLastName( $last_name ) {
		$this->last_name = $last_name;

		return $this;
	}

	public function getName() {
		return $this->first_name . ' ' . $this->last_name;
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
	 * @return $this
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
	 * @return $this
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
	 * @return $this
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
	 * @return $this
	 */
	public function setZip( $zip ) {
		$this->zip = $zip;

		return $this;
	}

	public function getCSZ() {
		return $this->city . ', ' . $this->state . ' ' . $this->zip;
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
	 * @return $this
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
	 * @return $this
	 */
	public function setPricePerQty( $price_per_qty ) {
		$this->price_per_qty = (is_numeric($price_per_qty)) ? abs(round($price_per_qty, 2)) : NULL;

		return $this;
	}

	/**
	 * @param null $payment_method_id
	 *
	 * @return string
	 */
	public static function getPaymentMethod( $payment_method_id )
	{
		switch ( $payment_method_id )
		{
			case self::PAYMENT_METHOD_CASH:
				return 'Cash';
			case self::PAYMENT_METHOD_CARD:
				return 'Credit Card';
			case self::PAYMENT_METHOD_CHECK:
				return 'Check';
			case self::PAYMENT_METHOD_MO:
				return 'Money Order';
			case self::PAYMENT_METHOD_SQUARE:
				return 'Square';
			default:
				return '';
		}
	}

	/**
	 * @return array
	 */
	public function getPaymentMethods()
	{
		return array(
			self::PAYMENT_METHOD_CASH => $this->getPaymentMethod( self::PAYMENT_METHOD_CASH ),
			self::PAYMENT_METHOD_CARD => $this->getPaymentMethod( self::PAYMENT_METHOD_CARD ),
			self::PAYMENT_METHOD_CHECK => $this->getPaymentMethod( self::PAYMENT_METHOD_CHECK ),
			self::PAYMENT_METHOD_MO => $this->getPaymentMethod( self::PAYMENT_METHOD_MO ),
			self::PAYMENT_METHOD_SQUARE => $this->getPaymentMethod( self::PAYMENT_METHOD_SQUARE )
		);
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
	 * @return $this
	 */
	public function setPaymentMethodId( $payment_method_id ) {
		$this->payment_method_id = (in_array($payment_method_id, array(1, 2, 3, 4, 5))) ? $payment_method_id : NULL;

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
	 * @return $this
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
	 * @return $this
	 */
	public function setPaymentAmount( $payment_amount ) {
		$this->payment_amount = (is_numeric($payment_amount)) ? abs(round($payment_amount, 2)) : NULL;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getPaymentConfirmationNumber() {
		return $this->payment_confirmation_number;
	}

	/**
	 * @param mixed $payment_confirmation_number
	 *
	 * @return $this
	 */
	public function setPaymentConfirmationNumber( $payment_confirmation_number ) {
		$this->payment_confirmation_number = $payment_confirmation_number;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getNotes() {
		return $this->notes;
	}

	/**
	 * @param mixed $notes
	 *
	 * @return $this
	 */
	public function setNotes( $notes ) {
		$this->notes = $notes;

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
	 * @return $this
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
	 * @return $this
	 */
	public function setUpdatedAt( $updated_at ) {
		$this->updated_at = (is_numeric($updated_at) || $updated_at === NULL) ? $updated_at : strtotime($updated_at);

		return $this;
	}

	/**
	 * @return float
	 */
	public function getTotal() {
		return round( $this->getQty() * $this->getPricePerQty(), 2 );
	}

	/**
	 * @return float
	 */
	public function getAmountDue() {
		return round( $this->getTotal() - $this->getPaymentAmount(), 2);
	}

	/**
	 * @param null $exclusions
	 * @param bool $hide_position_field
	 */
	public static function drawDefaultFormFields( $exclusions = NULL, $hide_position_field = TRUE )
	{
		$exclusions = ($exclusions === NULL) ? array() : $exclusions;
		$inclusions = array(
			'Organization',
			'First Name',
			'Last Name',
			'Position',
			'Email',
			'Phone',
			'Address',
			'City',
			'State',
			'Zip'
		);

		if ( $hide_position_field )
		{
			unset( $inclusions[3] );
		}

		foreach ($inclusions as $inclusion)
		{
			if ( ! in_array(preg_replace('/[^A-Za-z0-9]/', '_', strtolower( $inclusion ) ), $exclusions))
			{
				self::drawFormField( $inclusion );
			}
		}
	}

	/**
	 * @param string $label
	 * @param string $id
	 * @param string $type
	 * @param array $options
	 */
	public static function drawFormField( $label, $id=NULL, $type='text', $options=NULL )
	{
		$id = ($id === NULL) ? preg_replace('/[^A-Za-z0-9]/', '_', strtolower($label)) : $id;

		echo '
			<div class="row">
				<div class="col-md-3">
					<label for="' . $id . '">' . $label . '</label>
				</div>
				<div class="col-md-6">';

		switch ( $type )
		{
			case 'textarea':
				echo '<textarea class="form-control" data-label="' . $label . '" id="' . $id . '"></textarea>';
				break;
			case 'select':
				echo '<select class="form-control" data-label="' . $label . '" id="' . $id . '">';
				foreach ( $options as $key => $val )
				{
					echo '<option value="' . esc_html( $key ) . '">' . $val . '</option>';
				}
				echo '</select>';
				break;
			default:
				echo '<input class="form-control" data-label="' . $label . '" id="' . $id . '">';
		}

		echo '
				</div>
			</div>';
	}

	/* TODO: move this to Controller class ... not sure why I put it here */

	/**
	 * @return array
	 */
	public static function getStripeKeys()
	{
		$mode = ( get_option('pride_forms_stripe_mode') == 'live' ) ? 'live' : 'test';

		return array(
			'secret' => get_option( 'pride_forms_stripe_'.$mode.'_secret_key' ),
			'pub' => get_option( 'pride_forms_stripe_'.$mode.'_pub_key' )
		);
	}

	/**
	 * @return array
	 */
	public function getUniqueYears()
	{
		$years = array();

		if ( $this->table_name != '' )
		{
			$years = self::getUniqueEntryYears( $this->table_name );
		}

		return $years;
	}

	/**
	 * @param $table_name
	 *
	 * @return array
	 */
	public static function getUniqueEntryYears( $table_name )
	{
		global $wpdb;

		$years = array();

		if ( strlen( $table_name ) > 0 )
		{
			$rows = $wpdb->get_results("
				SELECT DISTINCT
					entry_year
				FROM
					`" . $wpdb->prefix . esc_sql( $table_name ) . "`
				ORDER BY
					entry_year DESC"
			);

			if ( $rows )
			{
				foreach ( $rows as $row )
				{
					$years[] = $row->entry_year;
				}
			}
		}

		return $years;
	}

	public static function drawExportForm( $table_name )
	{
		$years = self::getUniqueEntryYears( $table_name );
		if ( count( $years ) > 0 ) {
			echo "
				<form class='well' method='post'>
					<input type='hidden' name='table' value='" . $table_name . "'>
					<select name='pride_export'>
						<option value=''>
							Export All Years
						</option>";
			foreach ( $years as $year ) {
				echo "
						<option value='" . $year . "'>
							Export " . $year . "
						</option>";
			}
			echo "
					</select>
					<input type='submit' value='Export'>
				</form>";

		}
	}

	public static function exportToCsv()
	{
		/** @var \wpdb $wpdb */
		global $wpdb;

		$tables = array(
			CruiseEntry::TABLE_NAME => 'cruise',
			FestivalEntry::TABLE_NAME => 'festival',
			MurderMysteryEntry::TABLE_NAME => 'murder-mystery',
			ParadeEntry::TABLE_NAME => 'parade',
			Donation::TABLE_NAME => 'donation',
			FlagHandle::TABLE_NAME => 'flag-handle',
			Sponsorship::TABLE_NAME => 'sponsorship',
			BowlingEntry::TABLE_NAME => 'bowling'
		);

		$year = ( isset( $_POST['pride_export'] ) && is_numeric( $_POST['pride_export'] ) ) ? abs( round( $_POST['pride_export'] ) ) : NULL;
		$table = ( isset( $_POST['table'] ) && array_key_exists( $_POST['table'], $tables ) ) ? $_POST['table'] : NULL;

		header('Content-type: text/csv');

		if ( $table === NULL )
		{
			header( 'Content-disposition: attachment;filename=export.csv' );
			echo 'No Data to Export';
		}
		else
		{
			header( 'Content-disposition: attachment;filename=' . ( ( $year !== NULL ) ? $year : 'all' ) . '-' . $tables[ $table ] . '-entries.csv' );
			echo "Entry Date,Entry Year,Organization,First Name,Last Name,Email,Phone,Address,City,State,Zip,";
			switch ( $table )
			{
				case FestivalEntry::TABLE_NAME:
					echo "Entry Type,Corner Booth,";
					break;
				case MurderMysteryEntry::TABLE_NAME:
					echo "Tickets,Entry Type,";
					break;
				case ParadeEntry::TABLE_NAME:
					echo "Entry Types,Description,Parking Spots,Amped Sound,Group Size,Donation,";
					break;
				case Donation::TABLE_NAME:
					echo "Donation,";
					break;
				case FlagHandle::TABLE_NAME:
					echo "Message,Color,";
					break;
                case CruiseEntry::TABLE_NAME:
                    echo "Delivery,";
                    break;
			}
			echo "Qty,Amount Due,Amount Paid,Payment Method,Paid On,Notes";

			$rows = $wpdb->get_results("
				SELECT
					*
				FROM
					" . $wpdb->prefix . $table . "
				WHERE
					entry_year " . ( ( $year === NULL ) ? " > 0 " : " = " . $year ) . "
				ORDER BY
					id ASC");

			if ( $rows )
			{
				foreach ( $rows as $row )
				{
					switch ( $table )
					{
						case CruiseEntry::TABLE_NAME:
							$entry = new CruiseEntry;
							break;
						case FestivalEntry::TABLE_NAME:
							$entry = new FestivalEntry;
							break;
						case MurderMysteryEntry::TABLE_NAME:
							$entry = new MurderMysteryEntry;
							break;
						case ParadeEntry::TABLE_NAME:
							$entry = new ParadeEntry;
							break;
						case Donation::TABLE_NAME:
							$entry = new Donation;
							break;
						case FlagHandle::TABLE_NAME:
							$entry = new FlagHandle;
							break;
						case Sponsorship::TABLE_NAME:
							$entry = new Sponsorship;
							break;
						default:
							$entry = new Entry;
					}

					$entry->loadFromRow( $row );

					echo "\r\n";
					echo $entry->getCreatedAt( 'n/j/Y' ) . ',';
					echo $entry->getEntryYear() . ',';
					echo '"' . str_replace( '"', '""', $entry->getOrganization() ) . '",';
					echo '"' . str_replace( '"', '""', $entry->getFirstName() ) . '",';
					echo '"' . str_replace( '"', '""', $entry->getLastName() ) . '",';
					echo '"' . str_replace( '"', '""', $entry->getEmail() ) . '",';
					echo '"' . str_replace( '"', '""', $entry->getPhone() ) . '",';
					echo '"' . str_replace( '"', '""', $entry->getAddress() ) . '",';
					echo '"' . str_replace( '"', '""', $entry->getCity() ) . '",';
					echo '"' . str_replace( '"', '""', $entry->getState() ) . '",';
					echo '"' . str_replace( '"', '""', $entry->getZip() ) . '",';

					switch ( $table )
					{
						case FestivalEntry::TABLE_NAME:
							echo '"' . str_replace( '"', '""', $entry->getEntryType() ) . '",';
							echo ( ( $entry->isCornerBooth() ) ? 'Y' : 'N' ) . ',';
							break;
						case MurderMysteryEntry::TABLE_NAME:
							if ( $entry->isVip() )
                            {
                                echo "8,VIP Table,";
                            }
                            else
                            {
                                echo $entry->getQty() . ",";
                                if ( $entry->isSponsor() )
                                {
                                    echo "Sponsor Tickets,";
                                }
                                else
                                {
                                    echo "Tickets,";
                                }
                            }
							break;
						case ParadeEntry::TABLE_NAME:
							echo '"' . str_replace('"', '""', implode( ',', $entry->getEntryTypes() ) ) . '",';
							echo '"' . str_replace( "\n", ' ', str_replace( "\r", ' ', str_replace( '"', '""', $entry->getDescription() ) ) ) . '",';
							echo $entry->getFloatParkingSpaces() . ',';
							echo ( ( $entry->needsAmpedSound() ) ? 'Y' : 'N' ) . ',';
							echo $entry->getGroupSize( TRUE ) . ',';
							echo $entry->getDonationAmount() . ',';
							break;
						case Donation::TABLE_NAME:
							echo $entry->getDonationAmount() . ',';
							break;
						case FlagHandle::TABLE_NAME:
							echo '"' . str_replace( "\n", ' ', str_replace( "\r", ' ', str_replace( '"', '""', $entry->getMessage() ) ) ) . '",';
							echo '"' . str_replace( "\n", ' ', str_replace( "\r", ' ', str_replace( '"', '""', $entry->getColor() ) ) ) . '",';
							break;
                        case CruiseEntry::TABLE_NAME:
                            echo ( ( $entry->isWillCall() ) ? 'Will Call' : 'Mail' ) . ',';
                            break;
					}

					echo $entry->getQty() . ',';
					echo $entry->getTotal() . ',';
					echo $entry->getPaymentAmount() . ',';
					echo '"' . str_replace( '"', '""', $entry->getPaymentMethod( $entry->getPaymentMethodId() ) ) . '",';
					echo $entry->getPaidAt( 'n/j/Y' ) . ',';
					echo '"' . str_replace( "\n", ' ', str_replace( "\r", ' ', str_replace( '"', '""', $entry->getNotes() ) ) ) . '",';
				}
			}
		}

		exit;
	}

	/**
	 * @param $attribute
	 *
	 * @return string
	 */
	public function getRaw( $attribute )
	{
		if ( isset( $this->$attribute ) )
		{
			return $this->$attribute;
		}

		return '';
	}
}