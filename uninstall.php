<?php
/**
 * IG Pagebuilder Uninstalling
 *
 * Uninstalling IG Pagebuilder: deletes post metas & options
 *
 * @author		InnoThemes Team <support@innothemes.com>
 * @package		IGPGBLDR
 * @version		$Id$
 */

//if uninstall not called from WordPress exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

include_once 'core/utils/common.php';

ig_pb_delete_meta_key( array( '_ig_page_builder_content', '_ig_html_content', '_ig_page_active_tab', '_ig_post_view_count', '_ig_deactivate_pb' ) );

do_action( 'ig_pb_uninstall' );