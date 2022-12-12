<?php
/**
 * Add menu page class file
 *
 * @category Plugin
 * @package  EmailCrons
 * @author   Infobeans <infobeans@infobeans.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GPL Licence
 * @link     ''
 */

defined( 'ABSPATH' ) || die( 'Access denied!' );

/**
 * AddMenuPage class
 *
 * @link     ''
 */
class AddMenuPage {
	/**
	 * Construct function
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'email_crons_register_admin' ) );
		add_action( 'admin_post_nds_form_response', array( $this, 'the_form_response' ) );
	}

	/**
	 * Funtion register admin menu page.
	 *
	 * @since 1.0.0
	 */
	public function email_crons_register_admin() {
		$GLOBALS['email-crons-template'] = add_menu_page(
			'Email Crons',
			'Email Crons',
			'manage_options',
			'email-crons.php',
			array( $this, 'email_crons_template' ),
			'dashicons-clock',
			2
		);

		$GLOBALS['email-crons-users'] = add_submenu_page(
			'email-crons.php',
			'Email Test',
			'Email Test',
			'manage_options',
			'email-crons.php&tab=email-test',
			array( $this, 'email_crons_template' ),
			'dashicons-share-alt2',
		);

		$GLOBALS['email-crons-users'] = add_submenu_page(
			'email-crons.php',
			'Users',
			'Users',
			'manage_options',
			'email-crons.php&tab=users',
			array( $this, 'email_crons_template' ),
			'dashicons-share-alt2',
		);

	}

	/**
	 * Function admin menu page callback.
	 *
	 * @since 1.0.0
	 */
	public function email_crons_template() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$default_tab = null;
		$tab         = isset( $_GET['tab'] ) ? $_GET['tab'] : $default_tab; //phpcs:ignore
		$screen      = get_current_screen();

		$active_class = '';

		if ( null === $tab ) {
			$default = 'nav-tab-active';
		}
		if ( 'users' === $tab ) {
			$users = 'nav-tab-active';
		}
		if ( 'email-test' === $tab ) {
			$email_test = 'nav-tab-active';
		}

		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

			<nav class="nav-tab-wrapper">
				<a href="?page=<?php echo esc_attr( $screen->parent_file ); ?>" class="nav-tab <?php echo esc_attr( $default ); ?>">Email Template</a>
				<a href="?page=<?php echo esc_attr( $screen->parent_file ); ?>&tab=email-test" class="nav-tab <?php echo esc_attr( $email_test ); ?>">Email Test</a>
				<a href="?page=<?php echo esc_attr( $screen->parent_file ); ?>&tab=users" class="nav-tab <?php echo esc_attr( $users ); ?>">Users</a>
			</nav>

			<div class="tab-content">
				<?php
				switch ( $tab ) :
					case 'users':
						echo 'users';
						break;
					case 'email-test':
						echo 'email-test';
						break;
					default:
						$this->email_crons_email_template_tab_callback();
						break;
				endswitch;
				?>
			</div>
		</div>
		<?php

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

		if ( 'updated' === get_transient( 'updated_option' ) ) {
			?>
				<div class="notice notice-success is-dismissible">
					<p><?php echo esc_attr( 'Template saved!' ); ?></p>
				</div>
			<?php
			delete_transient( 'updated_option' );
		}
		if ( get_transient( 'update_fail' ) ) {
			?>
			<div class="notice notice-error">
				<p><?php echo esc_attr( 'An error has occurred' ); ?></p>
			</div>
			<?php
			delete_transient( 'update_fail' );
		}
		$content            = get_option( 'email_crons_email_template_editor_name' );
		$custom_editor_id   = 'email_crons_email_template_editor';
		$custom_editor_name = 'email_crons_email_template_editor_name';
		$args               = array(
			'media_buttons' => false,
			'textarea_name' => $custom_editor_name,
			'textarea_rows' => get_option( 'default_post_edit_rows', 10 ),
			'quicktags'     => true,
		);
		$nds_add_meta_nonce = wp_create_nonce( 'nds_add_user_meta_form_nonce' );

		?>
		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="nds_add_user_meta_form"> 
			<input type="hidden" name="action" value="nds_form_response">
			<input type="hidden" name="nds_add_user_meta_nonce" value="<?php echo esc_attr( $nds_add_meta_nonce ); ?>" />			
			<?php
				wp_editor( $content, $custom_editor_id, $args );
				submit_button( __( 'Save Template', 'email-crons' ) );
			?>
		</form>
		<?php
	}


	/**
	 * Function the_form_response callback.
	 *
	 * @since 1.0.0
	 */
	public function the_form_response() {
		if ( isset( $_POST['nds_add_user_meta_nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['nds_add_user_meta_nonce'] ), 'nds_add_user_meta_form_nonce' ) ) { //phpcs:ignore
			$content = isset( $_POST['email_crons_email_template_editor_name'] ) ? wp_kses_post( wp_unslash( $_POST['email_crons_email_template_editor_name'] ) ) : '';
			update_option( 'email_crons_email_template_editor_name', $content );
			set_transient( 'updated_option', 'updated', 10 );
			wp_safe_redirect( admin_url( 'admin.php?page=email-crons.php' ) );
			die();
		} else {
			set_transient( 'update_fail', 'fail', 10 );
			wp_safe_redirect( admin_url( 'admin.php?page=email-crons.php' ) );
			die();
			// wp_die( 'Invalid nonce specified' );
		}
	}
}

$add_menu_page = new AddMenuPage();
