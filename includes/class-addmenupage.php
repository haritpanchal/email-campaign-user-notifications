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

		// $GLOBALS['emsc-email-test'] = add_submenu_page(
		// 'emsc-template.php',
		// 'Email Test',
		// 'Email Test',
		// 'manage_options',
		// 'emsc-email-test.php',
		// array( $this, 'emsc_testing_content' ),
		// 'dashicons-share-alt2',
		// );
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
		echo 'here';
	}

}

$add_menu_page = new AddMenuPage();
