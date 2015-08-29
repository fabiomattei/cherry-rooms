<?php

/**
 * This is the room post type
 *
 * archive page: http://localhost:8888/wordpress/?post_type=rcnwroom
 * you can link the archive page calling: echo get_post_type_archive_link( 'rcnwroom' );
 */

add_action( 'init', 'rcnw_create_room_post_type' );
function rcnw_create_room_post_type() {

  	$labels = array(
		'name'               => _x( 'Rooms', 'post type general name', 'rcnw' ),
		'singular_name'      => _x( 'Room', 'post type singular name', 'rcnw' ),
		'menu_name'          => _x( 'Rooms', 'admin menu', 'rcnw' ),
		'name_admin_bar'     => _x( 'Room', 'add new on admin bar', 'rcnw' ),
		'add_new'            => _x( 'Add New', 'book', 'rcnw' ),
		'add_new_item'       => __( 'Add New Room', 'rcnw' ),
		'new_item'           => __( 'New Room', 'rcnw' ),
		'edit_item'          => __( 'Edit Room', 'rcnw' ),
		'view_item'          => __( 'View Room', 'rcnw' ),
		'all_items'          => __( 'All Rooms', 'rcnw' ),
		'search_items'       => __( 'Search Rooms', 'rcnw' ),
		'parent_item_colon'  => __( 'Parent Rooms:', 'rcnw' ),
		'not_found'          => __( 'No rooms found.', 'rcnw' ),
		'not_found_in_trash' => __( 'No rooms found in Trash.', 'rcnw' )
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_nav_menus'  => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'room' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
	);

	register_post_type( 'rcnwroom', $args );
	
}


add_filter( 'post_updated_messages', 'rcnw_room_updated_messages' );
/**
 * Book update messages.
 *
 * See /wp-admin/edit-form-advanced.php
 *
 * @param array $messages Existing post update messages.
 *
 * @return array Amended post update messages with new CPT update messages.
 */
function rcnw_room_updated_messages( $messages ) {
	$post             = get_post();
	$post_type        = get_post_type( $post );
	$post_type_object = get_post_type_object( $post_type );

	$messages['book'] = array(
		0  => '', // Unused. Messages start at index 1.
		1  => __( 'Room updated.', 'rcnw' ),
		2  => __( 'Custom field updated.', 'rcnw' ),
		3  => __( 'Custom field deleted.', 'rcnw' ),
		4  => __( 'Room updated.', 'rcnw' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Room restored to revision from %s', 'rcnw' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6  => __( 'Room published.', 'rcnw' ),
		7  => __( 'Room saved.', 'rcnw' ),
		8  => __( 'Room submitted.', 'rcnw' ),
		9  => sprintf(
			__( 'Room scheduled for: <strong>%1$s</strong>.', 'rcnw' ),
			// translators: Publish box date format, see http://php.net/date
			date_i18n( __( 'M j, Y @ G:i', 'rcnw' ), strtotime( $post->post_date ) )
		),
		10 => __( 'Room draft updated.', 'rcnw' )
	);

	if ( $post_type_object->publicly_queryable ) {
		$permalink = get_permalink( $post->ID );

		$view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'View room', 'rcnw' ) );
		$messages[ $post_type ][1] .= $view_link;
		$messages[ $post_type ][6] .= $view_link;
		$messages[ $post_type ][9] .= $view_link;

		$preview_permalink = add_query_arg( 'preview', 'true', $permalink );
		$preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview room', 'rcnw' ) );
		$messages[ $post_type ][8]  .= $preview_link;
		$messages[ $post_type ][10] .= $preview_link;
	}

	return $messages;
}

//display contextual help for Books

function rcnw_room_add_help_text( $contextual_help, $screen_id, $screen ) {
  //$contextual_help .= var_dump( $screen ); // use this to help determine $screen->id
  if ( 'book' == $screen->id ) {
    $contextual_help =
      '<p>' . __('Things to remember when adding or editing a room:', 'rcnw') . '</p>' .
      '<ul>' .
      '<li>' . __('Specify the correct genre such as Mystery, or Historic.', 'rcnw') . '</li>' .
      '<li>' . __('Specify the correct writer of the book.  Remember that the Author module refers to you, the author of this book review.', 'rcnw') . '</li>' .
      '</ul>' .
      '<p>' . __('If you want to schedule the book review to be published in the future:', 'rcnw') . '</p>' .
      '<ul>' .
      '<li>' . __('Under the Publish module, click on the Edit link next to Publish.', 'rcnw') . '</li>' .
      '<li>' . __('Change the date to the date to actual publish this article, then click on Ok.', 'rcnw') . '</li>' .
      '</ul>' .
      '<p><strong>' . __('For more information:', 'rcnw') . '</strong></p>' .
      '<p>' . __('<a href="http://codex.wordpress.org/Posts_Edit_SubPanel" target="_blank">Edit Posts Documentation</a>', 'rcnw') . '</p>' .
      '<p>' . __('<a href="http://wordpress.org/support/" target="_blank">Support Forums</a>', 'rcnw') . '</p>' ;
  } elseif ( 'edit-book' == $screen->id ) {
    $contextual_help =
      '<p>' . __('This is the help screen displaying the table of books blah blah blah.', 'rcnw') . '</p>' ;
  }
  return $contextual_help;
}

add_action( 'contextual_help', 'rcnw_room_add_help_text', 10, 3 );
