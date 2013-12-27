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
class IG_Pb_Helper_Html_Preview extends IG_Pb_Helper_Html {
	/**
	 * Preview Box of shortcode
	 * @return type
	 */
	static function render() {
		$hide_preview = __( 'Hide preview', IGPBL );
		$show_preview = __( 'Live preview', IGPBL );
		return "<div class='control-group'>
		<div id='preview_container'>
		<div id='previewToggle'><i id='hide_preview' title='$hide_preview' class='icon-delete'></i><div id='show_preview' class='thumbnail jsn-padding-medium jsn-text-center hidden'>$show_preview</div></div>
		<div id='ig_overlay_loading' class='jsn-overlay jsn-bgimage image-loading-24'></div>
		<iframe scrolling='no' id='shortcode_preview_iframe' name='shortcode_preview_iframe' class='shortcode_preview_iframe' ></iframe>
		</div></div>";
	}
}