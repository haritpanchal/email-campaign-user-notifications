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
		// add_action( 'admin_notices', array( $this, 'sample_admin_notice__success' ) );
		// add_action( 'admin_notices', array( $this, 'sample_admin_notice__error' ) );
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
		$tab         = isset( $_GET['tab'] ) ? $_GET['tab'] : $default_tab;
		$screen      = get_current_screen();

		$active_class = '';

		if ( null === $tab ) {
			$default = 'nav-tab-active';
		}
		if ( 'users' === $tab ) {
			$users = 'nav-tab-active';
		}

		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

			<nav class="nav-tab-wrapper">
				<a href="?page=<?php echo esc_attr( $screen->parent_file ); ?>" class="nav-tab <?php echo esc_attr( $default ); ?>">Email Template</a>
				<a href="?page=<?php echo esc_attr( $screen->parent_file ); ?>&tab=users" class="nav-tab <?php echo esc_attr( $users ); ?>">Users</a>
			</nav>

			<div class="tab-content">
				<?php
				switch ( $tab ) :
					case 'users':
						echo 'users';
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
			<h3>Write Your E-mail Template</h3>
		<?php
		$content            = '';
		$custom_editor_id   = 'email_crons_email_template_editor';
		$custom_editor_name = 'email_crons_email_template_editor_name';
		$args               = array(
			'media_buttons' => false,
			'textarea_name' => $custom_editor_name,
			'textarea_rows' => get_option( 'default_post_edit_rows', 10 ),
			'quicktags'     => true,
		);
		wp_editor( $content, $custom_editor_id, $args );

		submit_button( __( 'Save Template', 'email-crons' ) );

	}

	public function sample_admin_notice__success() {
		?>
		<div class="notice notice-success is-dismissible">
			<p><?php _e( 'Done!', 'sample-text-domain' ); ?></p>
		</div>
		<?php
	}

	public function sample_admin_notice__error() {
		$class   = 'notice notice-error';
		$message = __( 'Irks! An error has occurred.', 'sample-text-domain' );

		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
	}

}

$add_menu_page = new AddMenuPage();
