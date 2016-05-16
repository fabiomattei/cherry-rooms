<?php

/**
 * This class helps to deal with few settings connected with the cherry-rooms
 * custom post type.
 * For each room the user can set two variables:
 * - price
 * - currency
 */
class Room_Price_Meta_Box {

	/**
	 * The constructor cares about activating the class and connecting the class
	 * to the right actions
	 */
	public function __construct() {
		if ( is_admin() ) {
			add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
			add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
		}
	}

	/**
	 * Add actions for showing the metabox and saving the data for each post
	 */
	public function init_metabox() {
		add_action( 'add_meta_boxes', array( $this, 'add_metabox'  )        );
		add_action( 'save_post',      array( $this, 'save_metabox' ), 10, 2 );
	}

	/**
	 * Adding metabox to the right form
	 */
	public function add_metabox() {
		add_meta_box(
			'room_price',
			__( 'Room Price', 'rcnw' ),
			array( $this, 'render_metabox' ),
			'rcnwroom',
			'advanced',
			'default'
		);
	}

	/**
	 * @param $post
	 *
	 * Rendering metabox form
	 */
	public function render_metabox( $post ) {

		// Add nonce for security and authentication.
		wp_nonce_field( 'room_nonce_action', 'room_nonce' );

		// Retrieve an existing value from the database.
		$room_price = get_post_meta( $post->ID, 'room_price', true );
		$room_currency = get_post_meta( $post->ID, 'room_currency', true );

		// Set default values.
		if( empty( $room_price ) ) $room_price = '';
		if( empty( $room_currency ) ) $room_currency = '';

		// Form fields.
		echo '<table class="form-table">';

		echo '	<tr>';
		echo '		<th><label for="room_price" class="room_price_label">' . __( 'Price', 'rcnw' ) . '</label></th>';
		echo '		<td>';
		echo '			<input id="room_price" name="room_price" class="room_price_field" placeholder="' . esc_attr__( '', 'rcnw' ) . '" value="' . esc_attr__( $room_price ) . '">';
		echo '			<p class="description">' . __( 'Price', 'rcnw' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label for="room_currency" class="room_currency_label">' . __( 'Currency', 'rcnw' ) . '</label></th>';
		echo '		<td>';
		echo '			<select id="room_currency" name="room_currency" class="room_currency_field">';
		echo '			<option value="room_none" ' . selected( $room_currency, 'room_none', false ) . '> ' . __( 'None', 'rcnw' );
		echo '			<option value="room_USD" ' . selected( $room_currency, 'room_USD', false ) . '> ' . __( 'USD', 'rcnw' );
		echo '			<option value="room_EUR" ' . selected( $room_currency, 'room_EUR', false ) . '> ' . __( 'Euro', 'rcnw' );
		echo '			<option value="room_GBP" ' . selected( $room_currency, 'room_GBP', false ) . '> ' . __( 'GB Pound', 'rcnw' );
		echo '			<option value="room_JPY" ' . selected( $room_currency, 'room_JPY', false ) . '> ' . __( 'Japanese Yen', 'rcnw' );
		echo '			<option value="room_CNY" ' . selected( $room_currency, 'room_CNY', false ) . '> ' . __( 'Chinese Yuan', 'rcnw' );
		echo '			</select>';
		echo '		</td>';
		echo '	</tr>';

		echo '</table>';

	}

	/**
	 * @param $post_id
	 * @param $post
	 *
	 * Saving metabox data
	 */
	public function save_metabox( $post_id, $post ) {

		// Add nonce for security and authentication.
		$nonce_name   = $_POST['room_nonce'];
		$nonce_action = 'room_nonce_action';

		// Check if a nonce is set.
		if ( ! isset( $nonce_name ) )
			return;

		// Check if a nonce is valid.
		if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) )
			return;

		// Check if the user has permissions to save data.
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return;

		// Check if it's not an autosave.
		if ( wp_is_post_autosave( $post_id ) )
			return;

		// Check if it's not a revision.
		if ( wp_is_post_revision( $post_id ) )
			return;

		// Sanitize user input.
		$room_new_price = isset( $_POST[ 'room_price' ] ) ? sanitize_text_field( $_POST[ 'room_price' ] ) : '';
		$room_new_currency = isset( $_POST[ 'room_currency' ] ) ? $_POST[ 'room_currency' ] : '';

		// Update the meta field in the database.
		update_post_meta( $post_id, 'room_price', $room_new_price );
		update_post_meta( $post_id, 'room_currency', $room_new_currency );

	}

}

// Instantiating the class
new Room_Price_Meta_Box;
