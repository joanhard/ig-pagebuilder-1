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
( function ($) {
	'use strict';

	$.IG_ItemPricingTable = $.IG_ItemPricingTable || {};

	$.IG_ItemPricingTable = function () {};

    $.IGPopoverOptions	= $.IGPopoverOptions || {};
    var $popover_options = new $.IGPopoverOptions();

	$.IG_ItemPricingTable.prototype = {
		init: function () {
			this.container = $('.jsn-items-list');
			this.actionIconbar(this);
		},
		actionIconbar:function(this_){
            $popover_options.actionIconbar(this_);
        },
        openActionSettings:function(this_, btnInput){
            $popover_options.openActionSettings(this_, btnInput, 'pricingtable', function(dialog){
                var str_shortcode = $(btnInput).parents(".jsn-item").find(':input[name^="shortcode_content"]').val();

                // process popover data
                if ( str_shortcode ) {
                    var _arr_id = [];
                    _arr_id = str_shortcode.match(/item_prtbl_id="[^*!"]+"/g);
                    var _id = '';
                    if ( _arr_id ) {
                        _id 	= _arr_id[0].replace('item_prtbl_id="', '');
                        _id			= _id.replace('"', '');
                    }
                    var _arr_content = [];
                    _arr_content = str_shortcode.match(/prtbl_item_item_content="[^*!"]+"/g);
                    var _content = '';
                    if ( _arr_content ) {
                        _content 	= _arr_content[0].replace('prtbl_item_item_content="', '');
                        _content		= _content.replace('"', '');
                    }
                    var _arr_desc = [];
                    _arr_desc = str_shortcode.match(/item_item_desc="[^*!"]+"/g);
                    var _desc = '';
                    if ( _arr_desc ) {
                        _desc 	= _arr_desc[0].replace('item_item_desc="', '');
                        _desc		= _desc.replace('"', '');
                    }
                    $(dialog).find("#param-item_prtbl_id").attr('value', _id);
                    $(dialog).find("#param-prtbl_item_item_content").attr('value', _content);
                    $(dialog).find("#param-item_item_desc").attr('value', _desc);
                    // bind event
                    $(dialog).find("#param-prtbl_item_item_content").on('change', function () {
                        this_.popoverChange(dialog);
                    });
                    $(dialog).find("#param-item_item_desc").on('change', function () {
                        this_.popoverChange(dialog);
                    });
                }
            });
        },
        popoverChange: function (dialog) {
        	var id = '';
        	var content = '';
        	var desc = '';
        	id = $(dialog).find('#param-item_prtbl_id').attr('value');
        	content = $(dialog).find('#param-prtbl_item_item_content').attr('value');
        	desc = $(dialog).find('#param-item_item_desc').attr('value');

        	var shortcode = '--open_square--ig_item_item_pricingtable item_prtbl_id="'+id+'" prtbl_item_item_content="'+content+'" item_item_desc="'+desc+'" --close_square----open_square--/ig_item_item_pricingtable--close_square--';
        	$('.ui-state-edit textarea[data-sc-info="shortcode_content"]').html(shortcode);
        	$('.ui-state-edit .jsn-item-content').html(content);
        	$('body').trigger('on_update_attr_label_setting');
        },
        getBoxStyle:function(element){
	        return $popover_options.getBoxStyle(element);
	    }
	}

	/* build json attribute data */
	$.IG_PrtblItemAttrs = function () {
		/* build for label attribute */
		var string_sub_items = '';
		$( '#parent-param-prtbl_item_table_atts textarea[data-sc-info="shortcode_content"]' ).each(function () {
			var html = $(this).html();
			html = html.replace(/"/g, '--quote--');
			html = html.replace(/\[/g, '--open_square--');
			html = html.replace(/\]/g, '--close_square--');
			string_sub_items += html + '__#__';
		});

		/* build for checkbox attribute */
		var string_checked = '';
		$( '#parent-param-prtbl_item_table_atts #table_content .radio input:checked' ).each(function () {
			var id = $(this).attr('id');
			id = id.replace(/_item_check/g, '');

			if ( id ) {
				string_checked += 'ig_prtbl_checked';
				string_checked += ' checked_id=--quote--' + id + '--quote--';
				string_checked += ' checked_value=--quote--' + $(this).val() + '--quote--';
				string_checked += '__#__';
			}
		});
		$('#param-prtbl_item_attributes').val( string_sub_items + string_checked );
	}

	$.IG_PrtblProcessJSON = function (shortcode) {
		shortcode = shortcode.replace(/--quote--/g, '"');
		shortcode = shortcode.replace(/--open_square--/g, '[');
		shortcode = shortcode.replace(/--close_square--/g, ']');
		var title = '';
		var arr_title = [];
		arr_title = shortcode.match(/prtbl_item_item_title="[^*!"]+"/g);
		if ( arr_title ) {
			title 	= arr_title[0].replace('prtbl_item_item_title="', '');
			title	= title.replace('"', '');
		}
		var type = '';
		var arr_type = [];
		arr_type = shortcode.match(/prtbl_item_item_type="[^*!"]+"/g);
		if ( arr_type ) {
			type	= arr_type[0].replace('prtbl_item_item_type="', '')
			type	= type.replace('"', '');
		}

		var id = '';
		var arr_id = [];
		arr_id = shortcode.match(/item_prtbl_unique_id="[^*!"]+"/g);
		if ( arr_id ) {
			id	= arr_id[0].replace('item_prtbl_unique_id="', '')
			id	= id.replace('"', '');
		}

		//var id = title.toLowerCase();
		//id = id.replace(/[&\/\\#,+()$~%.'":*?<>{}]/g,'_');
		var html_table = '';

		html_table += '<tr>';
		html_table += '<td><b>' + title + '</b></td>';
		var cell = '';
		if ( type == 'text' ) {
			var el_shortcode = '[ig_item_item_pricingtable item_prtbl_id="' + id + '" tagname="" prtbl_item_item_title="" prtbl_item_item_content="" item_item_desc="" prtbl_item_item_type="" ][/ig_item_item_pricingtable]';
			var html_element = Ig_Translate.prtbl_item_cell;
			html_element = html_element.replace(/IG_SHORTCODE_CONTENT/g, el_shortcode);
			html_element = html_element.replace(/IG_OPTIONS_ATTRIBUTES/g, Ig_Translate.option_attribute);
			html_element = html_element.replace(/IG_CONTENT/g, '&nbsp;');
			
            cell += html_element;
		} else if ( type == 'checkbox' ) {
			cell = '<div class="text-center">';
			cell += '<label class="radio inline"><input type="radio" name="'+id+'_item_check" id="'+id+'_item_check" value="yes" class="ig_item_check">Yes</label>';
			cell += '<label class="radio inline"><input type="radio" name="'+id+'_item_check" id="'+id+'_item_check" checked="" value="no" class="ig_item_check">No</label>'
			cell += '</div>';
		}
		html_table += '<td>' + cell + '</td>';
		html_table += '<td valign="middle" class="ig-delete-column-td"></td>';
		html_table += '</tr>';
		return html_table;
	}

    $(document).ready(function(){

    	// add popover html
    	var popover_html = '';
    	popover_html = '<div class="control-group hidden" style="margin:0px" id="parent-param-item_prtbl_id" data-related-to="ig_item_pricingtable">';
    	popover_html += '	<div class="controls">';
    	popover_html += '		<input type="hidden" value="" class="" id="param-item_prtbl_id">';
    	popover_html += '	</div>';
    	popover_html += '</div>';
    	popover_html += '<div class="control-group hidden" id="parent-param-prtbl_item_item_content" data-related-to="ig_item_pricingtable">';
    	popover_html += '	<label for="param-prtbl_item_item_content" data-title="Content Description" class="control-label ig-label-des-tipsy">Content</label>';
    	popover_html += '	<div class="controls">';
    	popover_html += '		<input type="text" data-role="title" name="param-prtbl_item_item_content" id="param-prtbl_item_item_content" value="" class="jsn-input-xxlarge-fluid">';
    	popover_html += '</div>';
    	popover_html += '</div>';
    	popover_html += '<div class="control-group hidden" id="parent-param-item_item_desc" data-related-to="ig_item_pricingtable">';
    	popover_html += '	<label for="param-item_item_desc" data-title="Description" class="control-label ig-label-des-tipsy">Description</label>';
    	popover_html += '	<div class="controls">';
    	popover_html += '		<textarea style="width:100%" cols="50" rows="4" id="param-item_item_desc" class="jsn-input-xxlarge-fluid"></textarea>';
    	popover_html += '	</div>';
    	popover_html += '</div>';
    	$('#parent-param-prtbl_item_feature').before(popover_html);

    	$('#ig_option_tab').remove();
        var query = window.parent.jQuery.noConflict();
        /* build table attributes with data */
        var label_hidden_element = query('#jsn_view_modal').contents().find('#param-prtbl_common_label_json').val();
        var html_table = '';
        if ( label_hidden_element ) {
        	/* build struct html */
            html_table += '<div id="parent-param-prtbl_item_table_atts">';
            html_table += '<label class="control-label" style="width: 160px;">' + Ig_Translate.attributes + '</label>';
            html_table += '<div class="item-container has_submodal has_childsubmodal controls">';
            html_table += '<div class="ui-sortable item-container-content jsn-items-list">';
            html_table += '<table id="table_content" class="table table-bordered ig-tbl-pricing">';
            html_table += '<tbody>';

        	var item_attrs = $('#param-prtbl_item_attributes').val();
        	var arr_items = label_hidden_element.split('__#__');
        	if ( ! item_attrs ) {
            	for ( var i = 0; i < arr_items.length; i++ ) {
    				if ( arr_items[i] ) {
    					var shortcode = arr_items[i];

    					html_table += $.IG_PrtblProcessJSON(shortcode);
    				}
            	}
        	} else {
        		item_attrs = item_attrs.replace(/--quote--/g, '"');
        		var arr_item_attrs = item_attrs.split('__#__');
        		for ( var i = 0; i < arr_items.length; i++ ) {
    				if ( arr_items[i] ) {
    					var shortcode = arr_items[i];
    					shortcode = shortcode.replace(/--quote--/g, '"');
    					shortcode = shortcode.replace(/--open_square--/g, '[');
    					shortcode = shortcode.replace(/--close_square--/g, ']');

    					var title = '';
    					var arr_title = shortcode.match(/prtbl_item_item_title="[^*!"]+"/g);
    					if ( arr_title ) {
    						title 	= arr_title[0].replace('prtbl_item_item_title="', '');
    						title	= title.replace('"', '');
    					}
    					var type = '';
    					var arr_type = shortcode.match(/prtbl_item_item_type="[^*!"]+"/g);
    					if ( arr_type ) {
    						type	= arr_type[0].replace('prtbl_item_item_type="', '')
    						type	= type.replace('"', '');
    					}

    					var id = '';
    					var arr_id = [];
    					arr_id = shortcode.match(/item_prtbl_unique_id="[^*!"]+"/g);
    					if ( arr_id ) {
    						id	= arr_id[0].replace('item_prtbl_unique_id="', '')
    						id	= id.replace('"', '');
    					}

    					//var id = title.toLowerCase();
    					//id = id.replace(/[&\/\\#,+()$~%.'":*?<>{}]/g,'_');

    					var find_item = false;

    					for ( var j = 0; j < arr_item_attrs.length; j++ ) {
    						if ( arr_item_attrs[j] ) {
    							if ( arr_item_attrs[j].indexOf('ig_item_item_pricingtable') >= 0 ) {
    								var prtbl_id = '';
    		    					var arr_prtbl_id = arr_item_attrs[j].match(/item_prtbl_id="[^*!"]+"/g);
    		    					if ( arr_prtbl_id ) {
    		    						prtbl_id	= arr_prtbl_id[0].replace('item_prtbl_id="', '')
    		    						prtbl_id	= prtbl_id.replace('"', '');
    		    						if ( id == prtbl_id ) {
    		    							if ( type == 'text' ) {
    		    								var prtbl_title = '';
        		    	    					var arr_title = arr_item_attrs[j].match(/prtbl_item_item_content="[^*!"]+"/g);
        		    	    					if ( arr_title ) {
        		    	    						prtbl_title = arr_title[0].replace('prtbl_item_item_content="', '');
        		    	    						prtbl_title	= prtbl_title.replace('"', '');
        		    	    					}
        		    							html_table += '<tr>';
        		    	    					html_table += '<td><b>' + title + '</b></td>';
        		    	    					
        		    	    					var html_element = Ig_Translate.prtbl_item_cell;
        		    	    					html_element = html_element.replace(/IG_SHORTCODE_CONTENT/g, arr_item_attrs[j]);
        		    	    					html_element = html_element.replace(/IG_OPTIONS_ATTRIBUTES/g, Ig_Translate.option_attribute);
        		    	    					html_element = html_element.replace(/IG_CONTENT/g, prtbl_title);
        		    	    					
        		        			            html_table += '<td>' + html_element + '</td>';
        		            					html_table += '<td valign="middle" class="ig-delete-column-td"></td>';
        		            					html_table += '</tr>';
    		    							} else {
    		    								html_table += '<tr>';
    			    	    					html_table += '<td><b>' + title + '</b></td>';
    			    	    					var cell = '<div class="text-center">';
		    		    						cell += '<label class="radio inline"><input type="radio" name="'+id+'_item_check" id="'+id+'_item_check" value="yes" class="ig_item_check">Yes</label>';
		    		    						cell += '<label class="radio inline"><input type="radio" name="'+id+'_item_check" id="'+id+'_item_check" value="no" checked="" class="ig_item_check">No</label>'
		    		    						cell += '</div>';
    			        			            html_table += '<td>' + cell + '</td>';
    			            					html_table += '<td valign="middle" class="ig-delete-column-td"></td>';
    			            					html_table += '</tr>';
    		    							}
    		    							arr_items[i] = '';
    		    							find_item = true;
    		    						}
    		    					}
    							} else if ( arr_item_attrs[j].indexOf('ig_prtbl_checked') >= 0 ) {
    								var prtbl_id = '';
    		    					var arr_prtbl_id = arr_item_attrs[j].match(/checked_id="[^*!"]+"/g);
    		    					if ( arr_prtbl_id ) {
    		    						prtbl_id	= arr_prtbl_id[0].replace('checked_id="', '')
    		    						prtbl_id	= prtbl_id.replace('"', '');
    		    					}
    		    					var prtbl_value = '';
    		    					var arr_prtbl_value = arr_item_attrs[j].match(/checked_value="[^*!"]+"/g);
    		    					if ( arr_prtbl_value ) {
    		    						prtbl_value	= arr_prtbl_value[0].replace('checked_value="', '')
    		    						prtbl_value	= prtbl_value.replace('"', '');
    		    					}
    		    					if ( id == prtbl_id ) {
    		    						if ( type == 'checkbox' ) {
    		    							html_table += '<tr>';
    		    	    					html_table += '<td><b>' + title + '</b></td>';
    		    	    					var cell = '<div class="text-center">';
    		        						cell += '<label class="radio inline"><input type="radio" name="'+id+'_item_check" id="'+id+'_item_check" ' + ((prtbl_value == 'yes') ? ' checked="checked" ' : '') + ' value="yes" class="ig_item_check">Yes</label>';
    		        						cell += '<label class="radio inline"><input type="radio" name="'+id+'_item_check" id="'+id+'_item_check" ' + ((prtbl_value == 'no') ? ' checked="checked" ' : '') + ' value="no" class="ig_item_check">No</label>'
    		        						cell += '</div>';
    		        			            html_table += '<td>' + cell + '</td>';
    		            					html_table += '<td valign="middle" class="ig-delete-column-td"></td>';
    		            					html_table += '</tr>';
    		    						} else {
    		    							html_table += '<tr>';
    		    	    					html_table += '<td><b>' + title + '</b></td>';

    		    	    					var html_element = Ig_Translate.prtbl_item_cell;
    		    	    					html_element = html_element.replace(/IG_SHORTCODE_CONTENT/g, '[ig_item_item_pricingtable item_prtbl_id="' + id + '" tagname="" prtbl_item_item_title="" prtbl_item_item_content="" item_item_desc="" prtbl_item_item_type="" ][/ig_item_item_pricingtable]');
    		    	    					html_element = html_element.replace(/IG_OPTIONS_ATTRIBUTES/g, Ig_Translate.option_attribute);
    		    	    					html_element = html_element.replace(/IG_CONTENT/g, '&nbsp;');

    		        			            html_table += '<td>' + html_element + '</td>';
    		            					html_table += '<td valign="middle" class="ig-delete-column-td"></td>';
    		            					html_table += '</tr>';
    		    						}
    		    						arr_items[i] = '';
    		    						find_item = true;
    		    					}
    							}
    						}
    					}
    					if ( find_item == false ) {
    						var shortcode = arr_items[i];

        					html_table += $.IG_PrtblProcessJSON(shortcode);
    					}
    				}
            	}
        	}
        	html_table += '<tr class="ig-row-of-delete"><td><div class="jsn-iconbar"></div></td><td><div class="jsn-iconbar"></div></td></tr>';
            html_table += '</tbody>';
            html_table += '</table>';
            html_table += '</div>';
            html_table += '</div>';
            html_table += '</div>';
        }

        $('#parent-param-prtbl_item_attributes').before(html_table);
        /* setting on change for checkbox in pricing item setting */
        $('#parent-param-prtbl_item_table_atts #table_content .radio input').on('click', function () {
        	$.IG_PrtblItemAttrs();
        });
        $('body').bind('on_update_attr_label_setting', function () {
            $.IG_PrtblItemAttrs();
        });
        $.IG_PrtblItemAttrs();
        var Ig_PricingTable = new $.IG_ItemPricingTable();
        Ig_PricingTable.init();
    })

} )(jQuery)