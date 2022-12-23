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
	public function __construct() {
		add_action( 'admin_post_email_crons_save_cron_settings', array( $this, 'email_crons_save_cron_settings_callback' ) );
	}

	/**
	 * Funtion cron settings test callback.
	 *
	 * @since 1.0.0
	 */
	public function crons_settings_callback() {
		$email_crons_save_settings_nonce = wp_create_nonce( 'email_crons_save_settings_nonce_value' );
		$every_cron_time                 = get_option( 'email_crons_every_cron_time', true ) ? get_option( 'email_crons_every_cron_time', true ) : '';
		$user_chunk                      = get_option( 'email_crons_user_chunk', true ) ? get_option( 'email_crons_user_chunk', true ) : '';
		?>
		<div class="cron_settings_message notice">
			<p></p>
		</div>
		<p>Select the crons settings here.</p>
		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="emailForm" class="emailForm">
			<table class="form-table email-crons-every-cron-time" role="presentation">
				<tbody>
					<tr>
						<th scope="row"><label for="every_cron_time">Organize every email (in seconds)</label></th>
						<td>
							<input type="number" name="every_cron_time" min="1"	placeholder="60 seconds" value="<?php echo esc_attr( $every_cron_time ); ?>" />
							<p class="description" id="home-description"><i>Enter the number of time in seconds at what duration you want to organize sending mails<i/></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="user_chunk">Organize users</label></th>
						<td>
							<input type="number" name="user_chunk" min="1"	placeholder="5 users" value="<?php echo esc_attr( $user_chunk ); ?>" />
							<p class="description" id="home-description"><i>Enter the number users you want to schedule sending mails at once<i/></p>
						</td>
					</tr>
				<tbody>
			</table>
			<div class="email_crons_flex">
				<?php
					submit_button( __( 'Save Settings', 'email-crons' ) );
				?>
				<p class='start_sending_email'>
					<input type="button" name="start_sending_email_button" id="start_sending_email_button" class="button" value="Start Sending Emails">
				</p>
			</div>
			<input type="hidden" name="action" value="email_crons_save_cron_settings">
			<input type="hidden" name="email_crons_save_settings_nonce" value="<?php echo esc_attr( $email_crons_save_settings_nonce ); ?>" />	
		</form>
		<?php
	}

	/**
	 * Funtion cron save settings test callback.
	 *
	 * @since 1.0.0
	 */
	public function email_crons_save_cron_settings_callback() {

		if ( isset( $_POST['email_crons_save_settings_nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['email_crons_save_settings_nonce'] ), 'email_crons_save_settings_nonce_value' ) ) { //phpcs:ignore
			$every_cron_time = isset( $_POST['every_cron_time'] ) ? $_POST['every_cron_time'] : ''; //phpcs:ignore
			$user_chunk = isset( $_POST['user_chunk'] ) ? $_POST['user_chunk'] : ''; //phpcs:ignore

			if ( ! empty( $every_cron_time ) && ! empty( $user_chunk ) ) {
				update_option( 'email_crons_every_cron_time', $every_cron_time );
				update_option( 'email_crons_user_chunk', $user_chunk );
			}
		}
		wp_safe_redirect( admin_url( 'admin.php?page=email-crons.php&tab=cron-settings' ) );
		exit;
	}
}

$crons_settings = new CronsSettings();
