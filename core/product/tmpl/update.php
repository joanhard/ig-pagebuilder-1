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

$authentication = false;
?>
<div class="jsn-master"><div class="jsn-bootstrap"><div class="jsn-page-update">
	<div class="jsn-page-content jsn-rounded-large jsn-box-shadow-large jsn-bootstrap">
		<span id="jsn-update-cancel"><a href="<?php echo esc_url( admin_url() ); ?>" class="jsn-link-action">
			<?php _e( 'Cancel', IGPBL ); ?></a>
		</span>
		<h1>
			<?php esc_html_e( sprintf( __( 'Update %1$s %2$s', IGPBL ), $plugin['Name'], strtoupper( $plugin['Edition'] ) ) ); ?>
		</h1>
		<?php if ( $updatable ) : ?>
		<div id="jsn-update-action">
			<p>
				<?php esc_html_e( sprintf( __( 'This wizard will guide you through the process of updating your %1$s to the latest version.', IGPBL ), $plugin['Name'] ) ); ?>
			</p>
			<div class="alert alert-info">
				<p>
					<span class="label label-info">
						<?php _e( 'Important information', IGPBL ); ?>
					</span>
				</p>
				<ul>
					<li>
						<?php esc_html_e( sprintf( __( 'All %1$s data will remain after the update process.', IGPBL ), $plugin['Name'] ) ); ?>
					</li>
				</ul>
			</div>
			<p>
				<?php esc_html_e( sprintf( __( 'This process will update following %1$s elements to the latest version:', IGPBL ), $plugin['Name'] ) ); ?>
			</p>
			<ul>
				<?php foreach ( $plugin['Available_Update'] as $id => $item ) : if ( $item['authentication'] && ! $authentication ) { $authentication = true; } ?>
				<li ref="id=<?php esc_attr_e( $id ); ?>&edition=<?php esc_attr_e( $item['edition'] ); ?>">
					<?php esc_html_e( sprintf( __( 'Update %1$s to version %2$s', IGPBL ), $item['name'], $item['version'] ) ); ?>
				</li>
				<?php endforeach; ?>
			</ul>
			<div class="form-actions">
				<p>
					<a data-source="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" href="javascript:void(0)" class="btn btn-primary" id="jsn-proceed-button">
						<?php _e( 'Update', IGPBL ); ?>
					</a>
				</p>
			</div>
		</div>
		<?php if ( $authentication ) : ?>
		<div id="jsn-update-login" style="display: none;">
			<form name="JSNUpdateLogin" method="POST" class="form-horizontal" autocomplete="off">
				<h2>
					<?php _e( 'Enter InnoGears Customer Account', IGPBL ); ?>
				</h2>
				<p>
					<?php _e( 'For verification, please enter the <strong>InnoGears customer account</strong> that you created when making purchase', IGPBL ); ?>
				</p>
				<div class="row-fluid">
					<div class="span6">
						<div class="control-group">
							<label for="username" class="inline"><?php _e( 'Username', IGPBL ); ?>:</label>
							<input type="text" value="" class="input-xlarge" id="username" name="customer_username" />
						</div>
					</div>
					<div class="span6">
						<div class="control-group">
							<label for="password" class="inline"><?php _e( 'Password', IGPBL ); ?>:</label>
							<input type="password" value="" class="input-xlarge" id="password" name="customer_password" />
						</div>
					</div>
				</div>
				<hr />
				<div class="form-actions">
					<button class="btn btn-primary" disabled="disabled"><?php _e( 'Next', IGPBL ); ?></button>
				</div>
			</form>
		</div>
		<?php endif; ?>
		<div id="jsn-update-indicator" style="display: none;">
			<p>
				<?php _e( 'There are several stages involved in the process. Please be patient.', IGPBL ); ?>
			</p>
			<ul id="jsn-update-products">
				<?php foreach ( $plugin['Available_Update'] as $id => $item ) : ?>
				<li ref="id=<?php esc_attr_e( $id ); ?>&edition=<?php esc_attr_e( $item['edition'] ); ?>">
					<?php esc_html_e( sprintf( __( 'Update %1$s to version %2$s', IGPBL ), $item['name'], $item['version'] ) ); ?>
					<ul>
						<li style="display: none;" class="jsn-update-downloading">
							<?php _e( 'Download installation package.', IGPBL ); ?>
							<span class="jsn-update-downloading-indicator jsn-icon16 jsn-icon-loading"></span>
							<span class="jsn-update-downloading-notice jsn-processing-message"></span>
							<br />
							<p class="jsn-update-downloading-unsuccessful-message jsn-text-important"></p>
						</li>
						<li style="display: none;" class="jsn-update-installing">
							<?php _e( 'Install package.', IGPBL ); ?>
							<span class="jsn-update-installing-indicator jsn-icon16 jsn-icon-loading"></span>
							<span class="jsn-update-downloading-notice jsn-processing-message"></span>
							<br />
							<p class="jsn-update-installing-unsuccessful-message jsn-text-important"></p>
							<div class="jsn-update-installing-warnings alert alert-warning">
								<p>
									<span class="label label-important"><?php _e( 'WARNING!', IGPBL ); ?></span>
								</p>
							</div>
						</li>
					</ul>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<div id="jsn-update-successfully" style="display: none;">
			<hr />
			<p>
				<?php _e( sprintf( __( '%1$s is successfully updated. Please <b>clear your browser\'s cache</b> after clicking the button below.', IGPBL ), $plugin['Name'] ) ); ?>
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
// Load inline script initialization
$script = '
		new IG_Update({
			button: "jsn-proceed-button",
			language: {
				STILL_WORKING: "' . __( 'Still Working...', IGPBL ) . '",
				PLEASE_WAIT: "' . __( 'Please Wait...', IGPBL ) . '",
			}
		});';

IG_Init_Assets::inline( 'js', $script );
