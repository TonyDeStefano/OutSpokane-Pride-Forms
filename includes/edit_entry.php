<?php

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

switch ( $_GET['form'] )
{
	case 'festival':
		$form_title = 'Festival';
		$back_page = 'outspokane_festival';
		$entry = new \OutSpokane\FestivalEntry( $_GET['id'] );
		break;
	case 'cruise':
		$form_title = 'Cruise';
		$back_page = 'outspokane_cruise';
		$entry = new \OutSpokane\CruiseEntry( $_GET['id'] );
		break;
	case 'parade':
		$form_title = 'Parade';
		$back_page = 'outspokane_parade';
		$entry = new \OutSpokane\ParadeEntry( $_GET['id'] );
		break;
	case 'flag':
		$form_title = 'Flag Handle';
		$back_page = 'outspokane_flag';
		$entry = new \OutSpokane\FlagHandle( $_GET['id'] );
		break;
	case 'donation':
		$form_title = 'Donation';
		$back_page = 'outspokane_donation';
		$entry = new \OutSpokane\Donation( $_GET['id'] );
		break;
	case 'sponsorship':
		$form_title = 'Sponsorship';
		$back_page = 'outspokane_sponsorship';
		$entry = new \OutSpokane\Sponsorship( $_GET['id'] );
		unset( $fields[10] );
		break;
	default:
		$form_title = 'Murder Mystery';
		$back_page = 'outspokane_murder_mystery';
		$entry = new \OutSpokane\MurderMysteryEntry( $_GET['id'] );
}

?>

<div class="wrap">

	<h1>
		Edit <?php echo $form_title; ?> Entry
		<a href="?page=<?php echo $back_page; ?>&action=view&id=<?php echo $entry->getId(); ?>" class="page-title-action">
			Back
		</a>
	</h1>

	<?php if ( $entry->getId() === NULL ) { ?>

		<p>
			The entry you are trying to edit is currently unavailable.
		</p>

	<?php } else { ?>

		<form method="post" autocomplete="off">

			<input type="hidden" name="edit_outspokane_entry" value="">
			<input type="hidden" name="return" value="<?php echo $back_page; ?>">
			<input type="hidden" name="form" value="<?php echo $_GET['form']; ?>">
			<input type="hidden" name="id" value="<?php echo $entry->getId(); ?>">

			<table class="table">
				<?php foreach ( $fields as $field ) { ?>
					<tr>
						<th><?php echo $field; ?>:</th>
						<td><input name="<?php echo strtolower( str_replace( ' ', '_', $field ) ); ?>" value="<?php echo esc_html( $entry->getRaw( strtolower( str_replace( ' ', '_', $field ) ) ) ); ?>"></td>
					</tr>
				<?php } ?>
				<?php if ( $_GET['form'] == 'festival' ) { ?>
					<?php $entry_types = $entry->getEntryTypes(); ?>
					<tr>
						<th>Entry Type:</th>
						<td>
							<select name="entry_type_id">
								<?php foreach ($entry_types as $entry_type_id => $entry_type) { ?>
									<option value="<?php echo $entry_type_id; ?>"<?php if ( $entry_type_id == $entry->getEntryTypeId() ) { ?> selected<?php } ?>>
										<?php echo $entry_type; ?>
									</option>
								<?php } ?>
							</select>
						</td>
					</tr>
					<tr>
						<th>Price Per Entry:</th>
						<td><input name="price_per_qty" value="$<?php echo number_format( $entry->getPricePerQty(), 2 ); ?>"></td>
					</tr>
					<tr>
						<th>Corner Booth:</th>
						<td>
							<select name="is_corner_booth">
								<option value="0"<?php if ( ! $entry->isCornerBooth() ) { ?> selected<?php } ?>>
									No
								</option>
								<option value="1"<?php if ( $entry->isCornerBooth() ) { ?> selected<?php } ?>>
									Yes
								</option>
							</select>
						</td>
					</tr>
					<tr>
						<th>Price For Corner Booth:</th>
						<td><input name="price_for_corner_booth" value="$<?php echo number_format( $entry->getPriceForCornerBooth(), 2 ); ?>"></td>
					</tr>
					<tr>
						<th>Description:</th>
						<td><textarea name="description"><?php echo esc_html( $entry->getDescription() ); ?></textarea></td>
					</tr>
				<?php } else if ( $_GET['form'] == 'cruise' ) { ?>
					<tr>
						<th>Price Per Ticket:</th>
						<td><input name="price_per_qty" value="$<?php echo number_format( $entry->getPricePerQty(), 2 ); ?>"></td>
					</tr>
				<?php } else if ( $_GET['form'] == 'donation' ) { ?>
					<tr>
						<th>Donation Amount:</th>
						<td><input name="donation_amount" value="$<?php echo number_format( $entry->getDonationAmount(), 2 ); ?>"></td>
					</tr>
				<?php } else if ( $_GET['form'] == 'flag' ) { ?>
					<tr>
						<th>Message:</th>
						<td><input name="message" value="<?php echo esc_html( $entry->getMessage() ); ?>"></td>
					</tr>
				<?php } else if ( $_GET['form'] == 'parade' ) { ?>
					<?php $entry_types = $entry->getEntryTypeList(); ?>
					<tr>
						<th>Entry Type(s):</th>
						<td>
							<?php foreach ($entry_types as $entry_type) { ?>
								<label>
									<input type="checkbox" name="parade_entry_type[]" value="<?php echo esc_html( $entry_type ); ?>"<?php if ( in_array( $entry_type, $entry->getEntryTypes() ) ) { ?> checked<?php } ?>>
									<?php echo $entry_type; ?>
								</label><br>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<th>Description:</th>
						<td><textarea name="description"><?php echo esc_html( $entry->getDescription() ); ?></textarea></td>
					</tr>
					<tr>
						<th>Float Parking Spaces:</th>
						<td><input name="float_parking_spaces" value="<?php echo $entry->getFloatParkingSpaces(); ?>"></td>
					</tr>
					<tr>
						<th>Cost Per Parking Space:</th>
						<td><input name="float_parking_space_cost" value="$<?php echo number_format( $entry->getFloatParkingSpaceCost(), 2 ); ?>"></td>
					</tr>
					<tr>
						<th>Needs Amped Sound:</th>
						<td>
							<select name="needs_amped_sound">
								<option value="0"<?php if ( ! $entry->needsAmpedSound() ) { ?> selected<?php } ?>>
									No
								</option>
								<option value="1"<?php if ( $entry->needsAmpedSound() ) { ?> selected<?php } ?>>
									Yes
								</option>
							</select>
						</td>
					</tr>
					<tr>
						<th>Group Size:</th>
						<td><input name="group_size" value="<?php echo $entry->getGroupSize(); ?>"></td>
					</tr>
				<?php } elseif ( $_GET['form'] == 'murder_mystery' ) { ?>
					<tr>
						<th>Type:</th>
						<td>
							<select name="is_sponsor">
								<option value="0"<?php if ( ! $entry->isSponsor() ) { ?> selected<?php } ?>>
									Ticket
								</option>
								<option value="1"<?php if ( $entry->isSponsor() ) { ?> selected<?php } ?>>
									Table
								</option>
							</select>
						</td>
					</tr>
					<tr>
						<th>Price Per Ticket or Table:</th>
						<td><input name="price_per_qty" value="$<?php echo number_format( $entry->getPricePerQty(), 2 ); ?>"></td>
					</tr>
					<tr>
						<th>Upgraded Meal:</th>
						<td>
							<select name="is_upgraded">
								<option value="0"<?php if ( ! $entry->isUpgraded() ) { ?> selected<?php } ?>>
									No
								</option>
								<option value="1"<?php if ( $entry->isUpgraded() ) { ?> selected<?php } ?>>
									Yes
								</option>
							</select>
						</td>
					</tr>
					<tr>
						<th>Vegetarian Qty:</th>
						<td><input name="vegetarian_qty" value="<?php echo $entry->getVegetarianQty(); ?>"></td>
					</tr>
				<?php } else if ( $_GET['form'] == 'sponsorship' ) { ?>
					<tr>
						<th>Position:</th>
						<td><input name="position" value="<?php echo $entry->getPosition(); ?>"></td>
					</tr>
					<tr>
						<th>Local First Name:</th>
						<td><input name="local_first_name" value="<?php echo $entry->getLocalFirstName(); ?>"></td>
					</tr>
					<tr>
						<th>Local Last Name:</th>
						<td><input name="local_last_name" value="<?php echo $entry->getLocalLastName(); ?>"></td>
					</tr>
					<tr>
						<th>Local Email:</th>
						<td><input name="local_email" value="<?php echo $entry->getLocalEmail(); ?>"></td>
					</tr>
					<tr>
						<th>Local Phone:</th>
						<td><input name="local_phone" value="<?php echo $entry->getLocalPhone(); ?>"></td>
					</tr>
					<tr>
						<th>Local Address:</th>
						<td><input name="local_address" value="<?php echo $entry->getLocalAddress(); ?>"></td>
					</tr>
					<tr>
						<th>Local City:</th>
						<td><input name="local_city" value="<?php echo $entry->getLocalCity(); ?>"></td>
					</tr>
					<tr>
						<th>Local State:</th>
						<td><input name="local_state" value="<?php echo $entry->getLocalState(); ?>"></td>
					</tr>
					<tr>
						<th>Local Zip:</th>
						<td><input name="local_zip" value="<?php echo $entry->getLocalZip(); ?>"></td>
					</tr>
					<tr>
						<th>Local Position:</th>
						<td><input name="local_position" value="<?php echo $entry->getLocalPosition(); ?>"></td>
					</tr>
					<tr>
						<th>URL:</th>
						<td><input name="url" value="<?php echo $entry->getUrl(); ?>"></td>
					</tr>
					<tr>
						<th>Sponsorship Level:</th>
						<td><input name="level" value="<?php echo $entry->getLevel(); ?>"></td>
					</tr>
					<tr>
						<th>Sponsorship Amount:</th>
						<td><input name="amount" value="$<?php echo number_format( $entry->getAmount(), 2 ); ?>"></td>
					</tr>
				<?php } ?>
				<tr>
					<th></th>
					<td>
						<?php submit_button( 'Update' ); ?>
					</td>
				</tr>
			</table>

		</form>

	<?php } ?>

</div>
