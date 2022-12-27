<?php
/**
 * Email test class file
 *
 * @category Plugin
 * @package  Email Campaign User Notifications
 * @author   Harit Panchal <https://profiles.wordpress.org/haritpanchal>
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GPL Licence
 * @link     ''
 */

defined( 'ABSPATH' ) || die( 'Access denied!' );

/**
 * ECUN_EmailTest class
 *
 * @link     ''
 */
class ECUN_EmailTest {
	/**
	 * Construct function
	 */
	public function __construct() {
		add_action( 'wp_ajax_send_test_email_action', array( $this, 'ecun_send_test_email_action_callback' ) );
	}

	/**
	 * Funtion email test content.
	 *
	 * @since 1.0.0
	 */
	public function ecun_email_test_callback() {
		?>
			<div class="test_message notice">
				<p><strong></strong></p>
			</div>
			<p><?php echo esc_html( 'Test the email response by adding your email address here.' ); ?></p>
			<form class="emailForm">
				<table class="form-table email-crons-email-test" role="presentation">
					<tbody>
						<tr>
							<th scope="row"><label for="email_crons_test_email"><?php echo esc_html( 'Test Email:' ); ?></label></th>
							<td><input name="email_crons_test_email" type="text" id="email_crons_test_email"  placeholder="Enter test email" class="regular-text"></td>
						</tr>
					<tbody>
				</table>
				<input type="button" name="email_crons_sent_test_email" id="email_crons_sent_test_email" class="button button-primary" value="Send Test Email">
			</form>
		<?php
	}

	/**
	 * Funtion email test callback.
	 *
	 * @since 1.0.0
	 */
	public function ecun_send_test_email_action_callback() {
		$recipient = $_POST['email']; //phpcs:ignore
		$subject   = get_option( 'email_crons_email_subject' );
		$message   = get_option( 'email_crons_email_template_editor_name' );
		$headers   = array( 'Content-Type: text/html; charset=UTF-8' );
		$subject   = str_replace( '%USER%', 'User', $subject );
		$message   = str_replace( '%USER%', 'User', $message );

		$send_test_mail = false;
		$json_response  = array();
		$send_test_mail = wp_mail( $recipient, $subject, $message, $headers );

		if ( ! empty( $recipient ) && ( true === $send_test_mail ) ) {
			$json_response = array(
				'status'  => $send_test_mail,
				'message' => __( 'Test mail has been sent successfully.', 'email-crons' ),
			);
			wp_send_json_success( $json_response, 200 );
		} elseif ( empty( $recipient ) && ( false === $send_test_mail ) ) {
			$json_response = array(
				'status'  => $send_test_mail,
				'message' => __( 'Email field can not be empty.', 'email-crons' ),
			);
			wp_send_json_error( $json_response, 200 );
		} else {
			$json_response = array(
				'status'  => $send_test_mail,
				'message' => __( 'Something wrong with Email/SMTP settings.', 'email-crons' ),
			);
			wp_send_json_error( $json_response, 200 );
		}
		exit;
	}
}

$email_test = new ECUN_EmailTest();
