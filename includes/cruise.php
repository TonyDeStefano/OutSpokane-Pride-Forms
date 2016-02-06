<?php if ( isset( $_GET['txid'] ) ) { ?>

	<?php

	$entry = new \OutSpokane\CruiseEntry;
	$timestamp = '';

	$parts = explode( '-', $_GET['txid'] );
	if ( count( $parts ) == 2 )
	{
		if ( is_numeric( $parts[1] ) )
		{
			$timestamp = $parts[0];
			$entry = new \OutSpokane\CruiseEntry( $parts[1] );
		}
	}

	?>

	<a name="confirmation-payment"></a>
	<h2>Confirmation and Payment</h2>

	<?php if ( $entry->getId() !== NULL && $entry->getCreatedAt() == $timestamp ) { ?>

		<table class="table">
			<tr>
				<th>Order Date:</th>
				<td><?php echo $entry->getCreatedAt( 'n/j/Y' ); ?></td>
			</tr>
			<tr>
				<th>Event:</th>
				<td><?php echo $entry->getEntryYear(); ?> Pride Cruise</td>
			</tr>
			<?php if ( strlen( $entry->getOrganization() ) > 0 ) { ?>
				<tr>
					<th>Organization:</th>
					<td><?php echo $entry->getOrganization(); ?></td>
				</tr>
			<?php } ?>
			<tr>
				<th>Name:</th>
				<td><?php echo $entry->getName(); ?></td>
			</tr>
			<tr>
				<th>Tickets:</th>
				<td><?php echo $entry->getQty(); ?></td>
			</tr>
			<?php if ( $entry->getAmountDue() > 0 ) { ?>
				<tr>
					<th>Amount Due:</th>
					<td>$<?php echo number_format( $entry->getAmountDue(), 2 ); ?></td>
				</tr>
			<?php }  ?>
			<?php if ( $entry->getPaymentAmount() > 0 ) { ?>
				<tr>
					<th>Amount Paid:</th>
					<td>$<?php echo number_format( $entry->getPaymentAmount(), 2 ); ?></td>
				</tr>
				<tr>
					<th>Payment Method:</th>
					<td><?php echo $entry->getPaymentMethod( $entry->getPaymentMethodId() ); ?></td>
				</tr>
			<?php } ?>
		</table>

	<?php } else { ?>

		<p>The confirmation you are looking for is currently unavailable.</p>

	<?php } ?>

<?php } else { ?>

	<div
		id="pride-form-container"
		data-form="<?php echo $this->getAttribute('form'); ?>"
		data-year="<?php echo $this->getAttribute('year'); ?>"
		class="<?php echo $this->getAttribute('form'); ?>">

		<div id="pride-form-step-1">

			<?php \OutSpokane\Entry::drawDefaultFormFields( array( 'organization' ) ); ?>

			<div class="row">
				<div class="col-md-3">
					<label for="qty">How Many Tickets?</label>
				</div>
				<div class="col-md-6">
					<select id="qty" class="form-control">
						<?php for ($x=1; $x<=\OutSpokane\CruiseEntry::MAX_TICKETS; $x++) { ?>
							<option value="<?php echo $x; ?>">
								<?php echo $x; ?> - $<?php echo number_format(\OutSpokane\CruiseEntry::PRICE_PER_TICKET*$x, 2); ?>
							</option>
						<?php } ?>
					</select>
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