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
			Edit Festival Entry
			<a href="?page=<?php echo $_REQUEST['page']; ?>" class="page-title-action">
				Cancel
			</a>
		</h1>

	<?php } else { ?>

		<h1>
			Festival Entries
		</h1>

		<?php

		$table = new \OutSpokane\EntryTable( \OutSpokane\FestivalEntry::TABLE_NAME );
		$table->prepare_items();
		$table->display();

		?>

	<?php } ?>

</div>
