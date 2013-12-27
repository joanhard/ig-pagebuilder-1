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
/*
 * Parent class for sub elements
 */

class IG_Pb_Child extends IG_Pb_Element {

	public function element_in_pgbldr( $content = '', $shortcode_data = '', $el_title = '' ) {
		$this->config['sub_element'] = true;
		return parent::element_in_pgbldr( $content, $shortcode_data );
	}

}
