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
	$out = '<div class="external-post-horizontal-list-container">
				<div class="post-horizontal-list-title-wrapper">
					<h4 class="post-horizontal-list-title">Our rooms</h4>
				</div>
					<div class="post-horizontal-list-box">';
	
	if ($posts->have_posts()) {
		
	    while ($posts->have_posts()) {
	        $posts->the_post();

	        $room_currency = '';
	        switch (get_post_meta($post->ID, 'room_currency', true)) {
	        	case '': $room_currency=''; break;
	        	case 'room_none': $room_currency=''; break;
    			case 'room_USD': $room_currency='$'; break;
    			case 'room_EUR': $room_currency='&euro;'; break;
    			case 'room_GBP': $room_currency='&pound;'; break;
    			case 'room_JPY': $room_currency='&yen;'; break;
    			case 'room_CNY': $room_currency='&yuan;'; break;
			}

	        $out .= '<div class="singlepost-horizontal-list-box">
				<p class="post-horizontal-list-boxthumbnail">'.get_the_post_thumbnail().'
				<span class="post-horizontal-list-write-over-img">'.get_post_meta($post->ID, 'room_price', true).' '.$room_currency.'</span>
				</p>
	            <h5><a href="'.get_permalink().'" title="' . get_the_title() . '">'.get_the_title() .'</a></h5>
	            <p class="post-horizontal-list-desc">'.get_the_content().'</p>';
	            // add here more...
	        $out .= '</div>';
	
		} // end while loop
		
	} else {
		return; // no posts found
	}
	$out .= '</div>'; // ending post-horizontal-list-box
	$out .= '</div>'; // ending external-post-horizontal-list-container
	
	ob_start();

	echo $out;
	
    return ob_get_clean();
}

add_shortcode( 'RCRoomListHome', 'rcnw_room_list_home' );

// rename to horizontal list

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
			<div class="roomformtitlewrapper">
				<h4>Book a room</h4>
			</div>
			<div class="roomform">
			<form method="post">
				<div class="room-big-field-container">
					<input type="text" name="rm-name" placeholder="name" >
				</div> <!-- .room-big-field-container -->
				<div class="room-big-field-container">
					<input type="text" name="rm-email" placeholder="email" >
				</div> <!-- .room-big-field-container -->
				<div class="room-small-field-container">
					<input type="text" id="datepicker" name="rm-arrival" placeholder="arrival">
				</div> <!-- .room-small-field-container -->
				<div class="room-small-field-container">
				<input type="text" id="datepicker2" name="rm-departure" placeholder="departure">
				</div> <!-- .room-small-field-container -->
				<div class="room-small-field-container">
					<select name="rm-room">';
		$out .= '<option value="no room selected">room</option>';
	
	if ($posts->have_posts()) {
		
	    while ($posts->have_posts()) {
	        $posts->the_post();
			
	        $out .= '<option value="'.get_the_title() .'">'.get_the_title() .'</option>';

		} // end while loop
		
	} else {
		return; // no posts found
	}
	$out .= '</select>';
	$out .= '</div> <!-- .room-small-field-container -->';
	$out .= '<input type="submit" value="Send" name="rm-submitted">';
	$out .= '</form>';
	$out .= '</div>'; // ending post-horizontal-list-box
	$out .= '</div>'; // ending external-post-horizontal-list-container
	
	echo $out;
}

function deliver_mail() {

    // if the submit button is clicked, send the email
    if ( isset( $_POST['rm-submitted'] ) ) {

        // sanitize form values
        $name    = sanitize_text_field( $_POST["rm-name"] );
        $email   = sanitize_email( $_POST["rm-email"] );
		
		$arrival = sanitize_text_field( $_POST["rm-arrival"] );
		$departure = sanitize_text_field( $_POST["rm-departure"] );
		$room = sanitize_text_field( $_POST["rm-room"] );
		
		$subject = 'Reservation from '.$arrival.' to '.$departure;
		$message = $subject.' for room: '.$room;
		/*
        $subject = sanitize_text_field( $_POST["rm-subject"] );
        $message = esc_textarea( $_POST["rm-message"] );
		*/

        // get the blog administrator's email address
        $to = get_option( 'admin_email' );
        $headers = "From: $name <$email>" . "\r\n";

        // If email has been process for sending, display a success message
        if ( wp_mail( $to, $subject, $message, $headers ) ) {
            echo '<div>';
            echo '<p>Thanks for contacting us, expect a response soon.</p>';
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


