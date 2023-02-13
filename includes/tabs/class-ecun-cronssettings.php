<?php
/**
 * Crons setting class file
 *
 * @category Plugin
 * @package  Email Campaign User Notifications
 * @author   Harit Panchal <https://profiles.wordpress.org/haritpanchal>
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GPL Licence
 * @link     ''
 */

defined( 'ABSPATH' ) || die( 'Access denied!' );

/**
 * Crons setting class
 *
 * @link     ''
 */
class ECUN_CronsSettings {
	/**
	 * Construct function
	 */
	public function __construct() {
		add_action( 'admin_post_email_crons_save_cron_settings', array( $this, 'ecun_email_crons_save_cron_settings_callback' ) );
	}

	/**
	 * Funtion cron settings test callback.
	 *
	 * @since 1.0.0
	 */
	public function ecun_crons_settings_callback() {
		$email_crons_save_settings_nonce = wp_create_nonce( 'email_crons_save_settings_nonce_value' );
		$every_cron_time                 = get_option( 'email_crons_every_cron_time', true ) ? get_option( 'email_crons_every_cron_time', true ) : '60';
		$user_chunk                      = get_option( 'email_crons_user_chunk', true ) ? get_option( 'email_crons_user_chunk', true ) : '5';
		if ( 'cron_setting_update_success' === get_transient( 'cron_setting_update_success' ) ) {
			?>
				<div class="notice notice-success is-dismissible">
					<p><strong><?php echo esc_attr( 'Settings saved.' ); ?></strong></p>
				</div>
			<?php
			delete_transient( 'cron_setting_update_success' );
		}
		if ( 'cron_setting_update_fail' === get_transient( 'cron_setting_update_fail' ) ) {
			?>
			<div class="notice notice-error">
			<p><strong><?php echo esc_attr( 'Field(s) can not be empty.' ); ?></strong></p>
			</div>
			<?php
			delete_transient( 'cron_setting_update_fail' );
		}
		?>
		<p><?php echo esc_html( 'Select the crons settings here.' ); ?></p>
		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="emailForm" class="emailForm">
			<table class="form-table email-crons-every-cron-time" role="presentation">
				<tbody>
					<tr>
						<th scope="row"><label for="every_cron_time"><?php echo esc_html( 'Organize every email (in seconds)' ); ?></label></th>
						<td>
							<input type="number" name="every_cron_time" min="1"	placeholder="60 seconds" value="<?php echo esc_attr( $every_cron_time ); ?>" />
							<p class="description"><i><?php echo esc_html( 'Enter the number of time in seconds at what duration you want to organize sending mails' ); ?><i/></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="user_chunk">Organize users</label></th>
						<td>
							<input type="number" name="user_chunk" min="1"	placeholder="5 users" value="<?php echo esc_attr( $user_chunk ); ?>" />
							<p class="description"><i><?php echo esc_html( 'Enter the number users you want to schedule sending mails at once' ); ?><i/></p>
						</td>
					</tr>
				<tbody>
			</table>
			<div class="email_crons_flex">
				<?php
					submit_button( __( 'Save Settings', 'email-crons' ) );
				?>

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
	public function ecun_email_crons_save_cron_settings_callback() {

		if ( isset( $_POST['email_crons_save_settings_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['email_crons_save_settings_nonce'] ) ), 'email_crons_save_settings_nonce_value' ) ) {
			$every_cron_time = isset( $_POST['every_cron_time'] ) ? sanitize_text_field( wp_unslash( $_POST['every_cron_time'] ) ) : '';
			$user_chunk      = isset( $_POST['user_chunk'] ) ? sanitize_text_field( wp_unslash( $_POST['user_chunk'] ) ) : '';

			if ( ! empty( $every_cron_time ) && ! empty( $user_chunk ) ) {
				update_option( 'email_crons_every_cron_time', esc_attr( $every_cron_time ) );
				update_option( 'email_crons_user_chunk', esc_attr( $user_chunk ) );
				set_transient( 'cron_setting_update_success', 'cron_setting_update_success' );
			} else {
				set_transient( 'cron_setting_update_fail', 'cron_setting_update_fail' );
			}
		}
		wp_safe_redirect( admin_url( 'admin.php?page=email-crons.php&tab=cron_settings' ) );
		exit;
	}
}

$crons_settings = new ECUN_CronsSettings();
