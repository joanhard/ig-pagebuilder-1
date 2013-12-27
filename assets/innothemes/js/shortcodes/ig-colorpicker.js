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

(function ($) {
    "use strict";

    $.IGColorPicker = function (selector) {
        this.init(selector);
    };

    $.IGColorPicker.prototype = {
        init: function (selector) {
            if ( ! selector )
                return false;

            $( selector ).each(function () {
                var self	= $(this);
                var colorInput = self.siblings('input').last();
                var inputId 	= colorInput.attr('id');
                var inputValue 	= inputId.replace(/_color/i, '') + '_value';
                if ($('#' + inputValue).length){
                    $('#' + inputValue).val($(colorInput).val());
                }

                self.ColorPicker({
                    color: $(colorInput).val(),
                    onShow: function (colpkr) {
                        $(colpkr).fadeIn(500);
                        return false;
                    },
                    onHide: function (colpkr) {
                        $(colpkr).fadeOut(500);
                        $.HandleSetting.shortcodePreview();
                        return false;
                    },
                    onChange: function (hsb, hex, rgb) {
                        $(colorInput).val('#' + hex);

                        if ($('#' + inputValue).length){
                            $('#' + inputValue).val('#' + hex);
                        }
                        self.children().css('background-color', '#' + hex);
                    }
                });
            });
        }
    }

})(jQuery);