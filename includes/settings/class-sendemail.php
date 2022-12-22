<?php
/**
 * Send email class file
 *
 * @category Plugin
 * @package  EmailCrons
 * @author   Infobeans <infobeans@infobeans.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GPL Licence
 * @link     ''
 */

defined( 'ABSPATH' ) || die( 'Access denied!' );

/**
 * SendEmail class
 *
 * @link     ''
 */
class SendEmail {
	/**
	 * Construct function
	 */
	public function __construct() {
		add_filter( 'cron_schedules', array( $this, 'email_crons_cron_schedules' ) ); //phpcs:ignore
		add_action( 'email_crons_call_email_template', array( $this, 'email_crons_schedule_cron' ) );
		add_action( 'wp_ajax_schedule_cron', array( $this, 'email_crons_schedule_cron_callback' ) );

	}

	/**
	 * Create cron timer of one minute
	 *
	 * @since 1.0.0
	 *
	 * @param Array $schedules An array of non-default cron schedules.
	 */
	public function email_crons_cron_schedules( $schedules ) {
		$email_crons_every_cron_time = get_option( 'email_crons_every_cron_time', 'email_crons' ) ? get_option( 'email_crons_every_cron_time', 'email_crons' ) : '';
		$minutes                     = $email_crons_every_cron_time ? floor( $email_crons_every_cron_time / 60 ) : '';
		$display_text                = ( ! empty( $minutes ) && $minutes < 1 ) ? 'Every ' . $email_crons_every_cron_time . ' Seconds' : 'Every ' . $minutes . ' Minutes';

		if ( ! isset( $schedules['email_crons_handler'] ) ) {
			$schedules['email_crons_handler'] = array(
				'interval' => $email_crons_every_cron_time,
				'display'  => $display_text,
			);
			return $schedules;
		}
	}

	/**
	 * Create cron timer of one minute
	 *
	 * @since 1.0.0
	 */
	public function email_crons_schedule_cron() {
		// $bulk_user_id     = get_transient( 'bulk_user_email' );
		// $bulk_email_track = get_transient( 'bulk_email_track' );

		// $custom_mail_content = html_entity_decode( get_option( 'email_content' ) );

		// $query_info = new WP_User_Query( array( 'include' => $bulk_user_id ) );
		// $users_info = $query_info->results;

		// if ( ! empty( $users_info ) ) {
		// 	$ary_chunk = array_chunk( $users_info, 3 );
		// 	$end_key   = array_key_last( $ary_chunk[ $bulk_email_track ] );

		// 	foreach ( $ary_chunk[ $bulk_email_track ] as $key => $info ) {
		// 		$email_body = str_replace( '%User%', $info->display_name, $custom_mail_content );
		// 		wp_mail( $info->user_email, 'Testing', $email_body );

		// 		if ( $key == $end_key ) {
		// 			set_transient( 'bulk_email_track', $bulk_email_track + 1, 60 * 60 * 24 );
		// 		}
		// 	}

		// 	if ( ! array_key_exists( $bulk_email_track + 1, $ary_chunk ) ) {
		// 		wp_clear_scheduled_hook( 'custom_send_email' );
		// 	}
		// } else {
		// 	wp_clear_scheduled_hook( 'custom_send_email' );
		// }
	}

	/**
	 * Create cron timer of one minute
	 *
	 * @since 1.0.0
	 */
	public function email_crons_schedule_cron_callback() {
		$all_users = get_users();
		$user_info = array();
		$count     = 0;
		foreach ( $all_users as $user_info ) {
			$user_info      = esc_html( $user_info->ID );
			$data[ $count ] = $user_info;
			$count++;
		}
		// set_transient( 'bulk_user_email', $data, 60 * 60 * 24 );
		// set_transient( 'bulk_email_track', 0, 60 * 60 * 24 );

		// if ( ! wp_next_scheduled( 'email_crons_call_email_template' ) ) {
		// 	wp_schedule_event( time(), 'email_crons_handler', 'email_crons_call_email_template' );
		// }
	}
}

$send_email = new SendEmail();
