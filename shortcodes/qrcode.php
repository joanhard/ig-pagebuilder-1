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
if ( ! class_exists( 'IG_QRCode' ) ) {

	class IG_QRCode extends IG_Pb_Element {

		public function __construct() {
			parent::__construct();
		}

		public function element_config() {
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['name']      = __( 'QR Code', IGPBL );
			$this->config['cat']       = __( 'Extra', IGPBL );
			$this->config['icon']      = 'icon-paragraph-text';
			$this->config['exception'] = array(
				'data-modal-title' => __( 'QR Code', IGPBL )
			);
		}

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
						'name'          => __( 'Data', IGPBL ),
						'id'            => 'qr_content',
						'type'          => 'text_area',
						'class'         => 'jsn-input-xxlarge-fluid',
						'std'           => 'http://www.innothemes.com',
						'tooltip'       => __( 'Data Description', IGPBL ),
						'exclude_quote' => '1',
					),
					array(
						'name'    => __( 'Image ALT Text', IGPBL ),
						'id'      => 'qr_alt',
						'type'    => 'text_field',
						'class'   => 'jsn-input-xxlarge-fluid',
						'std'     => __( 'Wordpress themes from www.innothemes.com', IGPBL ),
						'tooltip' => __( 'Image ALT Text Description', IGPBL ),
					),
				),
				'styling' => array(
					array(
						'type' => 'preview',
					),
					array(
						'name'    => __( 'Container Style', IGPBL ),
						'id'      => 'qr_container_style',
						'type'    => 'select',
						'std'     => IG_Pb_Helper_Type::get_first_option( IG_Pb_Helper_Type::get_qr_container_style() ),
						'options' => IG_Pb_Helper_Type::get_qr_container_style(),
						'tooltip' => __( 'Container Style Description', IGPBL )
					),
					array(
						'name'    => __( 'Alignment', IGPBL ),
						'id'      => 'qr_alignment',
						'type'    => 'select',
						'std'     => IG_Pb_Helper_Type::get_first_option( IG_Pb_Helper_Type::get_text_align() ),
						'options' => IG_Pb_Helper_Type::get_text_align(),
						'tooltip' => __( 'Alignment Description', IGPBL )
					),
					array(
						'name'       => __( 'QRCode Sizes', IGPBL ),
						'id'         => 'qrcode_sizes',
						'type'       => 'text_append',
						'type_input' => 'number',
						'class'      => 'input-mini',
						'std'        => '150',
						'append'     => 'px',
						'validate'   => 'number',
						'tooltip'    => __( 'QRCode Sizes Description', IGPBL )
					),
				)
			);
		}

		public function element_shortcode( $atts = null, $content = null ) {
			$html_element  = '';
			$arr_params    = ( shortcode_atts( $this->config['params'], $atts ) );
			extract( $arr_params );
			$qrcode_sizes  = ( $qrcode_sizes ) ? ( int ) $qrcode_sizes : 0;
			$cls_alignment = '';
			if ( strtolower( $arr_params['qr_alignment'] ) != 'inherit' ) {
				if ( strtolower( $arr_params['qr_alignment'] ) == 'left' )
					$cls_alignment = "class='pull-left'";
				if ( strtolower( $arr_params['qr_alignment'] ) == 'right' )
					$cls_alignment = "class='pull-right'";
				if ( strtolower( $arr_params['qr_alignment'] ) == 'center' )
					$cls_alignment = "class='text-center'";
			}
			$class_img = ( $qr_container_style != 'no-styling' ) ? "class='{$qr_container_style}'" : '';
			$qr_content = str_replace( '<ig_quote>', '"', $qr_content );
			$image = 'https://chart.googleapis.com/chart?chs=' . $qrcode_sizes . 'x' . $qrcode_sizes . '&cht=qr&chld=H|1&chl=' . $qr_content;
			$qr_alt = ( ! empty( $qr_alt ) ) ? "alt='{$qr_alt}'" : '';
			$html_element = "<img src='{$image}' {$qr_alt} width='{$qrcode_sizes}' height='{$qrcode_sizes}' $class_img />";
			if ($cls_alignment != '')
				$html_element = "<div {$cls_alignment}>{$html_element}</div>";

			return $this->element_wrapper( $html_element, $arr_params );
		}

	}

}