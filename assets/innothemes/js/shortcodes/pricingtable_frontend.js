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
		if (typeof($.fn.tipsy) == "function") {
			$(".ig-prtbl-tipsy").tipsy({
	            gravity: "n",
	            fade: true
	        });
		}
		if (typeof($.fancybox) == "function") {
			$(".ig-prtbl-button-fancy").fancybox({
                "width"		: "75%",
				"height"	: "75%",
	            "autoScale"	: false,
	            "transitionIn"	: "elastic",
	            "transitionOut"	: "elastic",
	            "type"		: "iframe"
	        });
		}
	});
	
})(jQuery);