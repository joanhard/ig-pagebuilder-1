<?php
/**
 * @version    $Id$
 * @package    IG_Library
 * @author     InnoGears Team <support@innogears.com>
 * @copyright  Copyright (C) 2012 InnoGears.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.innogears.com
 * Technical Support: Feedback - http://www.innogears.com/contact-us/get-support.html
 */
?>
<div class="jsn-master"><div class="jsn-bootstrap"><div class="jsn-page-upgrade">
	<div class="jsn-page-content jsn-rounded-large jsn-box-shadow-large jsn-bootstrap">
		<span id="jsn-upgrade-cancel"><a href="<?php echo esc_url( admin_url() ); ?>" class="jsn-link-action">
			<?php _e( 'Cancel', IGPBL ); ?></a>
		</span>
		<?php if ( $upgrable ) : ?>
		<h1>
			<?php esc_html_e( sprintf( __( 'Upgrade %1$s %2$s to %3$s Edition', IGPBL ), $plugin['Name'], strtoupper( $plugin['Edition'] ), $highest->edition ) ); ?>
		</h1>
		<div id="jsn-upgrade-action">
			<p>
				<?php esc_html_e( sprintf( __( 'This wizard will guide you through the process of upgrading your %1$s to %2$s edition.', IGPBL ), $plugin['Name'], $highest->edition ) ); ?>
 			</p>
			<div class="alert alert-info">
				<p>
					<span class="label label-info">
						<?php _e( 'Important information', IGPBL ); ?>
					</span>
				</p>
				<ul>
					<li>
						<?php esc_html_e( sprintf( __( 'All %1$s data will remain after the upgrade process.', IGPBL ), $plugin['Name'] ) ); ?>
					</li>
				</ul>
			</div>
			<?php echo '' . $content; ?>
			<div class="form-actions">
				<p>
					<a data-source="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" href="javascript:void(0)" class="btn btn-primary" id="jsn-proceed-button">
						<?php esc_html_e( sprintf( __( 'I already purchased the %1$s edition, please upgrade', IGPBL ), $highest->edition ) ); ?>
					</a>
				</p>
				<p>
					<a class="jsn-link-action" target="_blank" href="<?php echo esc_url( $plugin['PluginURI'] ); ?>">
						<?php esc_html_e( sprintf( __( 'I want to purchase %1$s edition now', IGPBL ), $highest->edition ) ); ?>
					</a>
				</p>
			</div>
		</div>
		<div id="jsn-upgrade-login" style="display: none;">
			<form autocomplete="off" class="form-horizontal" method="POST" name="JSNUpgradeLogin">
				<h2>
					<?php _e( 'Enter InnoGears Customer Account', IGPBL ); ?>
				</h2>
				<p>
					<?php _e( 'For verification, please enter the <strong>InnoGears customer account</strong> that you created when making purchase', IGPBL ); ?>
				</p>
				<div class="row-fluid">
					<div class="span6">
						<div class="control-group">
							<label class="inline" for="username"><?php _e( 'Username', IGPBL ); ?>:</label>
							<input type="text" name="customer_username" id="username" class="input-xlarge" value="">
						</div>
					</div>
					<div class="span6">
						<div class="control-group">
							<label class="inline" for="password"><?php _e( 'Password', IGPBL ); ?>:</label>
							<input type="password" name="customer_password" id="password" class="input-xlarge" value="">
						</div>
					</div>
				</div>
				<hr />
				<div class="alert alert-error" id="jsn-upgrade-message"></div>
				<div class="row-fluid" id="jsn-upgrade-editions" style="display: none;">
					<div class="control-group">
						<label class="inline" for="editions"><?php _e( 'Editions', IGPBL ); ?>:</label>
						<select name="edition"></select>
					</div>
				</div>
				<div class="form-actions">
					<button disabled="disabled" class="btn btn-primary"><?php _e( 'Next', IGPBL ); ?></button>
				</div>
			</form>
		</div>
		<div id="jsn-upgrade-indicator" style="display: none;">
			<p>
				<?php _e( 'There are several stages involved in the process. Please be patient.', IGPBL ); ?>
			</p>
			<ul>
				<li id="jsn-upgrade-downloading">
					<?php _e( 'Download installation package.', IGPBL ); ?>
					<span class="jsn-icon16 jsn-icon-loading" id="jsn-upgrade-downloading-indicator"></span>
					<span class="jsn-processing-message" id="jsn-upgrade-downloading-notice"></span>
					<br />
					<p class="jsn-text-important" id="jsn-upgrade-downloading-unsuccessful-message"></p>
				</li>
				<li style="display: none;" id="jsn-upgrade-installing">
					<?php _e( 'Install package.', IGPBL ); ?>
					<span class="jsn-icon16 jsn-icon-loading" id="jsn-upgrade-installing-indicator"></span>
					<span class="jsn-processing-message" id="jsn-upgrade-downloading-notice"></span>
					<br />
					<p class="jsn-text-important" id="jsn-upgrade-installing-unsuccessful-message"></p>
					<div class="alert alert-warning" id="jsn-upgrade-installing-warnings">
						<p><span class="label label-important">WARNING!</span></p>
					</div>
				</li>
			</ul>
		</div>
		<div id="jsn-upgrade-successfully" style="display: none;">
			<hr />
			<p>
				<?php _e( sprintf( __( '%1$s is successfully upgraded. Please <b>clear your browser\'s cache</b> after clicking the button below.', IGPBL ), $plugin['Name'] ) ); ?>
			</p>
			<div class="form-actions">
				<p>
					<a href="<?php echo esc_url( wp_get_referer() ); ?>" class="btn btn-primary">
						<?php _e( 'Finish', IGPBL ); ?>
					</a>
				</p>
			</div>
		</div>
		<?php else : ?>
		<h1>
			<?php esc_html_e( sprintf( __( 'Upgrade %1$s %2$s Edition', IGPBL ), $plugin['Name'], strtoupper( $plugin['Edition'] ) ) ); ?>
		</h1>
		<div class="alert alert-info">
			<p>
				<span class="label label-info">
					<?php _e( 'Important information', IGPBL ); ?>
				</span>
			</p>
			<ul>
				<li>
					<?php esc_html_e( $message ); ?>
				</li>
			</ul>
		</div>
		<?php endif; ?>
	</div>
</div></div></div>
<?php
if ( $upgrable ) :

// Load inline script initialization
$script = '
		new IG_Upgrade({
			button: "jsn-proceed-button",
 			identified_name: "' . $plugin['Identified_Name'] . '",
 			edition: "' . $current_edition . '",
			language: {
				STILL_WORKING: "' . __( 'Still Working...', IGPBL ) . '",
				PLEASE_WAIT: "' . __( 'Please Wait...', IGPBL ) . '",
 				NO_EDITION: "' . __( 'Cannot retrieve purchased edition.', IGPBL ) . '",
			}
		});';

IG_Init_Assets::inline( 'js', $script );

endif;
