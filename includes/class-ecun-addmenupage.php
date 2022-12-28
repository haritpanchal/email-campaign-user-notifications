<?php
/**
 * Add menu page class file
 *
 * @category Plugin
 * @package  Email Campaign User Notifications
 * @author   Harit Panchal <https://profiles.wordpress.org/haritpanchal>
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GPL Licence
 * @link     ''
 */

defined( 'ABSPATH' ) || die( 'Access denied!' );

require 'tabs/class-ecun-savetemplate.php';
require 'tabs/class-ecun-emailtest.php';
require 'tabs/class-ecun-usersselection.php';
require 'tabs/class-ecun-cronssettings.php';
require 'settings/class-ecun-sendemail.php';

/**
 * ECUN_AddMenuPage class
 *
 * @link     ''
 */
class ECUN_AddMenuPage {
	/**
	 * Construct function
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'ecun_email_crons_register_admin' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'ecun_email_crons_register_scripts' ) );
	}

	/**
	 * Funtion register admin menu page.
	 *
	 * @since 1.0.0
	 */
	public function ecun_email_crons_register_admin() {
		$GLOBALS['email-crons-template'] = add_menu_page(
			'Email Campaign User Notifications',
			'Email Campaign',
			'manage_options',
			'email-crons.php',
			array( $this, 'ecun_email_crons_template' ),
			'dashicons-clock',
			2
		);
	}

	/**
	 * Funtion enqueue/register scripts.
	 *
	 * @param string $hook global parameter.
	 *
	 * @since 1.0.0
	 */
	public function ecun_email_crons_register_scripts( $hook ) {
		if ( $GLOBALS['email-crons-template'] === $hook ) {
			wp_enqueue_style( 'email-crons-style', plugin_dir_url( __DIR__ ) . 'assets/css/style.css', '', '1.0', '', );
			wp_enqueue_style( 'email-crons-select2-style', plugin_dir_url( __DIR__ ) . 'assets/css/select2.min.css', '', '4.0.13', '', );
			wp_enqueue_script( 'email-crons-script', plugin_dir_url( __DIR__ ) . 'assets/js/script.js', '', '1.0', true );
			wp_enqueue_script( 'email-crons-select2-script', plugin_dir_url( __DIR__ ) . 'assets/js/select2.min.js', '', '4.0.13', true );
			wp_localize_script(
				'email-crons-script',
				'localize_variable',
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
				)
			);
		}
	}

	/**
	 * Function admin menu page callback.
	 *
	 * @since 1.0.0
	 */
	public function ecun_email_crons_template() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$default_tab = null;
		$tab         = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : $default_tab; // phpcs:ignore
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

		if ( 'cron-settings' === $tab ) {
			$cron_settings = 'nav-tab-active';
		}

		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

			<nav class="nav-tab-wrapper">
				<a href="?page=<?php echo esc_attr( $screen->parent_file ); ?>" class="nav-tab <?php echo esc_attr( $default ); ?>"><?php echo esc_html( 'Email Template' ); ?></a>
				<a href="?page=<?php echo esc_attr( $screen->parent_file ); ?>&tab=users" class="nav-tab <?php echo esc_attr( $users ); ?>"><?php echo esc_html( 'Users Selection' ); ?></a>
				<a href="?page=<?php echo esc_attr( $screen->parent_file ); ?>&tab=cron-settings" class="nav-tab <?php echo esc_attr( $cron_settings ); ?>"><?php echo esc_html( 'Cron Settings' ); ?></a>
				<a href="?page=<?php echo esc_attr( $screen->parent_file ); ?>&tab=email-test" class="nav-tab <?php echo esc_attr( $email_test ); ?>"><?php echo esc_html( 'Test Email ' ); ?></a>
			</nav>

			<div class="tab-content">
				<?php
				switch ( $tab ) :
					case 'users':
						$users_selection = new ECUN_UsersSelection();
						$users_selection->ecun_users_selection_callback();
						break;
					case 'cron-settings':
						$crons_settings = new ECUN_CronsSettings();
						$crons_settings->ecun_crons_settings_callback();
						break;
					case 'email-test':
						$email_test = new ECUN_EmailTest();
						$email_test->ecun_email_test_callback();
						break;
					default:
						$save_templage = new ECUN_SaveTemplate();
						$save_templage->ecun_email_crons_email_template_tab_callback();
						break;
				endswitch;
				?>
			</div>
		</div>
		<?php
	}
}

$add_menu_page = new ECUN_AddMenuPage();
