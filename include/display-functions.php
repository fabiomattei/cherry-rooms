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
			wp_enqueue_style('rcro-css', RCRO_PLUGIN_URL.'css/rooms.css');

            break;
        } //end of if
    } //end of foreach
}
add_action( 'wp', 'RCSL_Cherry_Room_ShortCode_Detect' );
