<?php if ( isset( $_GET['txid'] ) ) { ?>

	<?php

	$entry = new \OutSpokane\FlagHandle;
	$timestamp = '';

	$parts = explode( '-', $_GET['txid'] );
	if ( count( $parts ) == 2 )
	{
		if ( is_numeric( $parts[1] ) )
		{
			$timestamp = $parts[0];
			$entry = new \OutSpokane\FlagHandle( $parts[1] );
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
				<th>Item:</th>
				<td>Flag Handle</td>
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
				<th>Message:</th>
				<td><?php echo $entry->getMessage(); ?></td>
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
										<input type="hidden" name="form" value="flag">
										<input type="hidden" name="txid" value="<?php echo $_GET['txid']; ?>">
										<script
											src="https://checkout.stripe.com/checkout.js"
											class="stripe-button"
											data-name="OutSpokane"
											data-image="<?php echo plugin_dir_url( __DIR__ ); ?>images/rainbow-flag.png"
											data-allow-remember-me="false"
											data-email="<?php echo $entry->getEmail(); ?>"
									        data-key="<?php echo $stripe_keys['pub']; ?>"
									        data-description="<?php echo $entry->getEntryYear(); ?> Flag Handle"
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

	<?php $disable_cruise_form = get_option( 'pride_forms_disable_flag_form', 'N' ); ?>

	<?php if ( $disable_cruise_form == 'Y' ) { ?>

		<div class="alert alert-info">
			Flag handles are no longer available for purchase.
		</div>

	<?php } else { ?>

		<div
			id="pride-form-container"
			data-form="<?php echo $this->getAttribute('form'); ?>"
			data-year="<?php echo $this->getAttribute('year'); ?>"
			class="<?php echo $this->getAttribute('form'); ?>">

			<div id="pride-form-step-1">

				<div class="row">
					<div class="col-md-3">
						<label for="price">Price</label>
					</div>
					<div class="col-md-6">
						$<?php echo number_format( \OutSpokane\FlagHandle::PRICE_PER_HANDLE, 2 ); ?>
					</div>
				</div>

				<?php \OutSpokane\Entry::drawDefaultFormFields( array( 'organization' ) ); ?>

				<div class="row">
					<div class="col-md-3">
						<label for="message">Embroidered Message</label>
					</div>
					<div class="col-md-6">
						<input class="form-control" name="message" id="message" maxlength="">
					</div>
				</div>

				<div class="row">
					<div class="col-md-6 col-md-offset-3">

						<button id="btn-step-1">Submit</button>

					</div>
				</div>

			</div>

		</div>

	<?php } ?>

<?php } ?>