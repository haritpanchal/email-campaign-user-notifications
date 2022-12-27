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

require 'tabs/class-savetemplate.php';
require 'tabs/class-emailtest.php';
require 'tabs/class-usersselection.php';
require 'tabs/class-cronssettings.php';
require 'settings/class-sendemail.php';

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
		add_action( 'admin_enqueue_scripts', array( $this, 'email_crons_register_scripts' ) );
	}

	/**
	 * Funtion register admin menu page.
	 *
	 * @since 1.0.0
	 */
	public function email_crons_register_admin() {
		$GLOBALS['email-crons-template'] = add_menu_page(
			'User Email Campaign Notifications',
			'Email Campaign',
			'manage_options',
			'email-crons.php',
			array( $this, 'email_crons_template' ),
			'dashicons-clock',
			2
		);
	}

	/**
	 * Funtion register admin menu page.
	 *
	 * @param string $hook global parameter.
	 *
	 * @since 1.0.0
	 */
	public function email_crons_register_scripts( $hook ) {
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

		if ( 'cron-settings' === $tab ) {
			$cron_settings = 'nav-tab-active';
		}

		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

			<nav class="nav-tab-wrapper">
				<a href="?page=<?php echo esc_attr( $screen->parent_file ); ?>" class="nav-tab <?php echo esc_attr( $default ); ?>">Email Template</a>
				<a href="?page=<?php echo esc_attr( $screen->parent_file ); ?>&tab=users" class="nav-tab <?php echo esc_attr( $users ); ?>">Users Selection</a>
				<a href="?page=<?php echo esc_attr( $screen->parent_file ); ?>&tab=cron-settings" class="nav-tab <?php echo esc_attr( $cron_settings ); ?>">Cron Settings</a>
				<a href="?page=<?php echo esc_attr( $screen->parent_file ); ?>&tab=email-test" class="nav-tab <?php echo esc_attr( $email_test ); ?>">Email Test</a>
			</nav>

			<div class="tab-content">
				<?php
				switch ( $tab ) :
					case 'users':
						$users_selection = new UsersSelection();
						$users_selection->users_selection_callback();
						break;
					case 'cron-settings':
						$crons_settings = new CronsSettings();
						$crons_settings->crons_settings_callback();
						break;
					case 'email-test':
						$email_test = new EmailTest();
						$email_test->email_test_callback();
						break;
					default:
						$save_templage = new SaveTemplate();
						$save_templage->email_crons_email_template_tab_callback();
						break;
				endswitch;
				?>
			</div>
		</div>
		<?php
	}
}

$add_menu_page = new AddMenuPage();
