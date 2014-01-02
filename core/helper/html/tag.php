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
class IG_Pb_Helper_Html_Tag extends IG_Pb_Helper_Html {
	/**
	 * Tag
	 * @param type $element
	 * @return string
	 */
	static function render( $element ) {
		$element = parent::get_extra_info( $element );
		$label = parent::get_label( $element );
		$element['class'] = ( $element['class'] ) ? $element['class'] . ' select2' : 'select2';
		$output = "<input type='hidden' value='{$element['std']}' id='{$element['id']}' class='{$element['class']}' data-share='ig_share_data' DATA_INFO />";

		add_filter( 'ig_pb_assets_enqueue_modal', array( __CLASS__, 'this_assets_enqueue_modal' ) );

		return parent::final_element( $element, $output, $label );
	}

	// enqueue custom assets
	static function this_assets_enqueue_modal( $scripts ){
		$scripts = array_merge( $scripts, array( 'ig-pb-jquery-select2-js', ) );

		return $scripts;
	}
}