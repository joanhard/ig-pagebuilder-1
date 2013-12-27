<?php
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
if ( ! class_exists( 'IG_Item_ProgressBar' ) ) {

	class IG_Item_ProgressBar extends IG_Pb_Child {

		public function __construct() {
			parent::__construct();
		}

		public function element_config() {
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['exception'] = array(
				'item_text'        => __( 'Progress bar', IGPBL ),
				'data-modal-title' => __( 'Progress Bar Item', IGPBL )
			);
		}

		public function element_items() {
			$this->items = array(
				'Notab' => array(
					array(
						'name'    => __( 'Text', IGPBL ),
						'id'      => 'pbar_text',
						'type'    => 'text_field',
						'class'   => 'jsn-input-xxlarge-fluid',
						'std'     => __( ig_pb_add_placeholder( 'Progress bar %s', 'index' ), IGPBL ),
						'role'    => 'title',
						'tooltip' => __( 'Text Description', IGPBL )
					),
					array(
						'name'         => __( 'Percentage', IGPBL ),
						'id'           => 'pbar_percentage',
						'type'         => 'text_append',
						'type_input'   => 'number',
						'class'        => 'input-mini',
						'std'          => '25',
						'append'       => '%',
						'validate'     => 'number',
						'parent_class' => 'combo-item',
						'tooltip'      => __( 'Percentage Description', IGPBL )
					),
					array(
						'name'    => __( 'Color', IGPBL ),
						'id'      => 'pbar_color',
						'type'    => 'select',
						'std'     => IG_Pb_Helper_Type::get_first_option( IG_Pb_Helper_Type::get_progress_bar_color() ),
						'options' => IG_Pb_Helper_Type::get_progress_bar_color(),
						'tooltip' => __( 'Color Description', IGPBL ),
						'container_class'   => 'color_select2',
					),
					array(
						'name'    => __( 'Style', IGPBL ),
						'id'      => 'pbar_item_style',
						'type'    => 'select',
						'std'     => IG_Pb_Helper_Type::get_first_option( IG_Pb_Helper_Type::get_progress_bar_item_style() ),
						'options' => IG_Pb_Helper_Type::get_progress_bar_item_style(),
						'tooltip' => __( 'Style Description', IGPBL )
					),
					array(
						'name'      => __( 'Icon', IGPBL ),
						'id'        => 'pbar_icon',
						'type'      => 'icons',
						'std'       => '',
						'role'      => 'title_prepend',
						'title_prepend_type' => 'icon',
						'tooltip'   => __( 'Icon Description', IGPBL )
					),
					array(
						'id'    => 'pbar_group',
						'class' => 'pbar_group_type',
						'type'  => 'hidden',
						'std'   => 'multiple-bars',
					),
				)
			);
		}

		public function element_shortcode( $atts = null, $content = null ) {
			extract( shortcode_atts( $this->config['params'], $atts ) );
			$pbar_percentage       = floatval( $pbar_percentage );
			$pbar_color            = ( strtolower( $pbar_color ) == 'default' || empty( $pbar_color ) ) ? $pbar_color = '' : ' ' . $pbar_color;
			$pbar_percentage_width = ( ! $pbar_percentage ) ? '' : ' style="width: ' . $pbar_percentage . '%"';
			$pbar_value			   = ( ! $pbar_percentage ) ? '' : ' aria-valuenow="' . $pbar_percentage . '"';
			$pbar_item_style       = ( ! $pbar_item_style || strtolower( $pbar_item_style ) == 'solid' ) ? '' : $pbar_item_style;
			if ( $pbar_item_style == 'striped' ) {
				$pbar_item_style = ' progress-striped';
			}

			$pbar_icon    = ( ! $pbar_icon ) ? '' : "<i class='{$pbar_icon}'></i>";
			$html_content = "[icon]{$pbar_icon}[/icon][text]{$pbar_text}[/text]";

			// Add title progressbar
			$html_content = "<div class='progress-info'[width]><span class='progress-title'>{$html_content}</span>[percentage]<span class='progress-percentage'>{$pbar_percentage}%</span>[/percentage]</div>";

			if ( $pbar_group == 'stacked' ) {
				$html_content = str_replace( '[width]', "style='width:{$pbar_percentage}%'", $html_content );
				$html_sub_elm = '[sub_content]' . $html_content . '[/sub_content]';
				$html_sub_elm .= "<div class='progress-bar{$pbar_color}{$pbar_item_style}'{$pbar_percentage_width}></div>";
			} else {
				$html_content = str_replace( '[width]', '', $html_content );
				$html_sub_elm = '[sub_content]' . $html_content . '[/sub_content]';
				$html_sub_elm .= "<div class='progress{$pbar_item_style}{active}'>";
				$html_sub_elm .= "<div class='progress-bar {$pbar_color}' role='progressbar'{$pbar_value}aria-valuemin='0' aria-valuemax='100'{$pbar_percentage_width}></div>";
				$html_sub_elm .= '</div>';
			}

			return $html_sub_elm . '<!--seperate-->';
		}

	}

}