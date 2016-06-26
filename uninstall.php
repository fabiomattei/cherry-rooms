<?php

// If uninstall not called from WordPress exit 
if( !defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();

// Delete option from options table 
delete_option( 'rc_rm_options' );
