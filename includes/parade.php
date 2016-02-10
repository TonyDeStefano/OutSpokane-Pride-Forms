<?php if ( isset( $_GET['txid'] ) ) { ?>

	<?php

	$entry = new \OutSpokane\ParadeEntry;
	$timestamp = '';

	$parts = explode( '-', $_GET['txid'] );
	if ( count( $parts ) == 2 )
	{
		if ( is_numeric( $parts[1] ) )
		{
			$timestamp = $parts[0];
			$entry = new \OutSpokane\ParadeEntry( $parts[1] );
		}
	}

	$stripe_keys = \OutSpokane\Entry::getStripeKeys();

	?>

	<a name="confirmation-payment"></a>
	<h2>Confirmation and Payment</h2>

	<?php if ( isset( $_POST['form'] ) ) { ?>
		<div class="alert alert-danger">
			There was a problem processing your credit card. Please try again.
		</div>
	<?php } ?>

	<?php if ( $entry->getId() !== NULL && $entry->getCreatedAt() == $timestamp ) { ?>

		<?php if ( $entry->getPaidAt() !== NULL && $entry->getAmountDue() == 0 ) { ?>
			<div class="alert alert-success">
				Thank you for your payment!
			</div>
		<?php } ?>

		<table class="table">
			<tr>
				<th>Order Date:</th>
				<td><?php echo $entry->getCreatedAt( 'n/j/Y' ); ?></td>
			</tr>
			<tr>
				<th>Event:</th>
				<td><?php echo $entry->getEntryYear(); ?> Pride Parade</td>
			</tr>
			<?php if ( strlen( $entry->getOrganization() ) > 0 ) { ?>
				<tr>
					<th>Organization:</th>
					<td><?php echo $entry->getOrganization(); ?></td>
				</tr>
			<?php } ?>
			<tr>
				<th>Contact:</th>
				<td>
					<?php echo $entry->getName(); ?><br>
					<?php echo $entry->getPhone(); ?><br>
					<?php echo $entry->getEmail(); ?>
				</td>
			</tr>
			<tr>
				<th>Address:</th>
				<td>
					<?php echo $entry->getAddress(); ?><br>
					<?php echo $entry->getCSZ(); ?>
				</td>
			</tr>
			<tr>
				<th>Entry Type:</th>
				<td>
					<?php foreach ($entry->getEntryTypes() as $entry_type) { ?>
						&raquo; <?php echo $entry_type; ?><br>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<th>Description:</th>
				<td><?php echo $entry->getDescription(); ?></td>
			</tr>
			<tr>
				<th>Group Size:</th>
				<td><?php echo $entry->getGroupSize(); ?></td>
			</tr>
			<?php if ($entry->getFloatParkingSpaces() > 0) { ?>
				<tr>
					<th>Float Parking Spots:</th>
					<td>
						<?php echo $entry->getFloatParkingSpaces(); ?>
						($<?php echo number_format( $entry->getFloatParkingSpaces() * $entry->getFloatParkingSpaceCost(), 2 ); ?>)
					</td>
				</tr>
			<?php } ?>
			<tr>
				<th>Needs Amped Sound:</th>
				<td><?php echo ($entry->needsAmpedSound()) ? 'Yes' : 'No'; ?></td>
			</tr>
			<?php if ($entry->getDonationAmount() > 0) { ?>
				<tr>
					<th>Donation Amount</th>
					<td>$<?php echo number_format( $entry->getDonationAmount(), 2 ); ?> (Thanks!)</td>
				</tr>
			<?php } ?>

			<?php if ( $entry->getAmountDue() > 0 ) { ?>
				<tr>
					<th>Amount Due:</th>
					<td>$<?php echo number_format( $entry->getAmountDue(), 2 ); ?></td>
				</tr>
				<tr>
					<th></th>
					<td>

						<p>
							<strong>
								Payment Options
							</strong>
						</p>

						<?php foreach ($entry->getPaymentMethods() as $payment_method_id => $payment_method) { ?>
							<?php if ($payment_method_id == \OutSpokane\Entry::PAYMENT_METHOD_CARD && strlen($stripe_keys['pub']) > 0 && strlen($stripe_keys['secret']) > 0) { ?>

								<?php \Stripe\Stripe::setApiKey( $stripe_keys['secret'] ); ?>

								<p>
								<form method="post">
									<?php wp_nonce_field('pride-nonce'); ?>
									<input type="hidden" name="pride_action" value="cc">
									<input type="hidden" name="form" value="parade">
									<input type="hidden" name="txid" value="<?php echo $_GET['txid']; ?>">
									<script
										src="https://checkout.stripe.com/checkout.js"
										class="stripe-button"
										data-name="OutSpokane"
										data-image="<?php echo plugin_dir_url( __DIR__ ); ?>images/rainbow-flag.png"
										data-allow-remember-me="false"
										data-email="<?php echo $entry->getEmail(); ?>"
										data-key="<?php echo $stripe_keys['pub']; ?>"
										data-description="<?php echo $entry->getEntryYear(); ?> Pride Parade"
										data-amount="<?php echo round($entry->getAmountDue() * 100); ?>"
										data-locale="auto"></script>
								</form>
								</p>

							<?php } elseif ( $payment_method_id != \OutSpokane\Entry::PAYMENT_METHOD_CARD ) { ?>

								<p>
									Send <?php echo $payment_method; ?> to:<br><br>
									OutSpokane<br>
									PO Box 883<br>
									Spokane, WA 99201<br><br>
								</p>

							<?php } ?>
						<?php } ?>

					</td>
				</tr>
			<?php }  ?>
			<?php if ( $entry->getPaymentAmount() > 0 ) { ?>
				<tr>
					<th>Amount Paid:</th>
					<td>$<?php echo number_format( $entry->getPaymentAmount(), 2 ); ?></td>
				</tr>
				<tr>
					<th>Payment Method:</th>
					<td>
						<?php echo $entry->getPaymentMethod( $entry->getPaymentMethodId() ); ?>
						on
						<?php echo $entry->getPaidAt( 'n/j/Y' ); ?>
					</td>
				</tr>
			<?php } ?>
		</table>

	<?php } else { ?>

		<div class="alert alert-danger">
			The confirmation you are looking for is currently unavailable.
		</div>

	<?php } ?>

<?php } else { ?>

	<div
		id="pride-form-container"
		data-form="<?php echo $this->getAttribute('form'); ?>"
		data-year="<?php echo $this->getAttribute('year'); ?>"
		class="<?php echo $this->getAttribute('form'); ?>">

		<div id="pride-form-step-1">

			<?php

			\OutSpokane\Entry::drawDefaultFormFields();
			$parade = new \OutSpokane\ParadeEntry;
			$entry_types = $parade->getEntryTypeList();

			?>

			<div class="row">
				<div class="col-md-3">
					<label>Entry Type(s)</label>
				</div>
				<div class="col-md-6">
					<?php foreach ($entry_types as $entry_type) { ?>
						<label>
							<input type="checkbox" class="parade_entry_type" value="<?php echo esc_html( $entry_type ); ?>">
							<?php echo $entry_type; ?>
						</label><br>
					<?php } ?>
				</div>
			</div>

			<?php

			\OutSpokane\Entry::drawFormField( 'Short Description', 'description', 'textarea' );
			\OutSpokane\Entry::drawFormField(
				'Do you need float parking for after the parade?',
				'float_parking_spaces',
				'select',
				array(
					0 => 'No',
					1 => '1 Space ($' . number_format( \OutSpokane\ParadeEntry::FLOAT_PARKING_SPACE_COST, 2 ) . ')',
					2 => '2 Spaces ($' . number_format( \OutSpokane\ParadeEntry::FLOAT_PARKING_SPACE_COST * 2, 2 ) . ')'
				)
			);
			\OutSpokane\Entry::drawFormField(
				'Need Amplified Sound?',
				'needs_amped_sound',
				'select',
				array(
					0 => 'No',
					1 => 'Yes'
				)
			);
			\OutSpokane\Entry::drawFormField( 'Group Size' );
			\OutSpokane\Entry::drawFormField(
				'If you would like to make a donation to OutSpokane, please enter the amount here',
				'donation_amount'
			);

			?>

			<div class="row">
				<div class="col-md-6 col-md-offset-3">

					<button id="btn-step-1">Submit</button>

				</div>
			</div>

		</div>

	</div>

<?php } ?>