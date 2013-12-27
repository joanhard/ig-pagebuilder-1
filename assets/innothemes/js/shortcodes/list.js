/**
 * @version    $Id$
 * @package    IGPGBLDR
 * @author     InnoThemes Team <support@innothemes.com>
 * @copyright  Copyright (C) 2012 InnoThemes.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.innothemes.com
 * Technical Support: Feedback - http://www.innothemes.com/contact-us/get-support.html
 */

/**
 * Custom script for List element
 */
( function ($)
{
	"use strict";

	$.IG_List = $.IG_List || {};

	$.IGSelectFonts	= $.IGSelectFonts || {};

	$.IG_List = function () {
		new $.IGSelectFonts();
        
		$('#param-font').on('change', function () {
			if ($(this).val() == 'inherit') {
				$('#param-font_face_type').val('standard fonts');
				$('.jsn-fontFaceType').trigger('change');
				$('#param-font_size_value_').val('');
				$('#param-font_style').val('bold');
				$('#param-color').val('#000000');
				$('#color-picker-param-color').ColorPickerSetColor('#000000');
				$('#color-picker-param-color div').css('background-color', '#000000');
			}
		});
	}

	$(document).ready(function () {
		$.IG_List();
	});

})(jQuery);