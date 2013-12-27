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

/**
 * Custom script for PromoBox element
 */
( function ($) {
	"use strict";

	$.IGSelectFonts	= $.IGSelectFonts || {};

    $.IGColorPicker = $.IGColorPicker || {};

    $.IG_PromoBox = $.IG_PromoBox || {};

	$.IG_PromoBox = function () {
		new $.IGSelectFonts();
        new $.IGColorPicker('#modalOptions .color-selector');

		$('#param-title_font').on('change', function () {
			if ($(this).val() == 'inherit') {
				$('#control-action-title').css('top', '421px');
			} else {
				$('#control-action-title').css('top', '390.5px');
			}
		});
		$('#param-title_font').trigger('change');
	}

	$(document).ready(function () {
		$('body').bind('ig_after_popover', function (e) {
			$.IG_PromoBox();
		});
	});

})(jQuery);