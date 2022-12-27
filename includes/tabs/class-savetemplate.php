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
					<p><strong><?php echo esc_attr( 'Template saved!' ); ?></strong></p>
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
		$default_subject                   = get_option( 'default_subject' );
		$default_template                  = get_option( 'default_template' );
		$subject                           = get_option( 'email_crons_email_subject' ) ? get_option( 'email_crons_email_subject' ) : $default_subject;
		$content                           = get_option( 'email_crons_email_template_editor_name' ) ? get_option( 'email_crons_email_template_editor_name' ) : $default_template;
		$customize_variable_preview_check  = get_option( 'customize_variable_preview' ) ? 'checked' : '';
		$customize_global_variable_options = get_option( 'customize_global_variable_options' ) ? get_option( 'customize_global_variable_options' ) : '';
		$custom_editor_id                  = 'email_crons_email_template_editor';
		$custom_editor_name                = 'email_crons_email_template_editor_name';

		$args                                = array(
			'default_editor' => 'tinymce',
			'media_buttons'  => true,
			'textarea_name'  => $custom_editor_name,
			'editor_height'  => 400,
			'textarea_rows'  => $content,
			'quicktags'      => true,
			'tinymce'        => true,
		);
		$nds_add_meta_nonce                  = wp_create_nonce( 'email_crons_save_template_nonce_value' );
		$email_crons_progress_check          = get_transient( 'email_crons_progress_check' );
		$email_crons_progress_check_disabled = get_transient( 'email_crons_progress_check' ) ? 'disabled' : '';

		?>
			<div class="cron_settings_message notice">
				<p></p>
			</div>
			<p>Create your email template here.</p>
			<div class="email_crons_save_template_row">
				<div class="email_crons_save_template_col one">
					<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="nds_add_user_meta_form"> 
						<h2>Subject</h2>
						<input name="email_crons_email_subject" class="email_crons_subject" id="email" value="<?php echo esc_attr( $subject ); ?>"  tabindex="2" type="text" placeholder="Enter subject">

						<h2>Content</h2>
						<?php wp_editor( $content, $custom_editor_id, $args ); ?>
						<p id="customize_variable_preview"><label for="customize_variable_preview"><input name="customize_variable_preview" type="checkbox" id="customize_variable_preview" <?php echo esc_attr( $customize_variable_preview_check ); ?>>
							Customize %USER% with different values.</label>
						</p>
						<?php
						if ( $customize_variable_preview_check ) :
							?>
							<select name="customize_global_variable_options" id="customize_global_variable_options">
								<option value="email" <?php echo ( 'email' === $customize_global_variable_options ) ? 'selected' : ''; ?>>Email address</option>
								<option value="first" <?php echo ( 'first' === $customize_global_variable_options ) ? 'selected' : ''; ?>>First name</option>
								<option value="second" <?php echo ( 'second' === $customize_global_variable_options ) ? 'selected' : ''; ?>>Second name</option>
								<option value="nickname" <?php echo ( 'nickname' === $customize_global_variable_options ) ? 'selected' : ''; ?>>Nickname</option>
								<option value="display" <?php echo ( 'display' === $customize_global_variable_options ) ? 'selected' : ''; ?>>Display name</option>
							</select>
							<p class="description">Choose any one value which will replace <i>%USER%</i>.</p>
							<?php
							endif;
						?>
						<input type="hidden" name="action" value="email_crons_save_template">
						<input type="hidden" name="email_crons_template_nonce" value="<?php echo esc_attr( $nds_add_meta_nonce ); ?>" />			
						<?php submit_button( __( 'Save Template', 'email-crons' ) ); ?>
					</form>
				</div>
				<div  class="email_crons_save_template_col two">
					<h2>Start Campaign</h2>
					<p>Send this email template to all users according to <a href="admin.php?page=email-crons.php&tab=cron-settings">cron settings</a> tab.
					<p class='start_sending_email'>
						<input type="button" name="start_sending_email_button" id="start_sending_email_button" class="button" value="Send Email to Users" <?php echo esc_attr( $email_crons_progress_check_disabled ); ?>>
						<?php if ( '1' === $email_crons_progress_check ) { ?>
							<p class="description in_progress"><strong>In Progress. Can not start another campaign until this one is finished.</strong></p>
						<?php } ?>
						<p class="description"><strong>CARFUL:</strong> Please double-check the subject and template before pressing this button, since the campaign will start and all recipients will receive an email.</p>
					</p>
					<hr/>
					<h2>Trivia</h2>
					<p class="description trivia"><strong>%USER% :</strong> This global variable can be used anywhere (subject or content) and it will be replaced with the user's data (first name, second name, email, etc). <br/><a href="#customize_variable_preview">Check this.</a></p>
					<hr>
				</div>
			</div>
		<?php
	}


	/**
	 * Function email_crons_save_template_callback callback.
	 *
	 * @since 1.0.0
	 */
	public function email_crons_save_template_callback() {
		if ( isset( $_POST['email_crons_template_nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['email_crons_template_nonce'] ), 'email_crons_save_template_nonce_value' ) ) { //phpcs:ignore
			$subject                           = isset( $_POST['email_crons_email_subject'] ) ? sanitize_text_field( wp_unslash( $_POST['email_crons_email_subject'] ) ) : '';
			$content                           = isset( $_POST['email_crons_email_template_editor_name'] ) ? wp_kses_post( wp_unslash( $_POST['email_crons_email_template_editor_name'] ) ) : '';
			$customize_global_variable_options = isset( $_POST['customize_global_variable_options'] ) ? wp_kses_post( wp_unslash( $_POST['customize_global_variable_options'] ) ) : '';
			$customize_variable_preview = isset( $_POST['customize_variable_preview'] ) ? $_POST['customize_variable_preview'] : ''; //phpcs:ignore
			if ( ! empty( $subject ) ) {
				update_option( 'email_crons_email_subject', $subject );
			}
			if ( ! empty( $content ) ) {
				update_option( 'email_crons_email_template_editor_name', $content );
			}
			if ( ! empty( $customize_global_variable_options ) ) {
				update_option( 'customize_global_variable_options', $customize_global_variable_options );
			}
			update_option( 'customize_variable_preview', $customize_variable_preview );
			set_transient( 'update_success', 'update_success' );
		} else {
			set_transient( 'update_error', 'update_error' );
		}
		wp_safe_redirect( admin_url( 'admin.php?page=email-crons.php' ) );
		die();
	}
}

$save_template = new SaveTemplate();
