<?php

/**
 * Plugin Name: OutSpokane Pride Forms
 * Plugin URI:
 * Description: Custom plugin for all the OutSpokane forms
 * Author: Tony DeStefano
 * Author URI: https://www.facebook.com/TonyDeStefanoWeb
 * Version: 1.0
 * Text Domain: out-spokane-pride-forms
 *
 * Copyright 2016 Tony DeStefano
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @package OutSpokanePrideForms
 * @author Tony DeStefano
 * @version 1.0.0
 */

require_once ( 'classes/Controller.php' );
require_once ( 'classes/Entry.php' );
require_once ( 'classes/CruiseEntry.php' );
require_once ( 'classes/FestivalEntry.php' );
require_once ( 'classes/ParadeEntry.php' );
require_once ( 'classes/MurderMysteryEntry.php' );

/* controller object  */
$controller = new \OutSpokane\Controller;

/* activate */
register_activation_hook( __FILE__, array( $controller, 'activate' ) );