<?php

// Define absolute path of plugin
define( 'IG_PB_PATH', plugin_dir_path( IG_PB_FILE ) );

// Define absolute path of shortcodes folder
define( 'IG_PB_LAYOUT_PATH', plugin_dir_path( IG_PB_FILE ) . 'core/shortcode/layout' );
define( 'IG_PB_ELEMENT_PATH', plugin_dir_path( IG_PB_FILE ) . 'shortcodes' );

// Define premade layout folder
define( 'IG_PB_PREMADE_LAYOUT', plugin_dir_path( IG_PB_FILE ) . 'templates/layout/pre-made' );
define( 'IG_PB_PREMADE_LAYOUT_URI', plugin_dir_url( IG_PB_FILE ) . 'templates/layout/pre-made' );

// Define absolute path of templates folder
define( 'IG_PB_TPL_PATH', plugin_dir_path( IG_PB_FILE ) . 'templates' );

// Define plugin uri
define( 'IG_PB_URI', plugin_dir_url( IG_PB_FILE ) );

// Define plugin domain
define( 'IGPBL', 'ig-pb' );

// Define nonce ID
define( 'IGNONCE', 'ig_nonce_check' );
