<?php
/**
 * @version	$Id$
 * @package	IG Pagebuilder
 * @author	 InnoGears Team <support@www.innogears.com>
 * @copyright  Copyright (C) 2012 www.innogears.com. All Rights Reserved.
 * @license	GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.www.innogears.com
 * Technical Support:  Feedback - http://www.www.innogears.com
 */

$data = IG_Pb_Helper_Layout::show_premade_layouts();
?>
<div class="jsn-master">
    <div class="jsn-bootstrap">
        <div id="ig-layout-lib" class="add-field-dialog jsn-elementselector">
            <div class="jsn-fieldset-filter">
                <fieldset>
                    <div style="text-align: center;">
                        <select class="jsn-filter-button input-large" style="float: none;">
                            <?php echo balanceTags( $data['providers'] ); ?>
                        </select>
                    </div>
<!--                    <div class="pull-right jsn-quick-search">
                        <input type="text" class="input search-query" id="jsn-quicksearch-field" placeholder="<?php _e( 'Search', IGPBL ); ?>...">
                        <a href="javascript:void(0);" title="<?php _e( 'Clear Search', IGPBL ); ?>" class="jsn-reset-search" id="reset-search-btn"><i class="icon-remove"></i></a>
                    </div>-->
                </fieldset>
            </div>
            <!-- Elements -->
            <ul class="jsn-items-list" style="height: auto;">
        <?php
		echo balanceTags( $data['files'] );
		///add_action( 'ig_pb_footer', array( 'IG_Pb_Helper_Functions', 'print_premade_layouts' ) );
		?>
            </ul>
        </div>
    </div>
</div>