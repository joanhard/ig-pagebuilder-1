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
class IG_Pb_Helper_Html_Slider extends IG_Pb_Helper_Html {
	/**
	 * Horizonal slider to select a numeric value
	 * @param type $element
	 * @return type
	 */
	static function render( $element ) {
		$element = parent::get_extra_info( $element );
		$label   = parent::get_label( $element );
		$std_max = empty ( $element['std_max'] ) ? 100 : $element['std_max'];
		$output  = '<script>
			( function ($ ) {
				$( document ).ready( function ()
				{
					$( "#' . $element['id'] . '_slider" ).slider({
						range: "min",
						value: ' . $element['std'] .',
						min: 1,
						max: ' . $std_max .',
						slide: function ( event, ui ) {
							$( "#' . $element['id'] . '" ).val( ui.value ).change();
						}
					});
				});
			})( jQuery )
		</script>';
		$output .= '<div id="' . $element['id'] . '_slider" class="' . $element['class'] . '" ></div>';
		$output .= '<input type="hidden" id="' . $element['id'] . '" value="' . $element['std'] . '" />';

		return parent::final_element( $element, $output, $label );
	}
}