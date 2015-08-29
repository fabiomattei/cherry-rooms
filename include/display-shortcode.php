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
				<h4>Our rooms</h4>
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

