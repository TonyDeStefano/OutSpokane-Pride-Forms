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
		case 'edit':
			$action = $_GET[ 'action' ];
	}
}

?>

<div class="wrap">

	<?php if ( $action == 'edit' ) { ?>

		<h1>
			Edit Parade Entry
			<a href="?page=<?php echo $_REQUEST['page']; ?>" class="page-title-action">
				Cancel
			</a>
		</h1>

	<?php } else { ?>

		<h1>
			Parade Entries
		</h1>

		<?php

		$table = new \OutSpokane\EntryTable( \OutSpokane\ParadeEntry::TABLE_NAME );
		$table->prepare_items();
		$table->display();

		?>

	<?php } ?>

</div>