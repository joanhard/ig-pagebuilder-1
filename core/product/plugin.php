<?php
/**
 * @version    $Id$
 * @package    IG_Library
 * @author     InnoGears Team <support@innogears.com>
 * @copyright  Copyright (C) 2012 InnoGears.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.innogears.com
 * Technical Support: Feedback - http://www.innogears.com/contact-us/get-support.html
 */

/**
 * IG Pagebuilder Settings
 *
 * @package  IG_Library
 * @since    1.0.0
 */
class IG_Product_Plugin {
	/**
	 * Initialize IG Sample plugin.
	 *
	 * @return  void
	 */
	public static function init() {
		global $pagenow;

		// Initialize necessary IG Library classes
		IG_Init_Admin_Menu::hook();

		// Register admin menu
		IG_Init_Admin_Menu::add(
			array(
				'page_title' => __( 'IG Pagebuilder', IGPBL ),
				'menu_title' => __( 'IG Pagebuilder', IGPBL ),
				'capability' => 'manage_options',
				'menu_slug'  => 'ig-pb-settings',
				'icon_url'  => IG_Pb_Helper_Functions::path( 'assets/innogears' ) . '/images/inno.png',
				'function'   => array( __CLASS__, 'settings' ),
				'children'   => array(
					array(
						'page_title' => __( 'IG Pagebuilder - Settings', IGPBL ),
						'menu_title' => __( 'Settings', IGPBL ),
						'capability' => 'manage_options',
						'menu_slug'  => 'ig-pb-settings',
						'function'   => array( __CLASS__, 'settings' ),
					),
				),
			)
		);

		// Init WordPress Filesystem Abstraction
		global $wp_filesystem;

		if ( ! function_exists( 'WP_Filesystem' ) ) {
			include_once ABSPATH . 'wp-admin/includes/file.php';
		}

		if ( ! $wp_filesystem ) {
			WP_Filesystem();
		}

		if ( 'admin.php' == $pagenow && isset( $_GET['page'] ) && in_array( $_GET['page'], array( 'ig-pb-settings', 'ig-pb-upgrade' ) ) ) {
			IG_Init_Assets::load( array( 'ig-bootstrap-css', 'ig-pb-joomlashine-css' ) );
		} elseif ( 'admin-ajax.php' == $pagenow ) {
			// Register Ajax actions
			switch ( $_GET['action'] ) {
				case 'ig-download-update':
				case 'ig-install-update' :
					IG_Product_Update::hook();
					break;

				case 'ig-check-edition':
					IG_Product_Upgrade::hook();
					break;
			}
		}
	}

	public static function load_assets() {
		IG_Pb_Helper_Functions::enqueue_styles();
		IG_Pb_Helper_Functions::enqueue_scripts_end();
	}

	/**
	 * Product update initialization.
	 *
	 * @return  object
	 */
	public static function update() {
		// Load update script
		IG_Init_Assets::load( 'ig-update-js' );

		// Instantiate update class
		IG_Product_Update::init( IG_PB_FILE );
	}

	/**
	 * Product upgrade initialization.
	 *
	 * @return  void
	 */
	public static function upgrade() {
		// Load update script
		IG_Init_Assets::load( 'ig-pb-upgrade-js' );

		// Instantiate upgrade class
		IG_Product_Upgrade::init( IG_PB_FILE );
	}

	/**
	 * Product settings page
	 *
	 * @return  void
	 */
	public static function settings() {
		// Load update script
		IG_Init_Assets::load( 'ig-pb-settings-js' );

		include IG_PB_TPL_PATH . '/settings.php';
	}
}
