<?php
/**
 * @version	$Id$
 * @package	IG Pagebuilder
 * @author	 InnoGears Team <support@www.innogears.com>
 * @copyright  Copyright (C) 2012 www.innogears.com. All Rights Reserved.
 * @license	GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.www.innogears.com
 * Technical Support:  Feedback - http://www.www.innogears.com
 */

?>
<div class="jsn-bootstrap">
<?php
$options = array( 'ig_pb_cache_status', 'ig_pb_boostrap_js', 'ig_pb_boostrap_css' );
// submit handle
if ( ! empty ( $_POST ) ) {
	foreach ( $options as $key ) {
		$value = ! empty( $_POST[$key] ) ? 'enable' : 'disable';
		update_option( $key, $value );
	}

	unset( $_POST );
	IG_Pb_Helper_Functions::alert_msg( array( 'success', __( 'Your settings are saved successfully', IGPBL ) ) );
}
// get saved options value
foreach ( $options as $key ) {
	$$key = get_option( $key, 'enable' );
}
// check/select saved options
function ig_pb_show_check( $value, $compare, $check ) {
	echo esc_attr( ( $value == $compare ) ? $check : '' );
}
?>

<div class="jsn-section-content jsn-style-light jsn-bootstrap">
    <form action="" method="POST">
        <div class="container">
            <div class="pull-left ig-pb-label">
                <label class="control-label"><?php _e( 'IG Cache', IGPBL ); ?></label>
            </div>
            <div class="pull-left">
                <select name="ig_pb_cache_status">
                    <option value="enable" <?php ig_pb_show_check( $ig_pb_cache_status, 'enable', 'selected' ); ?>><?php _e( 'Enable', IGPBL ); ?></option>
                    <option value="" <?php ig_pb_show_check( $ig_pb_cache_status, 'disable', 'selected' ); ?>><?php _e( 'Disable', IGPBL ); ?></option>
                </select>
                <button class="btn" id="ig-pb-clear-cache"><?php _e( 'Clear cache now', IGPBL ); ?></button>
                <div class="hidden layout-loading"><i class="jsn-icon16 jsn-icon-loading"></i></div>
                <div class="hidden layout-message alert"></div>
            </div>
        </div>
        <div class="container">
            <div class="pull-left ig-pb-label">
                <label class="control-label"><?php _e( 'Load Boostrap(js) on frontend', IGPBL ); ?></label>
            </div>
            <div class="pull-left">
                <input type="checkbox" name="ig_pb_boostrap_js" value="1" <?php ig_pb_show_check( $ig_pb_boostrap_js, 'enable', 'checked' ); ?>>
            </div>
        </div>
        <div class="container">
            <div class="pull-left ig-pb-label">
                <label class="control-label"><?php _e( 'Load Boostrap(css) on frontend', IGPBL ); ?></label>
            </div>
            <div class="pull-left">
                <input type="checkbox" name="ig_pb_boostrap_css" value="1" <?php ig_pb_show_check( $ig_pb_boostrap_css, 'enable', 'checked' ); ?>>
            </div>
        </div>
        <div class="container" style="margin-top:20px;">
            <input type="submit" name="submit" id="submit" class="btn btn-primary" value="Save Changes">
        </div>
    </form>
</div>
</div>

<?php
// Load inline script initialization
$script = '
		new IG_Pb_Settings({
			ajaxurl: "' . admin_url( 'admin-ajax.php' ) . '",
			_nonce: "' . wp_create_nonce( IGNONCE ) . '",
			button: "ig-pb-clear-cache",
			button: "ig-pb-clear-cache",
			loading: $("#ig-pb-clear-cache").next(".layout-loading"),
			message: $("#ig-pb-clear-cache").parent().find(".layout-message"),
		});';
IG_Init_Assets::inline( 'js', $script );

$style = '
		.jsn-bootstrap { margin-top: 30px; }
        #ig-pb-clear-cache { margin-top: -9px; margin-left: 6px; }
        .ig-pb-label{ margin-top: 5px; margin-right: 20px; width: 220px; }
        ';
IG_Init_Assets::inline( 'css', $style );