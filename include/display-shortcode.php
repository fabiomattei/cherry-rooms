<?php

/**
 * This function handle the short code
 */
function rcnw_room_list_home( $atts, $content ) {
	global $post;
	
	$atts = array( // a few default values
			'posts_per_page' => '3',
			'post_type' => 'rcnwroom' // post type
			);
			
	$posts = new WP_Query( $atts );
	$out = '<div class="roomboxcontainer">
				<div class="roomtitlewrapper">
					<h4 class="roomtitle">Our rooms</h4>
				</div>
					<div class="roombox">';
	
	if ($posts->have_posts()) {
		
	    while ($posts->have_posts()) {
	        $posts->the_post();
			
	        $out .= '<div class="singleroombox">
				<p class="roomboxthumbnail">'.get_the_post_thumbnail().'</p>
	            <h5><a href="'.get_permalink().'" title="' . get_the_title() . '">'.get_the_title() .'</a></h5>
	            <p class="room_desc">'.get_the_content().'</p>';
	            // add here more...
	        $out .= '</div>';
			
	/* these arguments will be available from inside $content
	    get_permalink()  
	    get_the_content()
	    get_the_category_list(', ')
	    get_the_title()
	    and custom fields
	    get_post_meta($post->ID, 'field_name', true);
	*/
	
		} // end while loop
		
	} else {
		return; // no posts found
	}
	$out .= '</div>'; // ending roombox
	$out .= '</div>'; // ending roomboxcontainer
	
	ob_start();

	echo $out;
	
    return ob_get_clean();
}
add_shortcode( 'RCRoomListHome', 'rcnw_room_list_home' );


/**
 * This function handle the short code
 */
function html_form_code() {
	global $post;
	
	$atts = array( // a few default values
			'posts_per_page' => '3',
			'post_type' => 'rcnwroom' // post type
			);
			
	$posts = new WP_Query( $atts );
	$out = '
		<div class="roomformboxcontainer">
				<h4>Book a room</h4>
					<div class="roombox">
					<input type="text" id="datepicker">
					<input type="text" id="datepicker2">
					<select>';
	
	if ($posts->have_posts()) {
		
	    while ($posts->have_posts()) {
	        $posts->the_post();
			
	        $out .= '<option value="'.get_the_title() .'">'.get_the_title() .'</option>';

		} // end while loop
		
	} else {
		return; // no posts found
	}
	$out .= '</select>';
	$out .= '<select>';
	$out .= '<option value="0">Adults</option>';
	$out .= '<option value="1">1</option>';
	$out .= '<option value="2">2</option>';
	$out .= '<option value="3">3</option>';
	$out .= '<option value="4">4</option>';
	$out .= '</select>';
	$out .= '<select>';
	$out .= '<option value="0">Children</option>';
	$out .= '<option value="1">1</option>';
	$out .= '<option value="2">2</option>';
	$out .= '<option value="3">3</option>';
	$out .= '<option value="4">4</option>';
	$out .= '</select>';
	$out .= '</div>'; // ending roombox
	$out .= '</div>'; // ending roomboxcontainer
	
	echo $out;
}

function deliver_mail() {

    // if the submit button is clicked, send the email
    if ( isset( $_POST['cf-submitted'] ) ) {

        // sanitize form values
        $name    = sanitize_text_field( $_POST["cf-name"] );
        $email   = sanitize_email( $_POST["cf-email"] );
        $subject = sanitize_text_field( $_POST["cf-subject"] );
        $message = esc_textarea( $_POST["cf-message"] );

        // get the blog administrator's email address
        $to = get_option( 'admin_email' );

        $headers = "From: $name <$email>" . "\r\n";

        // If email has been process for sending, display a success message
        if ( wp_mail( $to, $subject, $message, $headers ) ) {
            echo '<div>';
            echo '<p>Thanks for contacting me, expect a response soon.</p>';
            echo '</div>';
        } else {
            echo 'An unexpected error occurred';
        }
    }
}

function rcnw_room_form() {
    ob_start();
    deliver_mail();
    html_form_code();

    return ob_get_clean();
}

add_shortcode( 'RCRoomForm', 'rcnw_room_form' );
