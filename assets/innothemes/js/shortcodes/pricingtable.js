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
 * Custom script for pricing table item element
 */
(function ($) {
	'use strict';
	
	$.IG_PRTBL_BuildAttrsLabel = $.IG_PRTBL_BuildAttrsLabel || {};
	
	$.IG_PRTBL_BuildAttrsLabel = function () {
		var common_value = $('#param-prtbl_common_label_json').val();
		var arr_items = [];
		if ( common_value ) {
			arr_items = common_value.split('__#__');
		}
		var ul_html = '';
		if ( arr_items.length > 0 ) {
			for ( var i = 0; i < arr_items.length; i++ ) {
				if ( arr_items[i] ) {
					var shortcode = arr_items[i];
					shortcode = shortcode.replace(/--quote--/g, '"');
					shortcode = shortcode.replace(/--open_square--/g, '[');
					shortcode = shortcode.replace(/--close_square--/g, ']');
					
					var title = '';
					var arr_title = [];
					arr_title = shortcode.match(/prtbl_item_item_title="[^*!"]+"/g);
					if ( arr_title ) {
						title 	= arr_title[0].replace('prtbl_item_item_title="', '');
						title		= title.replace('"', '');
					}
					if ( ! title ) {
						title = Ig_Translate.no_title;
					}
					
					var html_element = Ig_Translate.prtbl_item_cell_label;
					html_element = html_element.replace(/IG_SHORTCODE_CONTENT/g, shortcode);
					html_element = html_element.replace(/IG_OPTIONS_ATTRIBUTES/g, Ig_Translate.attribute);
					html_element = html_element.replace(/IG_CONTENT/g, title);
					
					ul_html += html_element;
				}
			}
		}
		$('#param-prtbl_attr_label #group_elements').html(ul_html);
	}
	
	$.IG_RandomChars = function () {
		var randomStrLength = 8,
	    pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
	    randomStr = '';

		for (var i = 0; i < randomStrLength; i++) {
		     var randomChar = pool.charAt(Math.floor(Math.random() * pool.length));
		     randomStr += randomChar;   
		}
		
		return randomStr;
	}
	
	$(document).ready(function () {
		/* build package attributes label item */
		$.IG_PRTBL_BuildAttrsLabel();
		
		/* disable reorder */
		$('body').bind('on_remove_handle_reorder', function () {
			$( "#parent-param-prtbl_attr_label #group_elements" ).sortable( "disable" );
		});
		
		$('body').bind('on_update_attr_label_common', function () {
			/*remove other data*/
			$('#param-prtbl_attr_label .shortcode-preview-container').remove();
			
			/* build attribute label common json */
			var string_sub_items = '';
			$('#param-prtbl_attr_label textarea[data-sc-info="shortcode_content"]').each(function () {
				var html = $(this).html();
				if ( html.search('__default_id__') ) {
					var rand = $.IG_RandomChars();
					html = html.replace( /__default_id__/g, rand );
				}
				$(this).html(html);
				html = html.replace(/"/g, '--quote--');
				html = html.replace(/\[/g, '--open_square--');
				html = html.replace(/\]/g, '--close_square--');
				string_sub_items += html + '__#__';
			});
			$('#param-prtbl_common_label_json').val(string_sub_items);
		});
		
		$('body').bind('on_after_delete_element', function() {
			$('body').trigger('on_update_attr_label_common');
		});
		
		/* bind event clone element item */
        $('body').bind('on_clone_element_item', function (e, obj_return) {
        	if (obj_return.obj_element) {
        		var el_html = obj_return.obj_element.html();
        		var random = $.IG_RandomChars();
        		var result_html = el_html.replace(/item_prtbl_unique_id="[a-zA-Z]+"/g, 'item_prtbl_unique_id="' + random + '"');
        		obj_return.obj_element.html(result_html);
        	}
        });
        
        $('body').bind('add_exclude_jsn_item_class', function () {
        	$("#param-prtbl_attr_label #group_elements [name^='shortcode_content']").each(function () {
        		if ( ! $(this).hasClass( 'exclude_gen_shortcode' ) ) {
        			$(this).addClass('exclude_gen_shortcode');
        		}
        	});
        });
	});
})(jQuery);