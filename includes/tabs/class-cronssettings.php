<?php
/**
 * Crons setting class file
 *
 * @category Plugin
 * @package  EmailCrons
 * @author   Infobeans <infobeans@infobeans.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GPL Licence
 * @link     ''
 */

defined( 'ABSPATH' ) || die( 'Access denied!' );

/**
 * Crons setting class
 *
 * @link     ''
 */
class CronsSettings {
	/**
	 * Construct function
	 */
	// public function __construct() {
	// add_action( 'wp_ajax_send_test_email_action', array( $this, 'send_test_email_action_callback' ) );
	// }.

	/**
	 * Funtion cron settings test callback.
	 *
	 * @since 1.0.0
	 */
	public function crons_settings_callback() {
		$email_crons_users_nonce = wp_create_nonce( 'email_crons_save_users_nonce_value' );
		?>
		<p>Select the crons settings here.</p>
		<form class="emailForm">
			<table class="form-table email-crons-every-cron-time" role="presentation">
				<tbody>
					<tr>
						<th scope="row"><label for="every_cron_time">Organize every email (in seconds)</label></th>
						<td>
							<input type="number" name="every_cron_time" min="1"	placeholder="60 seconds" />
							<p class="description" id="home-description"><i>Enter the number of time in seconds at what duration you want to organize sending mails<i/></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="user_chunk">Organize users</label></th>
						<td>
							<input type="number" name="user_chunk" min="1"	placeholder="5 users" />
							<p class="description" id="home-description"><i>Enter the number users you want to schedule sending mails at once<i/></p>
						</td>
					</tr>
				<tbody>
			</table>
			<input type="button" name="save_crons_settings" id="save_crons_settings" class="button button-primary" value="Save Settings">
			<input type="hidden" name="action" value="email_crons_save_cron_settings">
			<input type="hidden" name="email_crons_save_settings_nonce" value="<?php echo esc_attr( $email_crons_users_nonce ); ?>" />	
		</form>
		<?php
	}
}

$crons_settings = new CronsSettings();
