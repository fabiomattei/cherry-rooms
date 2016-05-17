<?php

/**
 * Create the necessary default settings 
 */
function rcrm_items_create_settings() {
	
    $DefaultSettingsArray = serialize( array(
		"RCSL_Slide_Title"   		=> 1,
		"RCSL_Auto_Slideshow"   	=> 1,
		"RCSL_Sliding_Arrow"   		=> 1,
		"RCSL_Slider_Navigation"   	=> 1,
		"RCSL_Navigation_Button"   	=> 1,
		"RCSL_Slider_Width"   		=> 1000,
		"RCSL_Slider_Height"   		=> 500
    ));
    add_option("RCSL_Settings", $DefaultSettingsArray);
	
}

register_activation_hook( __FILE__, 'rcrm_items_create_settings' );
