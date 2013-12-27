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
class IG_Pb_Helper_Html_Filter_List extends IG_Pb_Helper_Html {
	/**
	 * Horizonal list of filter options
	 * @param type $data
	 * @param type $id
	 * @return string
	 */
	static function render( $data, $id ) {
		$html = "<ul id='filter_$id' class='nav nav-pills elementFilter'>";
		foreach ( $data as $idx => $value ) {
			$active = ( $idx == 0 ) ? 'active' : '';
			$html  .= "<li class='$active'><a href='#' class='" . str_replace( ' ', '_', $value ) . "'>" . ucfirst( $value ) . '</a></li>';
		}
		$html .= '</ul>';
		return $html;
	}
}