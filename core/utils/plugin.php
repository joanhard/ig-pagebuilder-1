<?php
/**
 * IG Pagebuilder Activate & Deactivate
 *
 * Show confirmation page before doing deactivation or go back
 *
 * @author		InnoGears Team <support@www.innogears.com>
 * @package		IGPGBLDR
 * @version		$Id$
 */
global $free_sc_fname;
$free_sc_fname = 'ig-shortcodes-free';
/*------------------------------------------------------
	Activate
------------------------------------------------------*/
register_activation_hook( IG_PB_FILE, 'ig_pb_activate' );
function ig_pb_activate() {
	global $free_sc_fname;
	$free_sc_zip = plugin_dir_path( IG_PB_FILE ) . $free_sc_fname . '.zip';
	if ( ! ( file_exists( $free_sc_zip ) ) ){
		$error = 404;
		return;
	}
	$zip = new ZipArchive;
	if ( $zip->open( $free_sc_zip ) === TRUE ) {
		$free_sc_folder = WP_PLUGIN_DIR . "/$free_sc_fname";
		if ( file_exists( $free_sc_folder ) ) {
			// rename older folder
			rename( $free_sc_folder, $free_sc_folder . '-old' );
		}
		// extract to plugin folder
		$zip->extractTo( $free_sc_folder );
		$zip->close();

		// remove zip file
		unlink( $free_sc_zip );

		set_transient( 'ig_pb_installed', 1, 2 * MINUTE_IN_SECONDS );
		$error = 0;
	} else {
		$error = -1;
	}
}

add_action( 'admin_init', 'ig_pb_activate_plugin' );
// active extracted plugin
function ig_pb_activate_plugin() {
	global $pagenow;
	global $free_sc_fname;
	if ( $pagenow == 'plugins.php' && get_transient( 'ig_pb_installed' ) !== false ) {
		if ( ! is_plugin_active( $free_sc_fname . '/main.php' ) ) {
			activate_plugin( $free_sc_fname . '/main.php' );
			// reset
			delete_transient( 'ig_pb_installed' );
			remove_action( 'admin_init', __FUNCTION__ );
		}
	}
}

/*------------------------------------------------------
	Deactivate
------------------------------------------------------*/
register_deactivation_hook( IG_PB_FILE, 'ig_pb_deactivate' );
// in case: select some/all plugins then Deactivate
add_action( 'admin_init', 'ig_pb_deactivate' );

function ig_pb_deactivate() {
	global $pagenow;
	if ( $pagenow == 'plugins.php' ) {
		$deactivate_action = false;
		$ig_pb_plugin = 'ig-pagebuilder/ig-pagebuilder.php';
		if ( ! empty( $_REQUEST['action'] ) && in_array( $_REQUEST['action'], array( 'deactivate-selected', 'deactivate' ) ) ) {
			$action = $_REQUEST['action'];
			if ( ( $action == 'deactivate' && $_REQUEST['plugin'] == $ig_pb_plugin ) || ( $action == 'deactivate-selected' && in_array( $ig_pb_plugin, $_REQUEST['checked'] ) ) ) {
				$deactivate_action = true;
			}
		}

		if ( $deactivate_action ) {
			$ig_action  = 'ig_deactivate';
			$plugin_url = admin_url( 'plugins.php' );
			// check whether delete only Ig Pagebuilder OR Bulk deactivate plugins
			$deactivate_one = isset( $_POST['action'] ) ? false : true;

			// original WP request
			if ( ! isset( $_REQUEST['ig_wpnonce'] ) && ! isset( $_REQUEST['ig_back'] ) ) {
				// create ig_nonce
				$ig_nonce = wp_create_nonce( $ig_action );
				$method   = $deactivate_one ? 'GET' : 'POST';
				if ( $deactivate_one )
					$back_btn = "<a href='$plugin_url' class='button button-large'>" . __( 'No, take me back', IGPBL ) . '</a>';
				else {
					$back_btn = "<input type='submit' name='ig_back' class='button button-large' value='" . __( 'No, take me back', IGPBL ) . "'>";
				}
				$form   = " action='{$plugin_url}' method='$method' ";
				$fields = '';

				foreach ( $_REQUEST as $key => $value ) {
					if ( ! is_array( $value ) ) {
						$fields .= "<input type='hidden' name='$key' value='$value' />";
					} else {
						foreach ( $value as $p ) {
							$fields .= "<input type='hidden' name='{$key}[]' value='$p' />";
						}
					}
				}
				$fields .= "<input type='hidden' name='ig_wpnonce' value='$ig_nonce' />";
				// show message
				ob_start();
				?>
				<p>
					<?php _e( 'After deactivating, all content built with PageBuilder will be parsed to plain HTML code. Are you sure you want to deactivate PageBuilder plugin?', IGPBL ); ?>
				</p>
				<center>
					<form <?php echo balanceTags( $form ); ?>>
						<?php echo balanceTags( $fields ); ?>
						<input type="submit" name="ig_deactivate" class="button button-large" value="<?php _e( 'Yes, deactivate plugin', IGPBL ); ?>">
						<?php echo balanceTags( $back_btn ); ?>
					</form>
				</center>
				<?php
				$message = ob_get_clean();
				_default_wp_die_handler( $message );

				exit;
			} else {
				$ig_nonce = esc_sql( $_REQUEST['ig_wpnonce'] );
				$nonce    = wp_verify_nonce( $ig_nonce, $ig_action );

				if ( ! in_array( $nonce, array( 1, 2 ) ) ) {
					_default_wp_die_handler( __( 'Nonce is invalid!', IGPBL ) );
					exit;
				}

				// No, take me back
				if ( isset($_REQUEST['ig_back']) ) {
					// remove Ig Pagebuilder from the checked list
					if ( ($key = array_search( $ig_pb_plugin, $_REQUEST['checked'] ) ) !== false ) {
						unset( $_REQUEST['checked'][$key] );
					}

					// Overwrite list of checked plugins to deactivating
					$_POST['checked'] = $_REQUEST['checked'];
				}
				// deactivate Ig Pagebuilder
				else {
					global $wpdb;
					// update post content = value of '_ig_html_content', deactivate pagebuilder
					$meta_key1 = 1;
					$meta_key2 = '_ig_html_content';
					$meta_key3 = '_ig_deactivate_pb';
					$wpdb->query(
						$wpdb->prepare(
							"
							UPDATE		$wpdb->posts p
							LEFT JOIN	$wpdb->postmeta p1
										ON p1.post_id = p.ID
							LEFT JOIN	$wpdb->postmeta p2
										ON p2.post_id = p.ID
							SET			post_content = p1.meta_value, p2.meta_value = %d
							WHERE		p1.meta_key = %s
										AND p2.meta_key = %s
							",
							$meta_key1,
							$meta_key2,
							$meta_key3
						)
					);
					// delete pagebuilder content
					ig_pb_delete_meta_key( array( '_ig_page_builder_content', '_ig_page_active_tab' ) );

					do_action( 'ig_pb_deactivate' );
				}
			}
		}
	}
}