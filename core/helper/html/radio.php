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
class IG_Pb_Helper_Html_Radio extends IG_Pb_Helper_Html {
	/**
	 * Radio
	 * @param type $element
	 * @return string
	 */
	static function render( $element ) {
		$element['class'] = isset( $element['class'] ) ? $element['class'] : 'radio inline';
		$element['input_type'] = 'radio';
		return IG_Pb_Helper_Shortcode::render_parameter( 'checkbox', $element );
	}
}