<?php if ( isset( $_GET['txid'] ) ) { ?>

	<?php

	$entry = new \OutSpokane\FestivalEntry;
	$timestamp = '';

	$parts = explode( '-', $_GET['txid'] );
	if ( count( $parts ) == 2 )
	{
		if ( is_numeric( $parts[1] ) )
		{
			$timestamp = $parts[0];
			$entry = new \OutSpokane\FestivalEntry( $parts[1] );
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
				<td><?php echo $entry->getEntryYear(); ?> Pride Festival</td>
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
				<td><?php echo $entry->getEntryType(); ?></td>
			</tr>
			<tr>
				<th>Corner Booth:</th>
				<td><?php echo ( $entry->isCornerBooth() ) ? 'Yes' : 'No'; ?></td>
			</tr>
			<tr>
				<th>Comments/Notes:</th>
				<td><?php echo $entry->getDescription(); ?></td>
			</tr>
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
									<input type="hidden" name="form" value="festival">
									<input type="hidden" name="txid" value="<?php echo $_GET['txid']; ?>">
									<script
										src="https://checkout.stripe.com/checkout.js"
										class="stripe-button"
										data-name="OutSpokane"
										data-image="<?php echo plugin_dir_url( __DIR__ ); ?>images/rainbow-flag.png"
										data-allow-remember-me="false"
										data-email="<?php echo $entry->getEmail(); ?>"
										data-key="<?php echo $stripe_keys['pub']; ?>"
										data-description="<?php echo $entry->getEntryYear(); ?> Pride Festival"
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

	<?php $disable_festival_form = get_option( 'pride_forms_disable_festival_form', 'N' ); ?>

	<?php if ( $disable_festival_form == 'Y' ) { ?>

		<div class="alert alert-info">
			Tickets are not currently available.
		</div>

	<?php } else { ?>

		<div
			id="pride-form-container"
			data-form="<?php echo $this->getAttribute('form'); ?>"
			data-year="<?php echo $this->getAttribute('year'); ?>"
			class="<?php echo $this->getAttribute('form'); ?>">

			<div id="pride-form-step-1">

				<?php

				\OutSpokane\Entry::drawDefaultFormFields();
				$festival = new \OutSpokane\FestivalEntry;
				$entry_types = $festival->getEntryTypes();

				?>

				<div class="row">
					<div class="col-md-3">
						<label for="entry_type_id">Entry Type</label>
					</div>
					<div class="col-md-6">
						<select id="entry_type_id" class="form-control" data-sponsor-id="<?php echo \OutSpokane\FestivalEntry::ENTRY_TYPE_SPONSOR; ?>">
							<?php foreach ($entry_types as $entry_type_id => $entry_type) { ?>
								<option value="<?php echo $entry_type_id; ?>">
									<?php echo $entry_type; ?> - $<?php echo number_format( \OutSpokane\FestivalEntry::getEntryTypePrice( $entry_type_id ), 2 ); ?>
								</option>
							<?php } ?>
						</select>
					</div>
				</div>

				<?php if ( strtolower( $this->getAttribute( 'corner_booth' ) ) == 'yes' ) { ?>
					<div class="row" id="corner-booth-container">
						<div class="col-md-3">
							<label for="corner_booth">Upgrade to Corner Booth</label>
						</div>
						<div class="col-md-6">
							<select id="corner_booth" class="form-control">
								<option value="0">
									No
								</option>
								<option value="1">
									Yes - $<?php echo number_format( \OutSpokane\FestivalEntry::CORNER_BOOTH_FEE, 2); ?> Extra
								</option>
							</select>
						</div>
					</div>
				<?php } else { ?>
					<input type="hidden" name="corner_booth" value="0">
				<?php } ?>

				<?php \OutSpokane\Entry::drawFormField( 'Comments/Notes', 'description', 'textarea' ); ?>

				<div class="row">
					<div class="col-md-6 col-md-offset-3">

						<button id="btn-step-1">Submit</button>

					</div>
				</div>

			</div>

		</div>

	<?php } ?>

<?php } ?>