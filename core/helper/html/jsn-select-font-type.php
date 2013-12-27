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
class IG_Pb_Helper_Html_Jsn_Select_Font_Type extends IG_Pb_Helper_Html {
	/**
	 * jsn select fonts element
	 * @param type $element
	 * @return string
	 */
	static function render( $element ) {
		$selected_value = $element['std'];
		$options = $element['options'];
		$output = '';
		$label = '';
		if ( is_array( $options ) && count( $options ) > 0 ) {
			$element = parent::get_extra_info( $element );
			$label   = parent::get_label( $element );

			$output = "<select id='{$element['id']}' name='{$element['id']}' class='jsn-fontFaceType {$element['class']}' data-selected='{$selected_value}' value='{$selected_value}' >";
			foreach ( $options as $key => $value ) {
				if ( ! is_numeric( $key ) ) {
					$option_value = $key;
				} else {
					$option_value = $value;
				}
				$selected = ( $option_value == $selected_value ) ? 'selected' : '';
				$output  .= "<option value='$option_value' $selected>$value</option>";
			}
			$output .= '</select>';
		}
		return parent::final_element( $element, $output, $label );
	}
}