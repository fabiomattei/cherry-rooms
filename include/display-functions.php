<?php

/**
 * This functions check the loaded post, in case a shortcode is present id loads
 * necessary css in order to show the gallery
 */
function RCSL_Cherry_Room_ShortCode_Detect() {
    global $wp_query;
    $Posts = $wp_query->posts;
    $Pattern = get_shortcode_regex();
    foreach ($Posts as $Post) {
		if ( strpos($Post->post_content, 'RCRoomListHome' ) ) {
			// loading css scripts
			wp_enqueue_style('rcro-css', RCRO_PLUGIN_URL.'css/rcbackbone.css');
        } //end of if
		
		if ( strpos($Post->post_content, 'RCRoomListHome' ) ) {
			// loading js scripts
			wp_enqueue_style('rcro-jquerycss', RCRO_PLUGIN_URL.'lib/jquery-ui/jquery-ui.css');
			wp_enqueue_script('rcro-jqueryui-javascript', RCRO_PLUGIN_URL.'lib/jquery-ui/jquery-ui.min.js', array('jquery'), '', true);
			wp_enqueue_script('rcro-datepicker-javascript', RCRO_PLUGIN_URL.'js/rooms.js', '', '', true);
			
			
        } //end of if
		
    } //end of foreach
}
add_action( 'wp', 'RCSL_Cherry_Room_ShortCode_Detect' );
