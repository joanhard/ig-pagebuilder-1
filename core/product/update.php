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
 * Product update class.
 *
 * @package  IG_Library
 * @since    1.0.0
 */
class IG_Product_Update {
	/**
	 * Link to get product installation package.
	 *
	 * @var  string
	 */
	protected static $product_download = 'http://www.innogears.com/lightcart/index.php?option=com_lightcart&controller=remoteconnectauthentication&task=authenticate&tmpl=component';

	/**
	 * Render product update screen.
	 *
	 * @param   string  $plugin_file  Absolute path to plugin main file.
	 *
	 * @return  void
	 */
	public static function init( $plugin_file ) {
		// Get template path
		if ( $tmpl = IG_Loader::get_path( 'product/tmpl/update.php' ) ) {
			$updatable = true;

			// Get product information
			$plugin = IG_Product_Info::get( $plugin_file, true );

			if ( ! $plugin ) {
				$updatable = false;
				$message   = __( 'Cannot retrieve product information.', IGPBL );
			} elseif ( ! $plugin['Available_Update'] ) {
				$updatable = false;
				$message   = __( 'No update available.', IGPBL );
			}

			// Load template
			include_once $tmpl;
		}
	}

	/**
	 * Method to download product update package.
	 *
	 * @return  void
	 */
	public static function download() {
		global $wp_filesystem;

		// Verify parameters
		if ( ! isset( $_REQUEST['id'] ) ) {
			die( 'FAIL: ' . __( "Missing product's identified name.", IGPBL ) );
		}

		if ( ! isset( $_REQUEST['edition'] ) ) {
			die( 'FAIL: ' . __( 'Missing product edition.', IGPBL ) );
		}

		// Build query string
		$query[] = 'identified_name=' . $_REQUEST['id'];
		$query[] = 'username=' . ( isset( $_REQUEST['customer_username'] ) ? urlencode( $_REQUEST['customer_username'] ) : '' );
		$query[] = 'password=' . ( isset( $_REQUEST['customer_password'] ) ? urlencode( $_REQUEST['customer_password'] ) : '' );
		$query[] = 'product_attr=' . urlencode( json_encode( array( 'edition' => strtolower( $_REQUEST['edition'] ) ) ) );
		$query[] = 'upgrade=yes';

		// Build final URL for downloading update package
		$url = self::$product_download . '&' . implode( '&', $query );

		// Download update package
		$target = wp_upload_dir();
		$target = $target['basedir'] . '/' . $_REQUEST['id'] . '-' . str_replace( ' ', '-', $_REQUEST['edition'] ) . '-update-package.zip';
		$result = download_url( $url );

		if ( ! $result || is_wp_error( $result ) ) {
			die( 'FAIL: ' . ( is_wp_error( $result ) ? $result->get_error_message() : __( 'Cannot connect to InnoGears.com server.', IGPBL ) ) );
		}

		if ( $wp_filesystem->size( $result ) < 10 ) {
			$result = $wp_filesystem->get_contents( $result );

			switch ( $result ) {
				case 'ERR00':
					die( 'FAIL: ' . __( 'Invalid Parameters! Can not verify your product information.', IGPBL ) );
				break;

				case 'ERR01':
					die( 'FAIL: ' . __( 'Invalid username or password.', IGPBL ) );
				break;

				case 'ERR02':
					die( 'FAIL: ' . __( 'We could not find the product in your order list. Seems like you did not purchase it yet.', IGPBL ) );
				break;

				case 'ERR03':
					die( 'FAIL: ' . __( 'Requested file is not found on server.', IGPBL ) );
				break;

				default:
					die( 'FAIL: ' . __( 'Cannot connect to InnoGears.com server.', IGPBL ) );
				break;
			}
		}

		// Create a local file by copying temporary file
		if ( ! $wp_filesystem->copy( $result, $target, true, FS_CHMOD_FILE ) ) {
			// If copy failed, chmod file to 0644 and try again
			$wp_filesystem->chmod( $target, 0644 );

			if ( ! $wp_filesystem->copy( $result, $target, true, FS_CHMOD_FILE ) ) {
				die( 'FAIL: ' . __( 'Cannot store update package to local file system.', IGPBL ) );
			}
		}

		// Remove temporary file
		$wp_filesystem->delete( $result );

		// Finished
		die( 'DONE: ' . basename( $target ) );
	}

	/**
	 * Method to install product update package.
	 *
	 * @return  void
	 */
	public static function install() {
		global $wp_filesystem;

		// Verify parameters
		if ( ! isset( $_REQUEST['id'] ) ) {
			die( 'FAIL: ' . __( "Missing product's identified name.", IGPBL ) );
		}

		if ( ! isset( $_REQUEST['edition'] ) ) {
			die( 'FAIL: ' . __( 'Missing product edition.', IGPBL ) );
		}

		// Detect plugin base file
		$plugin_dir = WP_PLUGIN_DIR;
		$plugin     = null;
		$name       = str_replace( '_', '-', trim( $_REQUEST['id'] ) );

		if ( @is_file( "{$plugin_dir}/{$name}.php" ) ) {
			$plugin = "{$name}.php";
		}

		if ( ! $dependency && @is_file( "{$plugin_dir}/{$name}/main.php" ) ) {
			$plugin = "{$name}/main.php";
		}

		if ( ! $dependency && @is_file( "{$plugin_dir}/{$name}/{$name}.php" ) ) {
			$plugin = "{$name}/{$name}.php";
		}

		if ( empty( $plugin ) ) {
			die( 'FAIL: ' . __( 'Cannot detect plugin to be updated.', IGPBL ) );
		}

		// Get the absolute path to update package
		$source = wp_upload_dir();
		$source = $source['basedir'] . '/' . $_REQUEST['id'] . '-' . str_replace( ' ', '-', $_REQUEST['edition'] ) . '-update-package.zip';

		if ( ! @is_file( $source ) ) {
			die( 'FAIL: ' . __( 'Missing product update package.', IGPBL ) );
		}

		// Init WordPress Plugin Upgrader
		class_exists( 'Plugin_Upgrader' ) || include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

		function_exists( 'screen_icon'     ) || include_once ABSPATH . 'wp-admin/includes/screen.php';
		function_exists( 'show_message'    ) || include_once ABSPATH . 'wp-admin/includes/misc.php';
		function_exists( 'get_plugin_data' ) || include_once ABSPATH . 'wp-admin/includes/plugin.php';

		// Install update package
		$upgrader = new Plugin_Upgrader();

		add_filter( 'upgrader_pre_install',       array( $upgrader, 'deactivate_plugin_before_upgrade' ), 10, 2 );
		add_filter( 'upgrader_clear_destination', array( $upgrader, 'delete_old_plugin'                ), 10, 4 );

		$upgrader->run(
			array(
				'package'           => $source,
				'destination'       => WP_PLUGIN_DIR,
				'clear_destination' => true,
				'clear_working'     => true,
				'hook_extra'        => array(
					'plugin' => $plugin,
					'type'   => 'plugin',
					'action' => 'update',
				),
			)
		);

		remove_filter( 'upgrader_pre_install',       array( $this, 'deactivate_plugin_before_upgrade' ) );
		remove_filter( 'upgrader_clear_destination', array( $this, 'delete_old_plugin'                ) );

		if ( ! $upgrader->result || is_wp_error( $upgrader->result ) ) {
			die( 'FAIL: ' . ( is_wp_error( $result ) ? $result->get_error_message() : 'Cannot install update package.' ) );
		}

		// Force refresh of plugin update information
		wp_clean_plugins_cache( true );

		// Remove downloaded update package
		$wp_filesystem->delete( $source );

		// Finished
		die( 'DONE' );
	}

	/**
	 * Hook into WordPress.
	 *
	 * @return  void
	 */
	public static function hook() {
		// Register Ajax actions
		add_action( 'wp_ajax_ig-download-update', array( __CLASS__, 'download' ) );
		add_action( 'wp_ajax_ig-install-update',  array( __CLASS__, 'install'  ) );
	}
}
