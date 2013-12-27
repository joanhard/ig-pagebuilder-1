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
if ( ! class_exists( 'IG_Widget' ) ) {

	class IG_Widget extends IG_Pb_Element {

		public function __construct() {
			parent::__construct();
		}

		function element_config() {
			$this->config['shortcode'] = strtolower( __CLASS__ );
		}

		function element_items() {
			
		}

		function element_shortcode( $atts = null, $content = null ) {
			
		}

	}

}