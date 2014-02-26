<?php

/**
 * @version    $Id$
 * @package    IG Pagebuilder
 * @author     InnoGears Team <support@www.innogears.com>
 * @copyright  Copyright (C) 2012 www.innogears.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.www.innogears.com
 * Technical Support:  Feedback - http://www.www.innogears.com
 */
/**
 * Premade layout functions
 */
if ( ! class_exists( 'IG_Pb_Helper_Layout' ) ) {

	class IG_Pb_Helper_Layout {

		/**
		 * save premade layouts file
		 * @param type $layout_name
		 * @param type $layout_content
		 */
		static function save_premade_layouts( $layout_name, $layout_content ) {
			$upload_dir = IG_Pb_Helper_Functions::get_wp_upload_folder( '/ig-pb-layout/user' );

			$layout_name = preg_replace( '/([\[\]])*/', '',  $layout_name );
			$file = $upload_dir . '/layout-' . time() . '.tpl';
			$fp = fopen( $file, 'w' );
			fwrite( $fp, "[ig_layout_tile $layout_name]" );
			fwrite( $fp, $layout_content );
			fclose( $fp );
		}

		/**
		 * get name of premade layouts file
		 */
		static function get_premade_layouts() {
			$path = IG_Pb_Helper_Functions::get_wp_upload_folder( '/ig-pb-layout' );

			$upload_dir = array();
			while ( $d = glob( $path . '/*', GLOB_ONLYDIR ) ) {
				$path .= '/*';
				foreach ( $d as $adir ) {
					$upload_dir[] = $adir;
				}
			}

			$files = $providers = array();
			$dirs = array_merge( $upload_dir, array( IG_PB_PREMADE_LAYOUT ) );
			foreach ( $dirs as $dir ) {
				$provider_id = self::get_provider_info( $dir, 'id' );

				// providerid - provider names
				$providers[$provider_id] = self::get_provider_info( $dir );

				// providerid - layouts
				foreach ( glob( $dir . '/*.tpl' ) as $filename ) {
					if ( ! isset ( $files[$provider_id] ) ) {
						$files[$provider_id] = array();
					}
					$files[$provider_id][basename( $filename )] = $filename;
				}
			}
			return array( 'providers' => $providers, 'files' => $files );
		}

		/**
		 * get name of premade layouts file
		 */
		static function show_premade_layouts() {
			$data = self::get_premade_layouts();

			ob_start();
			// providers
			foreach ( $data['providers'] as $provider_id  => $name ) {
				$selected = ( $provider_id != 'ig_pb_layout' ) ? '' : 'selected';
				echo balanceTags( "<option value='$provider_id' $selected>$name</option>" );
			}
			$providers = ob_get_clean();

			ob_start();
			// layouts
			foreach ( $data['files'] as $provider_id  => $layouts ) {
				foreach ( $layouts as $name => $path ) {
					$layout_name = self::extract_layout_data( $path, 'name' );
					if ( empty ( $layout_name ) ) {
						continue;
					}
					$path_parts = pathinfo( $path );
					$dir = $path_parts['dirname'];
					$content    = "<textarea class='hidden'>" . self::extract_layout_data( $path, 'content' ) . '</textarea>';
					$class      = ( $provider_id != 'ig_pb_layout' ) ? 'hidden' : '';
					if ( $provider_id != 'user_layout' ) {
						$thumbnail = IG_PB_PREMADE_LAYOUT_URI . '/default.png';
						$strip_ext = str_replace( '.tpl', '', $name );
						// get thumbnail of template
						$images_ext = array( 'png', 'gif', 'jpg', 'jpeg' );
						foreach ( $images_ext as $ext ) {
							if ( file_exists( $dir . "/$strip_ext.$ext" ) ) {
								$thumbnail = "$strip_ext.$ext";
								$thumbnail = self::get_uri( $dir, $thumbnail );
							} else if ( file_exists( $dir . "/$strip_ext." . strtoupper( $ext ) ) ) {
								$thumbnail = "$strip_ext." . strtoupper( $ext );
								$thumbnail = self::get_uri( $dir, $thumbnail );
							}
						}

						echo balanceTags(
							"<li class='jsn-item $provider_id $class' data-type='$provider_id' >
								<a class='template-item-thumb premade-layout-item' data-id='$name' href='javascript:;'>
									<span class='thumbnail'>
										<img src='$thumbnail' alt='$layout_name' align='center'>
									</span>
									<span>$layout_name</span>
									$content
								</a>
							</li>"
						);
					} else {
						echo balanceTags(
							"<li class='jsn-item $provider_id $class' data-type='$provider_id' >
								<button data-id='$name' class='premade-layout-item btn'>$layout_name $content</button>

							</li>"
						);
					}
				}
			}
			?>
			<?php
			$files = ob_get_clean();
			return array( 'providers' => $providers, 'files' => $files );
		}

		/**
		 * Get uri from dir path
		 *
		 * @param type $dir
		 * @param type $file
		 * @return type
		 */
		static function get_uri( $dir, $file ) {
			if ( $dir == IG_PB_PREMADE_LAYOUT ) {
				$uri = IG_PB_PREMADE_LAYOUT_URI;
			} else {
				$path_parts = pathinfo( $dir );
				$uri = IG_Pb_Helper_Functions::get_wp_upload_url( '/ig-pb-layout/' ) . $path_parts['basename'];
			}
			return "$uri/$file";
		}

		/**
		 * get content of premade layouts file, prinrt as template
		 */
		static function print_premade_layouts() {
			$files = self::get_premade_layouts();
			foreach ( $files as $provider  => $layouts ) {
				foreach ( $layouts as $name => $path ) {
					$content = self::extract_layout_data( $path, 'content' );
					echo balanceTags( "<script type='text/html' id='tmpl-layout-$name'>\n$content\n</script>\n" );
				}
			}
		}

		/**
		 * Read file line by line
		 *
		 * @param type $path
		 * @return type
		 */
		static function extract_layout_data( $path, $data ){
			$fp = @fopen( $path, 'r' );
			if ( $fp ) {
				$contents = fread( $fp, filesize( $path ) );
				$pattern  = '/\[ig_layout_tile\s([^\]]+)\]/';
				fclose( $fp );
				if ( $data == 'name' ) {
					preg_match( $pattern, $contents, $matches );
					return $matches[1];
				} else if ( $data == 'content' ) {
					return preg_replace( $pattern, '', $contents );
				}
			}
		}

		/**
		 * Get provider id of layout folder: Search for provider.info file to get provider name
		 * @param type $dir
		 */
		static function get_provider_info( $dir, $info = 'name' ) {
			if ( $info == 'id' ) {
				$path_parts = pathinfo( $dir );
				return ( ( $dir == IG_PB_PREMADE_LAYOUT ) ? 'ig_pb' : $path_parts['basename'] ) . '_layout';
			}
			if ( $dir == IG_PB_PREMADE_LAYOUT ) {
				return __( 'IG Templates', IGPBL );
			}

			// Get provider info from xml file
			$path = $dir . '/provider.xml';
			if ( file_exists( $path ) ) {
				$dom_object = new DOMDocument();
				if ( $dom_object->load( $path ) ) {
					$node = $dom_object->getElementsByTagName( $info );
					if ( $node ) {
						return $node->item( 0 )->nodeValue;
					}
				}
			}
			return __( 'Your Templates', IGPBL );
		}

		/**
		 * Import layout from folder
		 *
		 * @param type $file
		 */
		static function import( $dir ) {
			$provider_name    = self::get_provider_info( $dir, 'name' );
			$folder_to_create = ( $provider_name == __( 'Your Templates', IGPBL ) ) ? 'user' : sanitize_title( $provider_name );
			$new_dir = IG_Pb_Helper_Functions::get_wp_upload_folder( '/ig-pb-layout/' . $folder_to_create, false );
			// if this is new provider, rename tmp folder to provider name
			if ( ! is_dir( $new_dir ) ) {
				return rename( $dir, $new_dir );
			}
			// move templates file & thumbnail to existed folder of provider
			else {
				foreach ( glob( $dir . '/*.*' ) as $filename ) {
					$path_parts = pathinfo( $filename );
					$name = $path_parts['basename'];
					$ext = $path_parts['basename'];
					// only copy image & template file
					if ( in_array( strtolower( $ext ), array( 'png', 'gif', 'jpg', 'jpeg', 'tpl' ) ) ) {
						copy( $filename, "$new_dir/$name" );
					}
				}
				return true;
			}
			return false;
		}

	}

}
