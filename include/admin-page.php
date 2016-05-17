<?php

// Add the admin options page
add_action('admin_menu', 'rc_rm_cherry_rooms_add_page');

function rc_rm_cherry_rooms_add_page() {
    add_options_page(
        __('Cherry Rooms', 'cherryroomssettings'),
        __('Cherry Rooms', 'cherryroomssettings'),
        'manage_options',                               // access level required to see the page
        'rc-rm-cherry-rooms',                          // menu slug
        'rc_rm_cherry_rooms_options_page_callback'     // callback function
    );
}

// Draw the options page
function rc_rm_cherry_rooms_options_page_callback() {
    ?>
    <div class="wrap">
        <h2>Cherry Rooms Settings</h2>
        <form action="options.php" method="post">
            <?php
                settings_fields('rcrmoptiongroup');            // refers to option group
                do_settings_sections('rc-rm-cherry-rooms');    // refers to page slug or id
                submit_button();
            ?>
        </form>
    </div>
<?php
}


function rc_rm_init() {
    // activating settings
    register_setting(
        'rcrmoptiongroup',                                // option group
        'rc_rm_options',                                  // option name, determine the name of the setting stored in the database
        'rc_rm_cherry_rooms_validate_options'            // callback function for validation
    );

    add_settings_section(
        'rc_rm_cherry_rooms_section',                     // id
        __('Settings', 'cherryroomsettings'),            // Section title
        'rc_rm_cherry_rooms_section_callback',            // callback function
        'rc-rm-cherry-rooms'                              // page id
    );

    add_settings_field(
        'rc_rm_cherry_rooms_setting_input',             // id
        __('Speed', 'cherryroomsettings'),             // lable to show in the form associated to the field
        'rc_rm_cherry_rooms_setting_input_callback',    // callback function
        'rc-rm-cherry-rooms',                           // page id
        'rc_rm_cherry_rooms_section'                    // section id
    );

    add_settings_field(
        'rc_rm_cherry_rooms_transition',                // id
        __('Transition', 'cherryroomsettings'),        // lable to show in the form associated to the field
        'rc_rm_cherry_rooms_transition_callback',       // callback function
        'rc-rm-cherry-rooms',                           // page id
        'rc_rm_cherry_rooms_section'                    // section id
    );

    add_settings_field(
        'rc_rm_cherry_rooms_easing',                    // id
        __('Easing', 'cherryroomsettings'),            // lable to show in the form associated to the field
        'rc_rm_cherry_rooms_easing_callback',           // callback function
        'rc-rm-cherry-rooms',                           // page id
        'rc_rm_cherry_rooms_section'                    // section id
    );
	
    add_settings_field(
        'rc_rm_cherry_rooms_width',                     // id
        __('Width', 'cherryroomsettings'),            // lable to show in the form associated to the field
        'rc_rm_cherry_rooms_width_callback',           // callback function
        'rc-rm-cherry-rooms',                           // page id
        'rc_rm_cherry_rooms_section'                    // section id
    );
	
    add_settings_field(
        'rc_rm_cherry_rooms_height',                    // id
        __('Height', 'cherryroomsettings'),            // lable to show in the form associated to the field
        'rc_rm_cherry_rooms_height_callback',           // callback function
        'rc-rm-cherry-rooms',                           // page id
        'rc_rm_cherry_rooms_section'                    // section id
    );

}

add_action('admin_init', 'rc_rm_init');

// Explanations about this section
function rc_rm_cherry_rooms_section_callback() {
    echo '<p>Enter your can tune the Cherry Rooms plug-in.</p>';
}

// Display and fill the form field
function rc_rm_cherry_rooms_setting_input_callback() {
    //get option 'text_string' value from the database
    $options = get_option( RCSL_OPTIONS_STRING );
    $speed = $options['speed'];
    // echo the field
    echo "<input id='speed' name='rc_rm_options[speed]' type='text' value='{$speed}' />";
}

function rc_rm_cherry_rooms_transition_callback() {
    $options = get_option( RCSL_OPTIONS_STRING );
    if( !isset( $options['transition'] ) ) $options['transition'] = 'fade';
    ?>
    <select name="rc_rm_options[transition]">
        <option value="fade" <?php selected( $options['transition'], 'fade' ); ?>>fade</option>
        <option value="horizontal" <?php selected( $options['transition'], 'horizontal' ); ?>>horizontal</option>
        <option value="vertical" <?php selected( $options['transition'], 'vertical' ); ?>>vertical</option>
        <option value="kenburns" <?php selected( $options['transition'], 'kenburns' ); ?>>kenburns</option>
    </select>
    <?php
}

function rc_rm_cherry_rooms_easing_callback() {
    $options = get_option( RCSL_OPTIONS_STRING );
    if( !isset( $options['easing'] ) ) $options['easing'] = 'swing';
    ?>
    <select name="rc_rm_options[easing]">
        <option value="swing" <?php selected( $options['easing'], 'swing' ); ?>>swing</option>
        <option value="linear" <?php selected( $options['easing'], 'linear' ); ?>>linear</option>
        <option value="easeInQuad" <?php selected( $options['easing'], 'easeInQuad' ); ?>>easeInQuad</option>
    </select>
    <?php
}

// Display and fill the form field
function rc_rm_cherry_rooms_width_callback() {
    //get option 'text_string' value from the database
    $options = get_option( RCSL_OPTIONS_STRING );
    $width = $options['width'];
    // echo the field
    echo "<input id='width' name='rc_rm_options[width]' type='number' value='{$width}' />";
}

// Display and fill the form field
function rc_rm_cherry_rooms_height_callback() {
    //get option 'text_string' value from the database
    $options = get_option( RCSL_OPTIONS_STRING );
    $height = $options['height'];
    // echo the field
    echo "<input id='height' name='rc_rm_options[height]' type='number' value='{$height}' />";
}

// Validate input
function rc_rm_cherry_rooms_validate_options( $input ) {
    $valid = array();
    $valid['speed'] = preg_replace( '/[^0-9]/', '', $input['speed'] );
    $valid['transition'] = preg_replace( '/[^a-zA-Z]/', '', $input['transition'] );
    $valid['easing'] = preg_replace( '/[^a-zA-Z]/', '', $input['easing'] );
    $valid['width'] = preg_replace( '/[^0-9]/', '', $input['width'] );
    $valid['height'] = preg_replace( '/[^0-9]/', '', $input['height'] );
    return $valid;
}
