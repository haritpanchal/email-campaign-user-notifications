<?php
/**
 * Config class file
 *
 * @category Plugin
 * @package  Email Campaign User Notifications
 * @author   Harit Panchal <https://profiles.wordpress.org/haritpanchal>
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GPL Licence
 * @link     ''
 */

defined( 'ABSPATH' ) || die( 'Access denied!' );

/**
 * ECUN_Config class
 *
 * @link     ''
 */
class ECUN_Config {
	/**
	 * Construct function
	 */
	public function __construct() {
		global $ecun_tabs;
		$ecun_tabs = array(
			'email_template' => array(
				'name' => 'Add Email Template',
				'tab'  => 'email_template',
			),
			'user_selection' => array(
				'name' => 'Users Selection',
				'tab'  => 'user_selection',
			),
			'cron_settings'  => array(
				'name' => 'Cron Settings',
				'tab'  => 'cron_settings',
			),
			'email_test'     => array(
				'name' => 'Test Email',
				'tab'  => 'email_test',
			),
		);
	}
}

$ecun_config = new ECUN_Config();
