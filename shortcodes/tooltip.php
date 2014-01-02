<?php

/**
 * @version	$Id$
 * @package	IG Pagebuilder
 * @author	 InnoThemes Team <support@innothemes.com>
 * @copyright  Copyright (C) 2012 innothemes.com. All Rights Reserved.
 * @license	GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.innothemes.com
 * Technical Support:  Feedback - http://www.innothemes.com
 */
if ( ! class_exists( 'IG_Tooltip' ) ) {

	class IG_Tooltip extends IG_Pb_Element {

		public function __construct() {
			parent::__construct();
		}

		/**
		 * DEFINE configuration information of shortcode
		 */
		public function element_config() {
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['name']      = __( 'Tooltip', IGPBL );
			$this->config['cat']       = __( 'Typography', IGPBL );
			$this->config['icon']      = 'icon-paragraph-text';
		}

		/**
		 * DEFINE setting options of shortcode
		 */
		public function element_items() {
			$this->items = array(
				'content' => array(
					array(
						'name'    => __( 'Element Title', IGPBL ),
						'id'      => 'el_title',
						'type'    => 'text_field',
						'class'   => 'jsn-input-xxlarge-fluid',
						'std'     => __( '', IGPBL ),
						'role'    => 'title',
						'tooltip' => __( 'Set title for current element for identifying easily', IGPBL )
					),
					array(
						'name'    => __( 'Text', IGPBL ),
						'id'      => 'text',
						'type'    => 'text_field',
						'class'   => 'jsn-input-xxlarge-fluid',
						'std'     => __( 'Your text', IGPBL ),
						'tooltip' => __( 'Text Description', IGPBL )
					),
					array(
						'name'    => __( 'Tooltip Content', IGPBL ),
						'id'      => 'tooltip_content',
						'role'    => 'content',
						'type'    => 'tiny_mce',
						'std'     => __( 'Your tooltip content', IGPBL ),
						'tooltip' => __( 'Tooltip content. Accept HTML tag', IGPBL ),
					),
				),
				'styling' => array(
					array(
						'type' => 'preview',
					),
					array(
						'name'    => __( 'Tooltip Position', IGPBL ),
						'id'      => 'position',
						'type'    => 'select',
						'std'     => IG_Pb_Helper_Type::get_first_option( IG_Pb_Helper_Type::get_full_positions() ),
						'options' => IG_Pb_Helper_Type::get_full_positions(),
						'tooltip' => __( 'Description', IGPBL )
					),
					array(
						'name'       => __( 'Tooltips In Button', IGPBL ),
						'id'         => 'tooltips_button',
						'type'       => 'radio',
						'std'        => 'no',
						'options'    => array( 'yes' => __( 'Yes', IGPBL ), 'no' => __( 'No', IGPBL ) ),
						'has_depend' => '1',
					),
					array(
						'name' => __( 'Button Color', IGPBL ),
						'type' => array(
							array(
								'id'      => 'button_color',
								'type'    => 'select',
								'std'     => IG_Pb_Helper_Type::get_first_option( IG_Pb_Helper_Type::get_button_color() ),
								'options' => IG_Pb_Helper_Type::get_button_color(),
							),
						),
						'dependency' => array( 'tooltips_button', '=', 'yes' ),
                        'container_class'   => 'color_select2',
					),
					array(
						'name'            => __( 'Delay', IGPBL ),
						'container_class' => 'combo-group',
						'type'            => array(
							array(
								'id'            => 'show',
								'type'          => 'text_append',
								'type_input'    => 'number',
								'class'         => 'input-mini',
								'std'           => '500',
								'append_before' => 'Show',
								'append'        => 'ms',
								'parent_class'  => 'combo-item',
								'validate'      => 'number',
							),
							array(
								'id'            => 'hide',
								'type'          => 'text_append',
								'type_input'    => 'number',
								'class'         => 'input-mini',
								'std'           => '100',
								'append_before' => 'Hide',
								'append'        => 'ms',
								'parent_class'  => 'combo-item',
								'validate'      => 'number',
							),
						),
					),
				)
			);
		}

		/**
		 * DEFINE shortcode content
		 *
		 * @param type $atts
		 * @param type $content
		 */
		public function element_shortcode( $atts = null, $content = null ) {
			$arr_params = shortcode_atts( $this->config['params'], $atts );
			extract( $arr_params );
			$random_id  = ig_pb_generate_random_string();
			$tooltip_id = "tooltip_$random_id";
            // don't allow to run shortcode
            $content = str_replace( '[', '[[', $content );
            $content = str_replace( ']', ']]', $content );

			$button_color = ( ! $button_color || strtolower( $button_color ) == 'default' ) ? '' : $button_color;
			$position     = strtolower( $position );
			$delay_show   = ! empty( $show ) ? $show : 500;
			$delay_hide   = ! empty( $hide ) ? $hide : 100;
			$direction    = array( 'top' => 's', 'bottom' => 'n', 'left' => 'e', 'right' => 'w' );
			$script = "<script type='text/javascript'>( function ($) {
					$( document ).ready( function ()
					{
						$('#$tooltip_id').click(function(e){
							e.preventDefault();
						})
						$('#$tooltip_id').tipsy({
							fallback: '$content',
							html: true,
							live: true,
							delayIn: $delay_show,
							delayOut: $delay_hide,
							gravity: '{$direction[$position]}'
						})
					});
				} )( jQuery )</script>";
			if ( $tooltips_button == 'no' ) {
				$html = "<a id='$tooltip_id' class='ig-label-des-tipsy' original-title='' href='#'>$text</a>";
			} else {
				$html = "<a id='$tooltip_id' class='ig-label-des-tipsy btn {$button_color}' original-title='' href='#'>$text</a>";
			}
			$html = $html . $script;
			if ( is_admin() ) {
				$custom_style = "style='margin-top: 50px;'";
				$html_element = "<center $custom_style>$html</center>";
			} else
				$html_element = $html;

			return $this->element_wrapper( $html_element, $arr_params );
		}

	}

}
