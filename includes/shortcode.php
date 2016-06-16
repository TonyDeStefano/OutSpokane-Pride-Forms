<div class="wrap">

	<h1>Short Code</h1>
	<p>Insert this short code on any page to show the associated registration form:</p>

	[pride_forms year="<?php echo date('Y'); ?>" form="[form_name]"]

	<p>Replace [form_name] above with one of the following:</p>
	<blockquote>
		<ul>
			<li>parade</li>
			<li>festival</li>
			<li>cruise</li>
			<li>murder_mystery</li>
			<li>donation</li>
			<li>flag</li>
		</ul>
	</blockquote>

	<h3>Example:</h3>

	[pride_forms year="<?php echo date('Y'); ?>" form="parade"]

	<h3>Special Codes</h3>

	<p>For the Festival Entry tag, you can turn off the option to let people choose a Corner Booth by doing this:</p>

	[pride_forms year="<?php echo date('Y'); ?>" form="parade" corner_booth="no"]

</div>