<?php
/**
 * Email test class file
 *
 * @category Plugin
 * @package  EmailCrons
 * @author   Infobeans <infobeans@infobeans.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GPL Licence
 * @link     ''
 */

defined( 'ABSPATH' ) || die( 'Access denied!' );

/**
 * EmailTest class
 *
 * @link     ''
 */
class EmailTest {
	/**
	 * Construct function
	 */
	public function __construct() {
		add_action( 'wp_ajax_send_test_email_action', array( $this, 'send_test_email_action_callback' ) );
	}

	/**
	 * Funtion email test callback.
	 *
	 * @since 1.0.0
	 */
	public function email_test_callback() {
		?>
			<div class="test_message notice">
				<p><strong></strong></p>
			</div>
			<p>Test the email response by adding your email address here.</p>
			<form class="emailForm">
				<table class="form-table email-crons-email-test" role="presentation">
					<tbody>
						<tr>
							<th scope="row"><label for="email_crons_test_email">Test Email:</label></th>
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
	public function send_test_email_action_callback() {
		$recipient = $_POST['email']; //phpcs:ignore
		$subject   = get_option( 'email_crons_email_subject' );
		$message   = get_option( 'email_crons_email_template_editor_name' );
		$headers   = array( 'Content-Type: text/html; charset=UTF-8' );

		$send_test_mail = false;
		$json_response  = array();
		$send_test_mail = wp_mail( $recipient, $subject, $message, $headers );

		if ( ! empty( $recipient ) && ( true === $send_test_mail ) ) {
			$json_response = array(
				'status'  => $send_test_mail,
				'message' => 'Test mail has been sent successfully.',
			);
			wp_send_json_success( $json_response, 200 );
		} elseif ( empty( $recipient ) && ( false === $send_test_mail ) ) {
			$json_response = array(
				'status'  => $send_test_mail,
				'message' => 'Email field can not be empty.',
			);
			wp_send_json_error( $json_response, 200 );
		} else {
			$json_response = array(
				'status'  => $send_test_mail,
				'message' => 'Something wrong with Email/SMTP settings.',
			);
			wp_send_json_error( $json_response, 200 );
		}
		exit;
	}
}

$email_test = new EmailTest();
