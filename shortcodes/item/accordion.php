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
if ( ! class_exists( 'IG_Item_Accordion' ) ) {

	class IG_Item_Accordion extends IG_Pb_Child {

		public function __construct() {
			parent::__construct();
		}

		/**
		 * DEFINE configuration information of shortcode
		 */
		public function element_config() {
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['exception'] = array(
				'data-modal-title' => __( 'Accordion Item', IGPBL )
			);
		}

		/**
		 * DEFINE setting options of shortcode
		 */
		public function element_items() {
			$this->items = array(
				'Notab' => array(
					array(
						'name'  => __( 'Heading', IGPBL ),
						'id'    => 'heading',
						'type'  => 'text_field',
						'class' => 'jsn-input-xxlarge-fluid',
						'role'  => 'title',
						'std'   => __( ig_pb_add_placeholder( 'Accordion Item %s', 'index' ), IGPBL )
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
					array(
						'name' => __( 'Tag', IGPBL ),
						'id'   => 'tag',
						'type' => 'tag',
						'std'  => '',
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

			// tag1,tag2 => tag1 tag2 , to filter
			$tag = str_replace( ' ', '_', $tag );
			$tag = str_replace( ',', ' ', $tag );
			$inner_content = IG_Pb_Helper_Shortcode::remove_autop( $content );
			return "
			<div class='panel panel-default' data-tag='$tag'>
				<div class='panel-heading'>
					<h4 class='panel-title'>
						<a data-toggle='collapse' data-parent='#accordion_{ID}' href='#collapse{index}'>
						<i class='$icon'></i>$heading
						</a>
					</h4>
				</div>
				<div id='collapse{index}' class='panel-collapse collapse {show_hide}'>
				  <div class='panel-body'>
				  {$inner_content}
				  </div>
				</div>
			</div><!--seperate-->";
		}

	}

}
