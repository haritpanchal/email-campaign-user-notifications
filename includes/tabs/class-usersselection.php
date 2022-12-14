<?php
/**
 * User selection class file
 *
 * @category Plugin
 * @package  EmailCrons
 * @author   Infobeans <infobeans@infobeans.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GPL Licence
 * @link     ''
 */

defined( 'ABSPATH' ) || die( 'Access denied!' );

/**
 * UsersSelection class
 *
 * @link     ''
 */
class UsersSelection {

	/**
	 * Construct function
	 */
	public function __construct() {
		add_action( 'admin_post_email_crons_save_users', array( $this, 'email_crons_save_users_callback' ) );
	}

	/**
	 * Users selection callback function
	 */
	public function users_selection_callback() {
		$email_crons_users_nonce = wp_create_nonce( 'email_crons_save_users_nonce_value' );
		$all_users               = get_users();
		?>
		<p>Select the users to whom you want to send your mail template.</p>
		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="email_crons_save_users_form"> 
			<table>
				<tbody>
					<tr>
						<input id="email_crons_select_all" type="checkbox" >Select All
						<td>
							<select id="email_crons_users" multiple style="width:300px">
								<?php
								foreach ( $all_users as $user ) {
									$user_id    = $user->data->ID;
									$user_name  = $user->data->display_name;
									$user_email = $user->data->user_email;
									?>
										<option value="<?php echo esc_attr( $user_id ); ?>">
										<?php
										echo esc_attr( $user_name ) . '(' . esc_attr( $user_email ) . ')';
										?>
										</option>
									<?php
								}
								?>
							</select>
						</td>
					</tr>
				</tbody>
			</table>
			<?php
				submit_button( __( 'Save Users', 'email-crons' ) );
			?>
			<input type="hidden" name="action" value="email_crons_save_users">
			<input type="hidden" name="email_crons_users_nonce" value="<?php echo esc_attr( $email_crons_users_nonce ); ?>" />			
		</form>
		<?php
	}

	/**
	 * Save users callback function
	 */
	public function email_crons_save_users_callback() {
		echo '<pre>';
		print_r( $_POST );
		exit;
	}
}

$users_selection = new UsersSelection();
