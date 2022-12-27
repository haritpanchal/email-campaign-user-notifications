<?php
/**
 * User Email Campaign Notifications
 *
 * @package           EmailCrons
 * @author            Harit Panchal
 * @copyright         2022 Harit Panchal
 * @license           GPL-2.0
 *
 * @wordpress-plugin
 * Plugin Name:       User Email Campaign Notifications
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
	$default_subject  = 'Welcome %USER%';
	$default_template = '<table style="border: 15px solid #000; width: 70%; border-collapse: collapse; border-top-width: 7px; text-align: center; margin: auto; min-width: 320px; max-width: 600px;">
							<tbody>
								<tr>
									<td style="padding: 0;">
										<h2 style="margin: 0; font-size: 24px; padding: 20px 10px 20px 10px; font-family: sans-serif; text-align: center;">Welcome %USER%</h2>
									</td>
								</tr>
								<tr>
									<td class="text" style="line-height: 20px; font-size: 16px; padding: 40px 10px 74px 10px; font-family: sans-serif;">
									<div style="text-align: center;">Hi there,</div>
									<div style="text-align: center;">This is a dummy HTML template.</div>
								</td>
								</tr>
							</tbody>
						</table>';

	if ( get_option( 'Activated_Plugin' ) ) {
		delete_option( 'Activated_Plugin' );
	} else {
		add_option( 'Activated_Plugin', 'emial-crons' );
		update_option( 'default_subject', $default_subject );
		update_option( 'default_template', $default_template );
	}
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'email_crons_activate_callback' );
register_deactivation_hook( __FILE__, 'email_crons_activate_callback' );
