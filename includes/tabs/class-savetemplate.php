<?php
/**
 * Save template class file
 *
 * @category Plugin
 * @package  EmailCrons
 * @author   Infobeans <infobeans@infobeans.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GPL Licence
 * @link     ''
 */

defined( 'ABSPATH' ) || die( 'Access denied!' );

/**
 * SaveTemplate class
 *
 * @link     ''
 */
class SaveTemplate {
	/**
	 * Construct function
	 */
	public function __construct() {
		add_action( 'admin_post_email_crons_save_template', array( $this, 'email_crons_save_template_callback' ) );
	}

	/**
	 * Function email template tab callback.
	 *
	 * @since 1.0.0
	 */
	public function email_crons_email_template_tab_callback() {
		?>
			<!-- <h3>Write Your E-mail Template</h3> -->
		<?php

		if ( 'update_success' === get_transient( 'update_success' ) ) {
			?>
				<div class="notice notice-success is-dismissible">
					<p><?php echo esc_attr( 'Template saved!' ); ?></p>
				</div>
			<?php
			delete_transient( 'update_success' );
		}
		if ( 'update_fail' === get_transient( 'update_fail' ) ) {
			?>
			<div class="notice notice-error">
				<p><?php echo esc_attr( 'An error has occurred' ); ?></p>
			</div>
			<?php
			delete_transient( 'update_fail' );
		}
		$subject            = get_option( 'email_crons_email_subject' ) ? get_option( 'email_crons_email_subject' ) : '';
		$content            = get_option( 'email_crons_email_template_editor_name' ) ? get_option( 'email_crons_email_template_editor_name' ) : '';
		$custom_editor_id   = 'email_crons_email_template_editor';
		$custom_editor_name = 'email_crons_email_template_editor_name';

		$args               = array(
			'default_editor' => 'tinymce',
			'media_buttons'  => true,
			'textarea_name'  => $custom_editor_name,
			'editor_height'  => 500,
			'textarea_rows'  => $content,
			'quicktags'      => true,
			'tinymce'        => true,
		);
		$nds_add_meta_nonce = wp_create_nonce( 'email_crons_save_template_nonce_value' );

		?>
		<p>This is where you create your email template.</p>
		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="nds_add_user_meta_form"> 
			<h2>Subject</h2>
				<input name="email_crons_email_subject" class="email_crons_subject" id="email" value="<?php echo esc_attr( $subject ); ?>"  tabindex="2" type="text" placeholder="Enter subject">
			<h2>Content</h2>
			<?php
				wp_editor( $content, $custom_editor_id, $args );
				submit_button( __( 'Save Template', 'email-crons' ) );
			?>
			<input type="hidden" name="action" value="email_crons_save_template">
			<input type="hidden" name="email_crons_template_nonce" value="<?php echo esc_attr( $nds_add_meta_nonce ); ?>" />			
		</form>
		<?php
	}


	/**
	 * Function email_crons_save_template_callback callback.
	 *
	 * @since 1.0.0
	 */
	public function email_crons_save_template_callback() {
		if ( isset( $_POST['email_crons_template_nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['email_crons_template_nonce'] ), 'email_crons_save_template_nonce_value' ) ) { //phpcs:ignore
			$subject = isset( $_POST['email_crons_email_subject'] ) ? sanitize_text_field( wp_unslash( $_POST['email_crons_email_subject'] ) ) : '';
			$content = isset( $_POST['email_crons_email_template_editor_name'] ) ? wp_kses_post( wp_unslash( $_POST['email_crons_email_template_editor_name'] ) ) : '';
			if ( ! empty( $subject ) ) {
				update_option( 'email_crons_email_subject', $subject );
			}
			if ( ! empty( $content ) ) {
				update_option( 'email_crons_email_template_editor_name', $content );
			}
			set_transient( 'update_success', 'update_success' );
		} else {
			set_transient( 'update_error', 'update_error' );
		}
		wp_safe_redirect( admin_url( 'admin.php?page=email-crons.php' ) );
		die();
	}
}

$save_template = new SaveTemplate();
