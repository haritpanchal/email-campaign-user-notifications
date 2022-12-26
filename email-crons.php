<?php
/**
 * User email campaign notifications
 *
 * @package           EmailCrons
 * @author            Harit Panchal
 * @copyright         2022 Harit Panchal
 * @license           GPL-2.0
 *
 * @wordpress-plugin
 * Plugin Name:       User email campaign notifications
 * Description:       Description of the plugin.
 * Version:           1.0.0
 * Requires at least: 6.1
 * Requires PHP:      8.0.25
 * Author:            Harit Panchal
 * Author URI:        https://profiles.wordpress.org/haritpanchal
 * Text Domain:       email-crons
 * License:           GPL v2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

defined( 'ABSPATH' ) || die( 'Access denied!' );

require_once plugin_dir_path( __FILE__ ) . 'includes/class-addmenupage.php';

/**
 * Function add or delete option on plugin activation or deactivation.
 */
function email_crons_activate_callback() {

	if ( get_option( 'Activated_Plugin' ) ) {
		delete_option( 'Activated_Plugin' );
	} else {
		add_option( 'Activated_Plugin', 'emial-crons' );
	}
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'email_crons_activate_callback' );
register_deactivation_hook( __FILE__, 'email_crons_activate_callback' );
