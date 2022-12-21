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
		global $wp_roles;
		$user_roles              = array_keys( $wp_roles->roles );
		$email_crons_users_nonce = wp_create_nonce( 'email_crons_save_users_nonce_value' );
		$selected_roles          = get_option( 'email_crons_roles_chunk', true ) ? get_option( 'email_crons_roles_chunk', true ) : '';
		$selected_users          = get_option( 'email_crons_users_chunk', true ) ? get_option( 'email_crons_users_chunk', true ) : '';
		$selected_users_count    = is_array( $selected_users ) ? count( $selected_users ) : '';
		$all_users               = ! isset( $selected_roles ) ? get_users() : get_users( array( 'role__in' => $selected_roles ) );
		$all_users_id            = array_column( $all_users, 'user_login' );
		$select_all_checked      = count( $all_users_id ) === $selected_users_count ? 'checked' : '';
		?>
		<p>Select the users to whom you want to send your mail template.</p>
			<div>
				<label>Select role(s):</label></br>
			</div>
			<div>
				<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="email_crons_save_users_form"> 
					<table>
						<tbody>
							<tr>
								<select id="email_crons_roles" name="email_crons_roles[]" multiple="multiple" style="width:300px">
								<?php
								foreach ( $user_roles as $user_role ) {
									$selected_roles_label = ( '' !== $selected_roles ) ? ( in_array( $user_role, $selected_roles, true ) ? 'selected' : '' ) : '';
									?>
											<option value="<?php echo esc_attr( $user_role ); ?>" <?php echo esc_attr( $selected_roles_label ); ?>>
											<?php
											echo esc_attr( $user_role ) . '(' . esc_attr( ucfirst( $user_role ) ) . ')';
											?>
											</option>
										<?php
								}
								?>
								</select>
							</tr>
						</tbody>
					</table>
						<?php
						submit_button( __( 'Save Users', 'email-crons' ) );
						?>
					<input type="hidden" name="action" value="email_crons_save_users">
					<input type="hidden" name="email_crons_users_nonce" value="<?php echo esc_attr( $email_crons_users_nonce ); ?>" />			
				</form>
			</div>
			<?php
	}

	/**
	 * Save users callback function
	 */
	public function email_crons_save_users_callback() {
		if ( isset( $_POST['email_crons_users_nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['email_crons_users_nonce'] ), 'email_crons_save_users_nonce_value' ) ) { //phpcs:ignore
			$roles = isset( $_POST['email_crons_roles'] ) ? $_POST['email_crons_roles'] : ''; //phpcs:ignore
			update_option( 'email_crons_roles_chunk', $roles );
		}
		wp_safe_redirect( admin_url( 'admin.php?page=email-crons.php&tab=users' ) );
		exit;
	}
}

$users_selection = new UsersSelection();