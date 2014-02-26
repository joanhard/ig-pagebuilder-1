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
 * Product info class.
 *
 * @package  IG_Library
 * @since    1.0.0
 */
class IG_Product_Info {
	/**
	 * Link to get product information.
	 *
	 * @var  string
	 */
	protected static $product_info = 'http://www.innogears.com/lightcart/versioning/ig_plugins.php';

	/**
	 * Products information.
	 *
	 * @var  array
	 */
	protected static $products = array();

	/**
	 * Method to get product info.
	 *
	 * Product info will be returned in the following format:
	 *
	 * array(
	 *     'Name'             => 'IG Sample',
	 *     'Description'      => 'Sample plugin that demonstrates the functionality of IG Library (InnoGears’s shared library). By innogears.com.',
	 *     'Version'          => '1.0.0',
	 *     'Edition'          => 'free',
	 *     'Identified_Name'  => 'ig-sample',
	 *     'Available_Update' => false,
	 * )
	 *
	 * @param   string   $plugin        Path to plugin main file.
	 * @param   boolean  $check_update  Check for available product update.
	 *
	 * @return  array
	 */
	public static function get( $plugin, $check_update = false ) {
		if ( ! isset( self::$products[ $plugin ] ) ) {
			if ( $data = get_plugin_data( $plugin ) ) {
				// Prepare plugin name
				$name = str_replace( ' ', '_', $data['Name'] );

				// Get extra info from constant
				foreach ( array( 'Edition' => 'free', 'Identified_Name' => null, 'Dependency' => null ) as $key => $default ) {
					// Generate constant name
					$const = strtoupper( "{$name}_{$key}" );

					if ( ! defined( $const ) && @is_file( dirname( $plugin ) . '/defines.php' ) ) {
						include_once dirname( $plugin ) . '/defines.php';
					}

					// Get constant value
					if ( defined( $const ) ) {
						eval( '$const = ' . $const . ';' );
					} else {
						$const = $default;
					}

					// Store extra info
					$data[ $key ] = $const;
				}

				if ( $check_update ) {
					// Define current product info
					$products[ $data['Identified_Name'] ] = array(
						'version' => $data['Version'],
						'edition' => $data['Edition'],
					);

					if ( ! empty( $data['Dependency'] ) ) {
						$plugin_dir   = WP_PLUGIN_DIR;
						$dependencies = explode( ',', $data['Dependency'] );

						foreach ( $dependencies as $name ) {
							$name       = str_replace( '_', '-', trim( $name ) );
							$dependency = null;

							if ( @is_file( "{$plugin_dir}/{$name}.php" ) ) {
								$dependency = self::get( "{$plugin_dir}/{$name}.php" );
							}

							if ( ! $dependency && @is_file( "{$plugin_dir}/{$name}/main.php" ) ) {
								$dependency = self::get( "{$plugin_dir}/{$name}/main.php" );
							}

							if ( ! $dependency && @is_file( "{$plugin_dir}/{$name}/{$name}.php" ) ) {
								$dependency = self::get( "{$plugin_dir}/{$name}/{$name}.php" );
							}

							if ( $dependency ) {
								$products[ $dependency['Identified_Name'] ] = array(
									'version' => $dependency['Version'],
									'edition' => $dependency['Edition'],
								);
							}
						}
					}

					// Verify checking results
					if ( $results = self::check( $products ) ) {
						foreach ( $results as $key => $result ) {
							if ( ! $result ) {
								unset( $results[ $key ] );
							}
						}

						if ( ! count( $results ) ) {
							$results = false;
						}
					}

					$data['Available_Update'] = $results;
				}
			}

			self::$products[ $plugin ] = $data;
		}

		return self::$products[ $plugin ];
	}

	/**
	 * Method to check for available product update / product edition.
	 *
	 * @param   array    $products      Current product and dependency info, e.g. array( 'ig-sample' => '1.0.0', 'ig-dependency' => '1.0.0' )
	 * @param   array    $product_info  Latest product info from InnoGears server
	 * @param   array    $results       Checking results.
	 * @param   boolean  $get_editions  Whether to check for available product update or product edition?
	 *
	 * @return  array
	 */
	public static function check( $products, $product_info = array(), $results = array(), $get_editions = false ) {
		global $wp_filesystem;

		// Contact InnoGears server for latest product info
		if ( ! @count( $product_info ) ) {
			// Get InnoGears product info
			$result = download_url( self::$product_info );

			if ( ! is_wp_error( $result ) ) {
				if ( $product_info = $wp_filesystem->get_contents( $result ) ) {
					if ( $product_info = json_decode( $product_info ) ) {
						// Remove temporary file
						$wp_filesystem->delete( $result );
					}
				}
			}

			if ( ! $product_info ) {
				return false;
			}
		}

		// Get the latest product version
		is_array( $products ) || $products = (array) $products;
		is_array( $results  ) || $results  = (array) $results;

		foreach ( $products as $product => $current ) {
			if ( ! isset( $results[ $product ] ) ) {
				foreach ( $product_info->items as $item ) {
					if ( isset( $item->items ) ) {
						// Check recursively
						$results = self::check( array( $product => $current ), $item, $results );

						continue;
					}

					if ( isset( $item->identified_name ) && $item->identified_name == $product ) {
						// Product found
						$results[ $product ] = $item;

						break;
					}
				}

				// Does latest product info found?
				if ( isset( $results[ $product] ) && is_object( $results[ $product ] ) ) {
					if ( ! $get_editions ) {
						// Check if product has newer version?
						if ( version_compare( $results[ $product ]->version, $current['version'], '>' ) ) {
							$authentication = false;

							// Check if authentication is required
							if ( isset( $results[ $product ]->edition ) && strcasecmp( $results[ $product ]->edition, $current['edition'] ) == 0 ) {
								if ( isset( $results[ $product ]->authentication ) ) {
									$authentication = $results[ $product ]->authentication;
								}
							} elseif ( isset( $results[ $product ]->editions ) ) {
								foreach ( $results[ $product ]->editions as $edition ) {
									if ( strcasecmp( $edition->edition, $current['edition'] ) == 0 ) {
										$authentication = $edition->authentication;

										break;
									}
								}
							}

							$results[ $product ] = array(
								'name'           => $results[ $product ]->name,
								'version'        => $results[ $product ]->version,
								'edition'        => $current['edition'],
								'authentication' => $authentication,
							);
						} else {
							$results[ $product ] = false;
						}
					} elseif ( isset( $results[ $product ]->editions ) ) {
						$results[ $product ] = $results[ $product ]->editions;
					}
				}
			}
		}

		return $results;
	}
}
