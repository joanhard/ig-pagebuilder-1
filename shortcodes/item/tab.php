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
if ( ! class_exists( 'IG_Item_Tab' ) ) {

	class IG_Item_Tab extends IG_Pb_Child {

		public function __construct() {
			parent::__construct();
		}

		/**
		 * DEFINE configuration information of shortcode
		 */
		public function element_config() {
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['exception'] = array(
				'data-modal-title' => __( 'Tab Item', IGPBL )
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
						'std'   => __( ig_pb_add_placeholder( 'Tab Item %s', 'index' ), IGPBL )
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
			$inner_content = IG_Pb_Helper_Shortcode::remove_autop( $content );
            $custom_style  = ig_pb_get_placeholder( 'custom_style' );
			return "<div id='pane{index}' class='tab-pane {active} {fade_effect}' $custom_style>
					{$inner_content}
				</div><!--seperate-->";
		}

	}

}
