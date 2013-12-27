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
 * Custom script for QRCode element
 */
(function ($) {
	
	"use strict";
	
	$.IG_QRCode = $.IG_QRCode || {};
	
	$.IG_QRCode = function () {
		// QR Code element process
        $('#param-qr_content_area').on('change', function () {
        	var html = $(this).val();
        	html = html.replace(/&/g, '');
        	html = html.replace(/$/g, '');
        	html = html.replace(/#/g, '');
        	var encode_html = html.replace(/"/g, '<ig_quote>');
        	$('#param-qr_content_area').val(html.substring(0, 1200));
        	$('#param-qr_content').val(encode_html);
        });
        $('#param-qr_content_area').trigger('change');
	}
	
	$(document).ready(function () {
		$.IG_QRCode();
	});
	
})(jQuery)