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

(function ($) {
	
	$(document).ready(function () {
		if ( typeof($.fn.lazyload) == "function" ) {
			$(".image-scroll-fade").lazyload({
				effect       : "fadeIn"
			});	
		}
		if (typeof($.fancybox) == "function") {
			$(".ig-image-fancy").fancybox({
				"autoScale"	: true,
				"transitionIn"	: "elastic",
				"transitionOut"	: "elastic",
				"type"		: "iframe"
			});
		}
	});
	
})(jQuery);