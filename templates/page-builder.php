<?php
/**
 * @version    $Id$
 * @package    IG Pagebuilder
 * @author     InnoThemes Team <support@innothemes.com>
 * @copyright  Copyright (C) 2012 innothemes.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.innothemes.com
 * Technical Support:  Feedback - http://www.innothemes.com
 */
wp_nonce_field( 'ig_builder', IGNONCE . '_builder' );
?>
<!-- Buttons bar -->
<div class="jsn-form-bar">
	<div id="status-switcher" class="btn-group" data-toggle="buttons-radio">
		<button type="button" class="switchmode-button btn active" id="status-on" data-title="<?php _e( 'Active Page Builder', IGPBL ) ?>"><?php _e( 'On', IGPBL ) ?></button>
		<button type="button" class="switchmode-button btn" id="status-off" data-title="<?php _e( 'Deactivate Page Builder', IGPBL ) ?>"><?php _e( 'Off', IGPBL ) ?></button>
	</div>
	<div id="mode-switcher" class="btn-group" data-toggle="buttons-radio">
		<button type="button" class="switchmode-button btn active" id="switchmode-compact"><?php _e( 'Compact', IGPBL ) ?></button>
		<button type="button" class="switchmode-button btn" id="switchmode-full"><?php _e( 'Full', IGPBL ) ?></button>
	</div>
</div>

<!-- Pagebuilder elements -->
<div class="jsn-section-content jsn-style-light" id="form-design-content">
	<div id="ig-pbd-loading" class="text-center"><i class="jsn-icon32 jsn-icon-loading"></i></div>
	<div id="form-container" class="jsn-layout">
<?php
global $post;
$pagebuilder_content = get_post_meta( $post->ID, '_ig_page_builder_content', true );
if ( ! empty( $pagebuilder_content ) ) {
	$builder = new IG_Pb_Helper_Shortcode();
	echo balanceTags( $builder->do_shortcode_admin( $pagebuilder_content ) );
}
?>

		<a href="javascript:void(0);" id="jsn-add-container" class="jsn-add-more"><i class="icon-plus"></i><?php _e( 'Add Row', IGPBL ) ?></a>
		<input type="hidden" id="ig-select-media" value="" />
		<input type="hidden" id="ig-tinymce-change" value="0" />
	</div>
	<div id="deactivate-msg" class="jsn-section-empty hidden">
		<p class="jsn-bglabel">
			<span class="jsn-icon64 jsn-icon-remove"></span>
			<?php _e( 'PageBuilder is currently off.', IGPBL ); ?>
		</p>
	</div>
</div>

<div id="branding" class="text-center">
	<?php _e( 'PageBuilder by', IGPBL ); ?> <a href="http://www.innogears.com" target="_blank">InnoGears.com</a>
</div>
<?php
include 'select-elements.php';
?>
<!--[if IE]>
<style>
    #jsn-quicksearch-field{
        height: 28px;
    }
</style>
<![endif]-->