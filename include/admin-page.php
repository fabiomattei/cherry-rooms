<?php
 
/**
 * Option panel class
 */
class RCRM_Options {
  
    /*--------------------------------------------*
     * Attributes
     *--------------------------------------------*/
  
    /** Refers to a single instance of this class. */
    private static $instance = null;
     
    /* Saved options */
    public $options;
  
    /*--------------------------------------------*
     * Constructor
     *--------------------------------------------*/
  
    /**
     * Creates or returns an instance of this class.
     *
     * @return  CPA_Theme_Options A single instance of this class.
     */
    public static function get_instance() {
  
        if ( null == self::$instance ) {
            self::$instance = new self;
        }
  
        return self::$instance;
  
    } // end get_instance;
  
    /**
     * Initializes the plugin by setting localization, filters, and administration functions.
     */
	private function __construct() { 
	    // Add the page to the admin menu
	    add_action( 'admin_menu', array( &$this, 'add_page' ) );
     
	    // Register page options
	    add_action( 'admin_init', array( &$this, 'register_page_options') );
     
	    // Css rules for Color Picker
	    wp_enqueue_style( 'wp-color-picker' );
     
	    // Register javascript
	    add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_js' ) );
     
	    // Get registered option
	    $this->options = get_option( RCRO_SETTINGS_KEY );
	}
  
    /*--------------------------------------------*
     * Functions
     *--------------------------------------------*/
      
	/**
	 * Function that will add the options page under Setting Menu.
	 */
	public function add_page() { 
	    add_options_page(
	        __('Cherry Rooms', 'cherryroomssettings'),      // page_title
	        __('Cherry Rooms', 'cherryroomssettings'),      // menu_title
	        'manage_options',                               // access level required to see the page
	        'rc-rm-cherry-rooms',                           // menu slug
	        array( $this, 'display_page' )                  // callback function
	    );
	}
 
	/**
	 * Function that will display the options page.
	 */
	public function display_page() { 
	    ?>
	    <div class="wrap">
	        <h2>Cherry Rooms Settings</h2>
	        <form action="options.php" method="post">
	            <?php
	                settings_fields( 'rcrmoptiongroup' );            // refers to option group
	                do_settings_sections( 'rc-rm-cherry-rooms' );    // refers to page slug or id
	                submit_button();
	            ?>
	        </form>
	    </div>
		<?php  
	}
       
	/**
	 * Function that will register admin page options.
	 */
	public function register_page_options() { 
	    
	    add_settings_section(
	        'rc_rm_cherry_rooms_section',                     // id
	        __('Settings', 'cherryroomsettings'),             // Section title
	        array( $this, 'display_section' ),                // callback function
	        'rc-rm-cherry-rooms'                              // page id
	    );

	    add_settings_field(
	        'rc_rm_cherry_rooms_setting_input',             // id
	        __('Speed', 'cherryroomsettings'),              // lable to show in the form associated to the field
	        array( $this, 'speed_settings_field' ),         // callback function
	        'rc-rm-cherry-rooms',                           // page id
	        'rc_rm_cherry_rooms_section'                    // section id
	    );

	    add_settings_field(
	        'rc_rm_cherry_rooms_transition',                // id
	        __('Transition', 'cherryroomsettings'),         // lable to show in the form associated to the field
	        array( $this, 'transition_settings_field' ),    // callback function
	        'rc-rm-cherry-rooms',                           // page id
	        'rc_rm_cherry_rooms_section'                    // section id
	    );
		
	    // Add Background Color Field
	    add_settings_field( 
			'cpa_bg_field',                                 // id
			__('Background Color', 'cherryroomsettings'),   // lable to show in the form associated to the field
			array( $this, 'bg_settings_field' ),            // callback function
			'rc-rm-cherry-rooms',                           // page id
			'rc_rm_cherry_rooms_section'                    // section id
		);
		
	    // activating settings
	    register_setting(
	        'rcrmoptiongroup',                                // option group
	        'rc_rm_options',                                  // option name, determine the name of the setting stored in the database
	        array( $this, 'validate_options' )                // callback function for validation
	    );
	}
     
	/**
	 * Function that will add javascript file for Color Piker.
	 */
	public function enqueue_admin_js() { 
	    // Make sure to add the wp-color-picker dependecy to js file
	    wp_enqueue_script( 'cpa_custom_js', RCRO_PLUGIN_URL . 'js/jquery.custom.js', array( 'jquery', 'wp-color-picker' ), '', true  );
	}
     
	/**
	 * Function that will validate all fields.
	 */
	public function validate_options( $fields ) { 
	    $valid_fields = array();
		
	    $valid_fields['speed'] = preg_replace( '/[^0-9]/', '', $fields['speed'] );
	    $valid_fields['transition'] = preg_replace( '/[^a-zA-Z]/', '', $fields['transition'] );
     
	    // Validate Title Field
	    $title = trim( $fields['title'] );
	    $valid_fields['title'] = strip_tags( stripslashes( $title ) );
     
	    // Validate Background Color
	    $background = trim( $fields['background'] );
	    $background = strip_tags( stripslashes( $background ) );
     
	    // Check if is a valid hex color
	    if( FALSE === $this->check_color( $background ) ) {
     
	        // Set the error message
	        add_settings_error( 'cpa_settings_options', 'cpa_bg_error', 'Insert a valid color for Background', 'error' ); // $setting, $code, $message, $type
         
	        // Get the previous valid value
	        $valid_fields['background'] = $this->options['background'];
     
	    } else {
     
	        $valid_fields['background'] = $background;  
     
	    }
     
	    return apply_filters( 'validate_options', $valid_fields, $fields);
	}
 
	/**
	 * Function that will check if value is a valid HEX color.
	 */
	public function check_color( $value ) { 
	    if ( preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) { // if user insert a HEX color with #     
	        return true;
	    }
     
	    return false;
	}
     
    /**
     * Callback function for settings section
     */
    public function display_section() { /* Leave blank */ } 
	// echo '<p>Enter your can tune the Cherry Rooms plug-in.</p>';

	/**
	 * Functions that display the fields.
	 */
	public function speed_settings_field() { 
	    $val = ( isset( $this->options['speed'] ) ) ? $this->options['speed'] : '';
	    echo '<input type="text" name="rc_rm_options[speed]" value="' . $val . '" />';
	}
	
	public function transition_settings_field() { 
	    $val = ( isset( $this->options['transition'] ) ) ? $this->options['transition'] : 'fade';
	    ?>
	    <select name="rc_rm_options[transition]">
	        <option value="fade" <?php selected( $val, 'fade' ); ?>>fade</option>
	        <option value="horizontal" <?php selected( $val, 'horizontal' ); ?>>horizontal</option>
	        <option value="vertical" <?php selected( $val, 'vertical' ); ?>>vertical</option>
	        <option value="kenburns" <?php selected( $val, 'kenburns' ); ?>>kenburns</option>
	    </select>
	    <?php
	}
 
	public function bg_settings_field() { 
	    $val = ( isset( $this->options['title'] ) ) ? $this->options['background'] : '';
	    echo '<input type="text" name="rc_rm_options[background]" value="' . $val . '" class="rc-cr-color-picker" >';
	}
         
} // end class
  
RCRM_Options::get_instance();
