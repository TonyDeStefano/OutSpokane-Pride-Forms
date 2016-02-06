<div class="wrap">

	<h1>OutSpokane Form Settings</h1>
	<p>
		In order to accept credit card payments on the website, you must fill in the following information from your Stripe account:
	</p>

	<form method="post" action="options.php">

		<?php

		settings_fields('outspokane_settings');
		do_settings_sections( 'outspokane_settings' );

		?>

		<table class="form-table">
			<thead>
				<tr>
					<th></th>
					<th>Current Value</th>
					<th>Change To</th>
				</tr>
			</thead>
			<tr valign="top">
				<th scope="row">
					<label for="pride_forms_stripe_test_secret_key">
						Stripe Test Secret Key
					</label>
				</th>
				<td><?php echo esc_attr( get_option('pride_forms_stripe_test_secret_key') ); ?></td>
				<td><input type="text" id="pride_forms_stripe_test_secret_key" name="pride_forms_stripe_test_secret_key" value="<?php echo esc_attr( get_option('pride_forms_stripe_test_secret_key') ); ?>" /></td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="pride_forms_stripe_test_pub_key">
						Stripe Test Publishable Key
					</label>
				</th>
				<td><?php echo esc_attr( get_option('pride_forms_stripe_test_pub_key') ); ?></td>
				<td><input type="text" id="pride_forms_stripe_test_pub_key" name="pride_forms_stripe_test_pub_key" value="<?php echo esc_attr( get_option('pride_forms_stripe_test_pub_key') ); ?>" /></td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="pride_forms_stripe_live_secret_key">
						Stripe Live Secret Key
					</label>
				</th>
				<td><?php echo esc_attr( get_option('pride_forms_stripe_live_secret_key') ); ?></td>
				<td><input type="text" id="pride_forms_stripe_live_secret_key" name="pride_forms_stripe_live_secret_key" value="<?php echo esc_attr( get_option('pride_forms_stripe_live_secret_key') ); ?>" /></td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="pride_forms_stripe_live_pub_key">
						Stripe Live Publishable Key
					</label>
				</th>
				<td><?php echo esc_attr( get_option('pride_forms_stripe_live_pub_key') ); ?></td>
				<td><input type="text" id="pride_forms_stripe_live_pub_key" name="pride_forms_stripe_live_pub_key" value="<?php echo esc_attr( get_option('pride_forms_stripe_live_pub_key') ); ?>" /></td>
			</tr>
		</table>

		<?php submit_button(); ?>

	</form>

</div>