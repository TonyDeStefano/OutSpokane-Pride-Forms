<?php

$action = 'list';
if ( isset( $_GET[ 'action' ] ) )
{
	switch( $_GET[ 'action' ] )
	{
		case 'view':
			$action = $_GET[ 'action' ];
	}
}

?>

<div class="wrap">

	<?php if ( $action == 'view' ) { ?>

		<?php $entry = new \OutSpokane\Donation( (isset($_GET['id'])) ? $_GET['id'] : 0 ); ?>

		<?php if ( $entry->getCreatedAt() === NULL ) { ?>

			<div class="alert alert-danger">
				Entry Not Found
			</div>

		<?php } else { ?>

			<h1>
				View Donation
				<a href="?page=<?php echo $_REQUEST['page']; ?>" class="page-title-action">
					Back
				</a>
				<a href="?page=outspokane_edit_entry&form=donation&id=<?php echo $entry->getId(); ?>" class="page-title-action">
					Edit
				</a>
				<a href="#" class="page-title-action" id="pride-delete-entry" data-form="donation" data-id="<?php echo $entry->getId(); ?>">
					Delete
				</a>
			</h1>

			<div class="row">
				<div class="col-md-7">

					<div class="well">

						<table class="table">
							<tr>
								<th>Order Date:</th>
								<td><?php echo $entry->getCreatedAt( 'n/j/Y' ); ?></td>
							</tr>
							<tr>
								<th>Title:</th>
								<td><?php echo $entry->getEntryYear(); ?> Donation</td>
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
							<?php if ($entry->getDonationAmount() > 0) { ?>
								<tr>
									<th>Donation Amount</th>
									<td>$<?php echo number_format( $entry->getDonationAmount(), 2 ); ?></td>
								</tr>
							<?php } ?>
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
									<td>
										<?php echo $entry->getPaymentMethod( $entry->getPaymentMethodId() ); ?>
										on
										<?php echo $entry->getPaidAt( 'n/j/Y' ); ?>
									</td>
								</tr>
							<?php } ?>
						</table>

					</div>

					<?php

					$receipt_link = '';
					/** @var WP_Post[] $pages */
					$pages = get_pages();
					foreach ($pages as $page)
					{
						if ( preg_match('/\[pride_forms[^\]]*form=["\']donation["\'][^\]]*\]/', $page->post_content) === 1)
						{
							$receipt_link = get_page_link($page->ID) . '?txid=' . $entry->getCreatedAt() . '-' . $entry->getId() . '#confirmation-payment';
							break;
						}
					}

					?>

					<?php if ( strlen( $receipt_link ) > 0 ) { ?>

						<div class="well">
							<p>
								<strong>Payment / Receipt Link</strong>
							</p>
							<p>
								<a href="<?php echo $receipt_link; ?>" target="_blank"><?php echo $receipt_link; ?></a>
							</p>

						</div>

					<?php } ?>

				</div>
				<div class="col-md-5">

					<div class="well" id="pride-forms-update-entry-notes" data-form="donation" data-id="<?php echo $entry->getId(); ?>">

						<p>
							<label for="entry-notes">
								<strong>
									Notes
								</strong>
							</label>
						</p>
						<p><textarea id="entry-notes" style="width:100%; height:100px"><?php echo esc_html( $entry->getNotes() ); ?></textarea></p>

						<?php submit_button( 'Save Notes' ); ?>

					</div>

					<div class="well" id="pride-forms-mark-as-paid" data-form="donation" data-id="<?php echo $entry->getId(); ?>">

						<p>
							<label for="payment-method-id">
								<strong>
									Mark as Paid / Not Paid
								</strong>
							</label>
						</p>
						<p>
							<select id="payment-method-id">
								<option value="">
									Not Paid
								</option>
								<?php foreach ($entry->getPaymentMethods( TRUE ) as $payment_method_id => $payment_method) { ?>
									<option value="<?php echo $payment_method_id; ?>"<?php if ($payment_method_id == $entry->getPaymentMethodId()) { ?> selected<?php } ?>>
										<?php echo $payment_method; ?>
									</option>
								<?php } ?>
							</select>
						</p>

						<?php submit_button( 'Update' ); ?>

					</div>

				</div>
			</div>

		<?php } ?>

	<?php } else { ?>

		<h1>
			Donations
		</h1>

		<?php

		\OutSpokane\Entry::drawExportForm( \OutSpokane\Donation::TABLE_NAME );
		$table = new \OutSpokane\EntryTable( \OutSpokane\Donation::TABLE_NAME );
		$table->prepare_items();
		$table->display();

		?>

	<?php } ?>

</div>
