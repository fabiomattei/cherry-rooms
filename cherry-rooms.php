<?php

/*
Plugin Name: Cherry Rooms
Plugin URI: http://www.redcherries.net/cherry-rooms
Description: TODO
Version: 1.0.0
Author: Red Cherries
Author URI: http://www.redcherries.net/
License: GPLv2
*/

/*******************************************
* Plugin CONSTANT
********************************************/
define( 'RCEV_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'RCEV_PLUGIN_URL' , plugin_dir_url( __FILE__ ) );
define( 'RCEV_TEXT_DOMAIN', 'cherry-events' );
define( 'RCEV_SLUG',        'cherry-events' );

define( 'RCEV_SETTINGS_KEY', 'RCEV_Gallery_Settings_');

/*******************************************
* Global Variables
* variables and costants that are used 
* in this plug in
********************************************/



/*******************************************
* Includes
********************************************/

if ( is_admin() ) {
	// include admin side
	include( 'include/installer.php' );
	include( 'include/register-posttype.php' );

} else {
	// include for client side
	include( 'include/display-shortcode.php');
	
}
