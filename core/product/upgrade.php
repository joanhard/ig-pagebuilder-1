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
 * Product upgrade class.
 *
 * @package  IG_Library
 * @since    1.0.0
 */
class IG_Product_Upgrade extends IG_Product_Update {
	/**
	 * Link to check product upgrade availability.
	 *
	 * @var  string
	 */
	protected static $product_upgrade = 'http://www.innogears.com/lightcart/versioning/product_upgrade.php';

	/**
	 * Render product upgrade screen.
	 *
	 * @param   string  $plugin_file  Absolute path to plugin main file.
	 *
	 * @return  void
	 */
	public static function init( $plugin_file ) {
		global $wp_filesystem;

		// Get template path
		if ( $tmpl = IG_Loader::get_path( 'product/tmpl/upgrade.php' ) ) {
			$upgrable = true;

			// Get product information
			$plugin = IG_Product_Info::get( $plugin_file, true );

			if ( ! $plugin ) {
				$upgrable = false;
				$message  = __( 'Cannot retrieve product information.', IGPBL );
			}

			// Get available product editions
			$editions = IG_Product_Info::check( $plugin['Identified_Name'], null, null, true );
			$highest  = $editions[0][0];

			if ( strcasecmp( $plugin['Edition'], $highest->edition ) == 0 ) {
				$upgrable = false;
				$message  = __( 'You are currently using the highest available edition.', IGPBL );
			}

			if ( $upgrable ) {
				// Get InnoGears product info
				$result = download_url( self::$product_upgrade );

				if ( ! is_wp_error( $result ) ) {
					if ( $product_upgrade = $wp_filesystem->get_contents( $result ) ) {
						if ( $product_upgrade = json_decode( $product_upgrade, true ) ) {
							// Remove temporary file
							$wp_filesystem->delete( $result );
						}
					}
				}

				if ( ! $product_upgrade ) {
					$upgrable = false;
					$message  = __( 'Cannot retrieve product upgrade details.', IGPBL );
				}

				// Get upgrade details for current plugin
				if ( isset( $product_upgrade[ $plugin['Identified_Name'] ] ) ) {
					$product_upgrade = $product_upgrade[ $plugin['Identified_Name'] ];
				} elseif ( isset( $response['plugin'] ) ) {
					$product_upgrade = $product_upgrade['plugin'];
				} else {
					$product_upgrade = $product_upgrade['default'];
				}

				// Prepare content
				$content = '';

				foreach ( $product_upgrade as $edition => $benefit ) {
					if ( ! empty( $content ) ) {
						$content .= $benefit;
					} elseif ( strcasecmp( $plugin['Edition'], $edition ) == 0 ) {
						$content = $benefit;
					}
				}
			}

			// Load template
			include_once $tmpl;
		}
	}

	/**
	 * Get purchased edition for provided username/password.
	 *
	 * @return  void
	 */
	public static function get_purchased_edition() {
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
		$query[] = 'upgrade=no';

		// Finalize link
		$url = self::$product_download . '&' . implode( '&', $query );

		// Get purchased edition
		$purchased = null;
		$result    = download_url( $url );

		if ( ! $result || is_wp_error( $result ) ) {
			die( json_encode(
				array(
					'message' => ( is_wp_error( $result ) ? $result->get_error_message() : __( 'Cannot connect to InnoGears.com server.', IGPBL ) ),
					'type'    => 'error',
				)
			) );
		}

		if ( $wp_filesystem->size( $result ) < 10 ) {
			$result = $wp_filesystem->get_contents( $result );

			switch ( $result ) {
				case 'ERR00':
					die( json_encode(
						array(
							'message' => __( 'Invalid Parameters! Can not verify your product information.', IGPBL ),
							'type'    => 'error',
						)
					) );
				break;

				case 'ERR01':
					die( json_encode(
						array(
							'message' => __( 'Invalid username or password.', IGPBL ),
							'type'    => 'error',
						)
					) );
				break;

				case 'ERR02':
					die( json_encode(
						array(
							'message' => __( 'We could not find the product in your order list. Seems like you did not purchase it yet.', IGPBL ),
							'type'    => 'error',
						)
					) );
				break;

				case 'ERR03':
					die( json_encode(
						array(
							'message' => __( 'Requested file is not found on server.', IGPBL ),
							'type'    => 'error',
						)
					) );
				break;

				default:
					die( json_encode(
						array(
							'message' => __( 'Cannot connect to InnoGears.com server.', IGPBL ),
							'type'    => 'error',
						)
					) );
				break;
			}
		}

		if ( $raw = $wp_filesystem->get_contents( $result ) ) {
			if ( $purchased = json_decode( $raw ) ) {
				// Remove temporary file
				$wp_filesystem->delete( $result );
			}
		}

		if ( ! $purchased ) {
			die( json_encode(
				array(
					'message' => sprintf( __( 'Cannot retrieve purchased edition. Returned error is: %1$s', IGPBL ), $raw ),
					'type'    => 'error',
				)
			) );
		}

		// Strip current edition from edition list
		foreach ( $purchased->editions as $k => $edition ) {
			if ( strcasecmp( $edition, $_REQUEST['edition'] ) == 0 ) {
				unset( $purchased->editions[ $k ] );
			}
		}

		if ( ! count( $purchased->editions ) ) {
			die( json_encode(
				array(
					'message' => __( 'You are currently using the highest edition available in your account.', IGPBL ),
					'type'    => 'error',
				)
			) );
		}

		// Finish
		die( json_encode( $purchased->editions ) );
	}

	/**
	 * Hook into WordPress.
	 *
	 * @return  void
	 */
	public static function hook() {
		// Register Ajax actions
		add_action( 'wp_ajax_ig-check-edition', array( __CLASS__, 'get_purchased_edition' ) );
	}
}
