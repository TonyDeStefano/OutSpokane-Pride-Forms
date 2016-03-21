<?php
/**
 * Created by PhpStorm.
 * User: Tony DeStefano
 * Date: 2/2/16
 * Time: 2:30 PM
 */

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

		<?php $entry = new \OutSpokane\MurderMysteryEntry( (isset($_GET['id'])) ? $_GET['id'] : 0 ); ?>

		<?php if ( $entry->getCreatedAt() === NULL ) { ?>

			<div class="alert alert-danger">
				Entry Not Found
			</div>

		<?php } else { ?>

			<h1>
				View Murder Mystery Entry
				<a href="?page=<?php echo $_REQUEST['page']; ?>" class="page-title-action">
					Back
				</a>
				<a href="?page=outspokane_edit_entry&form=murder_mystery&id=<?php echo $entry->getId(); ?>" class="page-title-action">
					Edit
				</a>
				<a href="#" class="page-title-action" id="pride-delete-entry" data-form="murder_mystery" data-id="<?php echo $entry->getId(); ?>">
					Delete
				</a>
			</h1>

			<div class="row">
				<div class="col-md-7">

					<div class="well" id="pride-forms-update-details" data-form="murder_mystery" data-id="<?php echo $entry->getId(); ?>">

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
										Table Sponsorship (8 Tickets)
									<?php } ?>
								</td>
							</tr>
							<tr>
								<th>Meal Type:</th>
								<td><?php echo ( $entry->isUpgraded() ) ? 'Prime Rib Dinner' : 'Turkey or Vegetarian Dinner'; ?></td>
							</tr>
							<?php if ( ! $entry->isUpgraded() ) { ?>
								<tr>
									<th>Vegetarian Meals:</th>
									<td>
										<select name="vegetarian_qty" id="vegetarian_qty">
											<?php for ( $q=0; $q<=( ( $entry->isSponsor() ) ? 8 : $entry->getQty() ); $q++ ) { ?>
												<option value="<?php echo $q; ?>"<?php if ( $q == $entry->getVegetarianQty() ) { ?> selected<?php } ?>>
													<?php echo $q; ?>
												</option>
											<?php } ?>
										</select>
									</td>
								</tr>
							<?php } else { ?>
								<input type="hidden" name="vegetarian_qty" id="vegetarian_qty" value="<?php echo $entry->getVegetarianQty(); ?>">
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
							<tr>
								<th>Tickets Sent:</th>
								<td>
									<select name="tickets_sent" id="tickets_sent">
										<option value="0">
											No
										</option>
										<option value="1"<?php if ( $entry->wereTicketsSent() ) { ?> selected<?php } ?>>
											Yes
										</option>
									</select>
								</td>
							</tr>
							<tr>
								<th></th>
								<td>
									<?php submit_button( 'Save' ); ?>
								</td>
							</tr>
						</table>

					</div>

					<?php

					$receipt_link = '';
					/** @var WP_Post[] $pages */
					$pages = get_pages();
					foreach ($pages as $page)
					{
						if ( preg_match('/\[pride_forms[^\]]*form=["\']murder_mystery["\'][^\]]*\]/', $page->post_content) === 1)
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

					<div class="well" id="pride-forms-update-entry-notes" data-form="murder_mystery" data-id="<?php echo $entry->getId(); ?>">

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

					<div class="well" id="pride-forms-mark-as-paid" data-form="murder_mystery" data-id="<?php echo $entry->getId(); ?>">

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
			Murder Mystery Entries
		</h1>

		<?php

		\OutSpokane\Entry::drawExportForm( \OutSpokane\MurderMysteryEntry::TABLE_NAME );
		$table = new \OutSpokane\EntryTable( \OutSpokane\MurderMysteryEntry::TABLE_NAME );
		$table->prepare_items();
		$table->display();

		?>

	<?php } ?>

</div>
