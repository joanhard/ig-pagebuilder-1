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
class IG_Pb_Helper_Html_Radio_Button_Group extends IG_Pb_Helper_Html {
	/**
	 * Radio Button group
	 * @param type $element
	 * @return string
	 */
	static function render( $element ) {
		$element = parent::get_extra_info( $element );
		$label   = parent::get_label( $element );

		$output = "<div class='btn-group ig-btn-group' data-toggle='buttons-radio'>";
		foreach ( $element['options'] as $key => $text ) {
			$active  = ( $key == $element['std'] ) ? 'active' : '';
			$output .= "<button type='button' class='btn btn-icon $active' data-value='$key'><i class='ig-btn-$key'>$text</i></button>";
		}
		$output .= '</div>';
		$output .= "<div class='radio-group ig-btn-radio hidden'>";
		foreach ( $element['options'] as $key => $text ) {
			$active  = ( $key == $element['std'] ) ? 'active' : '';
			$output .= "<input type='radio' name='{$element['id']}' id='{$element['id']}' value='$key'/>";
		}
		$output .= '</div>';

		return parent::final_element( $element, $output, $label );
	}
}