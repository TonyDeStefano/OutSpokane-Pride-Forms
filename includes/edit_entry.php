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