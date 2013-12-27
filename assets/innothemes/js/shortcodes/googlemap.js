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
( function ($) {
	"use strict";

	$.GoogleMapElement	= $.GoogleMapElement || {};

	$.GoogleMapElement = function(iframe) {
   		var html_options 	= '<option value="" > - '+Ig_Translate.shortcodes.googlemap1+' - </option>';
   		if ( $('#shortcode_name').val() == 'ig_googlemap' ) {
   			var select_destination 	= $(iframe).contents().find('#select_param-gmi_destination');
   			var exclude_id			= $(iframe).contents().find('#param-gmi_id').val();
   			var currentValue		= $(iframe).contents().find('#param-gmi_destination').val();

   			$('.jsn-item textarea[data-sc-info="shortcode_content"]').each(function () {
   	    		var html_str 	= $(this).html();
   	    		var match 		= html_str.match(/gmi_id="[^*!"]+"/g);
   	    		var title 		= html_str.match(/gmi_title="[^*!"]+"/g);
   	    		var key 		= match[0].replace('"', '');
   	    		key				= key.replace('"', '');
   	    		key 			= key.replace('gmi_id=', '');
   	    		var value 		= title[0].replace('"', '');
   	    		value 			= value.replace('"', '');
   	    		value 			= value.replace('gmi_title=', '');
   	    		if ( exclude_id != '' && exclude_id == key ) {
   	    			html_options	+= '';
   				} else if ( currentValue ) {
   					var current_selected = '';
   	    			if ( currentValue == key ) {
   	    				current_selected 	= 'selected="selected"';
   	    			}

   	    			html_options 	+= '<option value="' + key + '" ' + current_selected + ' >' + value + '</option>';
   				} else {
   					html_options 	+= '<option value="' + key + '" >' + value + '</option>';
   				}

   	    	});
   			if ( html_options ) {
    			select_destination.html( html_options );
    		}

    		$(iframe).contents().find('#select_param-gmi_destination').on( 'change', function () {
    			$(iframe).contents().find('#param-gmi_destination').val($(this).val());
            } );
       		$(iframe).contents().find('#select_param-gmi_destination').trigger('change');

       		$(iframe).contents().find('#param-gmidesccontent').on( 'change', function () {
       			$(iframe).contents().find('#param-gmi_desc').val($(this).val());
            } );
       		$(iframe).contents().find('#param-gmidesccontent').trigger('change');
   		}
    }

	$(document).ready(function () {
		$('body').bind('ig_submodal_load', function (e, iframe) {
			$.GoogleMapElement(iframe);
		});
	});

})(jQuery);