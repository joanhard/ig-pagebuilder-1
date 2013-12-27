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
if ( ! class_exists( 'IG_Item_Carousel' ) ) {

	class IG_Item_Carousel extends IG_Pb_Child {

		public function __construct() {
			parent::__construct();
		}

		/**
		 * DEFINE configuration information of shortcode
		 */
		public function element_config() {
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['exception'] = array(
				'data-modal-title' => __( 'Carousel Item', IGPBL )
			);
		}

		/**
		 * DEFINE setting options of shortcode
		 */
		public function element_items() {
			$this->items = array(
				'Notab' => array(
					array(
						'name'    => __( 'Image File', IGPBL ),
						'id'      => 'image_file',
						'type'    => 'select_media',
						'std'     => '',
						'class'   => 'jsn-input-large-fluid',
						'tooltip' => __( 'Image File Description', IGPBL )
					),
					array(
						'name'  => __( 'Heading', IGPBL ),
						'id'    => 'heading',
						'type'  => 'text_field',
						'class' => 'jsn-input-xxlarge-fluid',
						'role'  => 'title',
						'std'   => __( ig_pb_add_placeholder( 'Carousel Item %s', 'index' ), IGPBL )
					),
					array(
						'name' => __( 'Body', IGPBL ),
						'id'   => 'body',
						'role' => 'content',
						'type' => 'tiny_mce',
						'std'  => IG_Pb_Helper_Type::lorem_text()
					),
					array(
						'name'      => __( 'Icon', IGPBL ),
						'id'        => 'icon',
						'type'      => 'icons',
						'std'       => '',
						'role'      => 'title_prepend',
						'title_prepend_type' => 'icon',
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
			extract( shortcode_atts( $this->config['params'], $atts ) );
			$content_class = ! empty( $image_file ) ? 'carousel-caption' : 'carousel-content';
			$hidden        = ( empty( $heading ) && empty( $content) ) ? 'style="display:none"' : '';
			$img           = ! empty( $image_file ) ? "<div class='carousel-image' style='{WIDTH}{HEIGHT}background-image: url( $image_file )'></div>" : '';
			$inner_content = IG_Pb_Helper_Shortcode::remove_autop( $content );
			return "
				<div class='{active} item'>
					$img
					<div class='$content_class' $hidden>
						<h4><i class='$icon'></i>$heading</h4>
						<p>{$inner_content}</p>
					</div>
				</div><!--seperate-->";
		}

	}

}
