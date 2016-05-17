<?php

/**
 * Create the necessary default settings 
 */
function rcrm_items_create_settings() {
	
    $default_settings_array = array(
		"speed"   		=> 3,
		"transition"   	=> 1,
		"easing"   		=> 1,
		"width"   	=> 1000,
		"height"   	=> 500,
    );
    add_option( 'rc_rm_options', $default_settings_array );
	
}

register_activation_hook( RCRO_PLUGIN_MAIN_FILE, 'rcrm_items_create_settings' );
