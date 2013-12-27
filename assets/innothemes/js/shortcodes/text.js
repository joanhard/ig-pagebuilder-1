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
 * Custom script for Textbox element
 */
( function ($) {
    "use strict";

    $.IGSelectFonts = $.IGSelectFonts || {};

    $.IGColorPicker = $.IGColorPicker || {};

    $.IG_Text = $.IG_Text || {};

    $.IG_Text = function () {
        new $.IGSelectFonts();
        new $.IGColorPicker('#modalOptions .color-selector');
    }

    $(document).ready(function () {
        $.IG_Text();
    });

})(jQuery)