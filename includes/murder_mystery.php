<?php if ( isset( $_GET['txid'] ) ) { ?>

	<?php

	$entry = new \OutSpokane\MurderMysteryEntry;
	$timestamp = '';

	$parts = explode( '-', $_GET['txid'] );
	if ( count( $parts ) == 2 )
	{
		if ( is_numeric( $parts[1] ) )
		{
			$timestamp = $parts[0];
			$entry = new \OutSpokane\MurderMysteryEntry( $parts[1] );
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
				<td><?php echo $entry->getEntryYear(); ?> Murder Mystery</td>
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
				<th>Tickets:</th>
				<td>
					<?php echo $entry->getQty(); ?>
					<?php if ($entry->isSponsor()) { ?>
						Tickets (Sponsor)
					<?php } ?>
                    <?php if ($entry->isVip()) { ?>
                        VIP Table (8 Tickets)
                    <?php } ?>
				</td>
			</tr>
			<tr>
				<th>Meal Types:</th>
				<td>
                    <?php

                    $meals_types = \OutSpokane\MurderMysteryEntry::getMealTypes();
                    $chosen_meals = $entry->getMeals( TRUE );

                    foreach ( $chosen_meals as $index => $qty )
                    {
                        if ( isset( $meals_types[ $index ] ) )
                        {
                            echo $qty . ' X ' . $meals_types[ $index ]['name'] . '<br>';
                        }
                    }

                    ?>
                </td>
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
									<input type="hidden" name="form" value="murder_mystery">
									<input type="hidden" name="txid" value="<?php echo $_GET['txid']; ?>">
									<script
										src="https://checkout.stripe.com/checkout.js"
										class="stripe-button"
										data-name="OutSpokane"
										data-image="<?php echo plugin_dir_url( __DIR__ ); ?>images/rainbow-flag.png"
										data-allow-remember-me="false"
										data-email="<?php echo $entry->getEmail(); ?>"
										data-key="<?php echo $stripe_keys['pub']; ?>"
										data-description="<?php echo $entry->getEntryYear(); ?> Murder Mystery"
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

	<?php $disable_murder_mystery_form = get_option( 'pride_forms_disable_murder_mystery_form', 'N' ); ?>

	<?php if ( $disable_murder_mystery_form == 'Y' ) { ?>

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

				<?php \OutSpokane\Entry::drawDefaultFormFields(); ?>

                <div id="mm-sponsor-question">

                    <div class="row">
                        <div class="col-md-12">
                            <label for="mm_is_sponsor">Are you an event sponsor (Includes 10 Tickets)?</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <select id="mm_is_sponsor">
                                <option value="0">No - I am not an event sponsor</option>
                                <option value="1">Yes - I am an event sponsor</option>
                            </select>
                        </div>
                    </div>

                </div>

                <div id="mm-vip-question">

                    <div class="row">
                        <div class="col-md-12">
                            <label for="mm_is_vip">We have VIP tables available for $500!</label>
                            <p>Includes:</p>
                            <ul style="margin-left:50px">
                                <li>8 Tickets</li>
                                <li>$40 of Bribe Bucks</li>
                                <li>2 Cruise Tickets</li>
                                <li>1 Package of Mystery Evidence</li>
                                <li>16 Drink Tickets (2 Per Person)</li>
                                <li>1 Free Prom Picture</li>
                                <li>Priority Access To The Buffet</li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <select id="mm_is_vip">
                                <option value="0">No - I do not need a VIP table</option>
                                <option value="1">Yes - I would like a VIP table</option>
                            </select>
                        </div>
                    </div>

                </div>

                <div id="mm-qty-question">

                    <div class="row">
                        <div class="col-md-12">
                            <label for="ticket_qty">How many total tickets would you like to purchase?</label>
                        </div>
                        <div class="col-md-12">
                            <select id="ticket_qty">
                                <?php for ($x=1; $x<=\OutSpokane\MurderMysteryEntry::MAX_TICKETS; $x++) { ?>
                                    <option value="<?php echo $x; ?>">
                                        <?php echo $x; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label for="ticket_qty">Please indicate the number of each meal type you would like:</label>
                    </div>
                    <div class="col-md-12">
                        <table>
                            <?php foreach ( \OutSpokane\MurderMysteryEntry::getMealTypes() as $index => $meal ) { ?>
                                <?php if ( $meal['active'] == 1 ) { ?>
                                    <tr>
                                        <td>
                                            <input style="width:50px;" value="0" class="meal_qty" data-index="<?php echo $index; ?>" name="meal_qty_<?php echo $index; ?>">
                                        </td>
                                        <td style="width:80%"><?php echo $meal['name']; ?></td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </table>
                    </div>
                </div>

				<div class="row">
					<div class="col-md-12">

						<button id="btn-step-1">Submit</button>

					</div>
				</div>

			</div>

		</div>

	<?php } ?>

<?php } ?>