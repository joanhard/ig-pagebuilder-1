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
 * Parent class for all elements of page builder
 */
class IG_Pb_Common {

	/**
	 * element type: layout/element
	 */
	public $type;

	/**
	 * config information of this element
	 */
	public $config;

	/**
	 * setting options of this element
	 */
	public $items;

	public function __construct() {
		
	}

	/*
	 * HTML structure of an element in SELECT ELEMENT modal box
	 */

	public function element_button( $sort ) {
		
	}

	/*
	 * HTML structure of an element in Page Builder area
	 */

	public function element_in_pgbldr() {
		
	}

}
