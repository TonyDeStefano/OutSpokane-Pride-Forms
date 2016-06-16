<?php

$disable_cruise_form = get_option( 'pride_forms_disable_cruise_form', 'N' );
$disable_donation_form = get_option( 'pride_forms_disable_donation_form', 'N' );
$disable_festival_form = get_option( 'pride_forms_disable_festival_form', 'N' );
$disable_murder_mystery_form = get_option( 'pride_forms_disable_murder_mystery_form', 'N' );
$disable_parade_form = get_option( 'pride_forms_disable_parade_form', 'N' );
$disable_flag_form = get_option( 'pride_forms_disable_flag_form', 'N' );

?>

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
			<tr valign="top">
				<th scope="row">
					<label for="pride_forms_stripe_mode">
						Mode
					</label>
				</th>
				<td><?php echo ( get_option('pride_forms_stripe_mode') == 'live') ? 'Live' : 'Test' ?> Mode</td>
				<td>
					<select id="pride_forms_stripe_mode" name="pride_forms_stripe_mode">
						<option value="live"<?php if (get_option('pride_forms_stripe_mode') == 'live') { ?> selected<?php } ?>>
							Live Mode
						</option>
						<option value="test"<?php if (get_option('pride_forms_stripe_mode') != 'live') { ?> selected<?php } ?>>
							Test Mode
						</option>
					</select>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">
					<label for="pride_forms_disable_cruise_form">
						Disable Cruise Form
					</label>
				</th>
				<td><?php echo ( $disable_cruise_form == 'N' ) ? 'No' : '<strong style="color:red">Yes</strong>' ?></td>
				<td>
					<select id="pride_forms_disable_cruise_form" name="pride_forms_disable_cruise_form">
						<option value="N"<?php if ( $disable_cruise_form == 'N') { ?> selected<?php } ?>>
							No
						</option>
						<option value="Y"<?php if ( $disable_cruise_form == 'Y') { ?> selected<?php } ?>>
							Yes
						</option>
					</select>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">
					<label for="pride_forms_disable_donation_form">
						Disable Donation Form
					</label>
				</th>
				<td><?php echo ( $disable_donation_form == 'N' ) ? 'No' : '<strong style="color:red">Yes</strong>' ?></td>
				<td>
					<select id="pride_forms_disable_donation_form" name="pride_forms_disable_donation_form">
						<option value="N"<?php if ( $disable_donation_form == 'N') { ?> selected<?php } ?>>
							No
						</option>
						<option value="Y"<?php if ( $disable_donation_form == 'Y') { ?> selected<?php } ?>>
							Yes
						</option>
					</select>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">
					<label for="pride_forms_disable_festival_form">
						Disable Festival Form
					</label>
				</th>
				<td><?php echo ( $disable_festival_form == 'N' ) ? 'No' : '<strong style="color:red">Yes</strong>' ?></td>
				<td>
					<select id="pride_forms_disable_festival_form" name="pride_forms_disable_festival_form">
						<option value="N"<?php if ( $disable_festival_form == 'N') { ?> selected<?php } ?>>
							No
						</option>
						<option value="Y"<?php if ( $disable_festival_form == 'Y') { ?> selected<?php } ?>>
							Yes
						</option>
					</select>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">
					<label for="pride_forms_disable_murder_mystery_form">
						Disable Murder Mystery Form
					</label>
				</th>
				<td><?php echo ( $disable_murder_mystery_form == 'N' ) ? 'No' : '<strong style="color:red">Yes</strong>' ?></td>
				<td>
					<select id="pride_forms_disable_murder_mystery_form" name="pride_forms_disable_murder_mystery_form">
						<option value="N"<?php if ( $disable_murder_mystery_form == 'N') { ?> selected<?php } ?>>
							No
						</option>
						<option value="Y"<?php if ( $disable_murder_mystery_form == 'Y') { ?> selected<?php } ?>>
							Yes
						</option>
					</select>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">
					<label for="pride_forms_disable_parade_form">
						Disable Parade Form
					</label>
				</th>
				<td><?php echo ( $disable_parade_form == 'N' ) ? 'No' : '<strong style="color:red">Yes</strong>' ?></td>
				<td>
					<select id="pride_forms_disable_parade_form" name="pride_forms_disable_parade_form">
						<option value="N"<?php if ( $disable_parade_form == 'N') { ?> selected<?php } ?>>
							No
						</option>
						<option value="Y"<?php if ( $disable_parade_form == 'Y') { ?> selected<?php } ?>>
							Yes
						</option>
					</select>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">
					<label for="pride_forms_disable_flag_form">
						Disable Flag Handle Form
					</label>
				</th>
				<td><?php echo ( $disable_flag_form == 'N' ) ? 'No' : '<strong style="color:red">Yes</strong>' ?></td>
				<td>
					<select id="pride_forms_disable_flag_form" name="pride_forms_disable_flag_form">
						<option value="N"<?php if ( $disable_flag_form == 'N') { ?> selected<?php } ?>>
							No
						</option>
						<option value="Y"<?php if ( $disable_flag_form == 'Y') { ?> selected<?php } ?>>
							Yes
						</option>
					</select>
				</td>
			</tr>

		</table>

		<?php submit_button(); ?>

	</form>

</div>