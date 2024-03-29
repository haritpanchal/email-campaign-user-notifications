<?php
/**
 * Send email class file
 *
 * @category Plugin
 * @package  Email Campaign User Notifications
 * @author   Harit Panchal <https://profiles.wordpress.org/haritpanchal>
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GPL Licence
 * @link     ''
 */

defined( 'ABSPATH' ) || die( 'Access denied!' );

/**
 * ECUN_SendEmail class
 *
 * @link     ''
 */
class ECUN_SendEmail {
	/**
	 * Construct function
	 */
	public function __construct() {
		add_filter( 'cron_schedules', array( $this, 'ecun_email_crons_cron_schedules' ) ); //phpcs:ignore
		add_action( 'email_crons_call_email_template', array( $this, 'ecun_email_crons_schedule_cron' ) );
		add_action( 'wp_ajax_schedule_cron', array( $this, 'ecun_email_crons_schedule_cron_callback' ) );

	}

	/**
	 * Create event for campaign.
	 *
	 * @since 1.0.0
	 *
	 * @param Array $schedules An array of non-default cron schedules.
	 */
	public function ecun_email_crons_cron_schedules( $schedules ) {
		$email_crons_every_cron_time = get_option( 'email_crons_every_cron_time' ) ? get_option( 'email_crons_every_cron_time' ) : '';
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
	 * Create cron schedule function.
	 *
	 * @since 1.0.0
	 */
	public function ecun_email_crons_schedule_cron() {
		$email_crons_bulk_users                 = get_transient( 'email_crons_bulk_user_email' );
		$email_crons_bulk_users_track           = get_transient( 'email_crons_bulk_users_track' );
		$email_crons_user_chunk_count           = get_option( 'email_crons_user_chunk' ) ? get_option( 'email_crons_user_chunk' ) : '';
		$email_crons_email_subject              = get_option( 'email_crons_email_subject' ) ? get_option( 'email_crons_email_subject' ) : '';
		$email_crons_email_template_editor_name = get_option( 'email_crons_email_template_editor_name' ) ? get_option( 'email_crons_email_template_editor_name' ) : '';
		$customize_global_variable_options      = get_option( 'customize_global_variable_options' ) ? get_option( 'customize_global_variable_options' ) : '';

		switch ( $customize_global_variable_options ) {
			case 'email':
				$option_value = 'user_email';
				break;
			case 'first':
				$option_value = 'first_name';
				break;
			case 'second':
				$option_value = 'last_name';
				break;
			case 'nickname':
				$option_value = 'nickname';
				break;
			case 'display':
				$option_value = 'display_name';
				break;
			default:
				$option_value = 'display_name';
				break;
		}

		$query_info = new WP_User_Query( array( 'include' => $email_crons_bulk_users ) );
		$users_info = $query_info->results;
		if ( ! empty( $users_info ) ) {
			$ary_chunk = array_chunk( $users_info, $email_crons_user_chunk_count );
			$end_key   = array_key_last( $ary_chunk[ $email_crons_bulk_users_track ] );

			foreach ( $ary_chunk[ $email_crons_bulk_users_track ] as $key => $info ) {
				$username                               = $info->$option_value;
				$email_subject                          = str_replace( '%USER%', $username, $email_crons_email_subject );
				$email_crons_email_template_editor_name = str_replace( '%USER%', $username, $email_crons_email_template_editor_name );
				wp_mail( $info->user_email, $email_subject, $email_crons_email_template_editor_name );

				if ( $key == $end_key ) {
					set_transient( 'email_crons_bulk_users_track', $email_crons_bulk_users_track + 1, 60 * 60 * 24 );
				}
			}

			if ( ! array_key_exists( $email_crons_bulk_users_track + 1, $ary_chunk ) ) {
				wp_clear_scheduled_hook( 'email_crons_call_email_template' );
				delete_transient( 'email_crons_bulk_users_track' );
				delete_transient( 'email_crons_bulk_user_email' );
				delete_transient( 'email_crons_progress_check' );
			}
		} else {
			wp_clear_scheduled_hook( 'email_crons_call_email_template' );
		}
	}

	/**
	 * Create schedule cron callback function.
	 *
	 * @since 1.0.0
	 */
	public function ecun_email_crons_schedule_cron_callback() {
		$selected_roles = get_option( 'email_crons_roles_chunk', true ) ? get_option( 'email_crons_roles_chunk', true ) : '';
		$all_users      = get_users( array( 'role__in' => $selected_roles ) );
		$users_chunk    = array_column( $all_users, 'ID' );

		set_transient( 'email_crons_bulk_user_email', $users_chunk, 60 * 60 * 24 );
		set_transient( 'email_crons_bulk_users_track', 0, 60 * 60 * 24 );

		$json_response = array();
		if ( ! wp_next_scheduled( 'email_crons_call_email_template' ) ) {
			$json_response['message'] = 'Success. Email scheduling has started.';
			wp_schedule_event( time(), 'email_crons_handler', 'email_crons_call_email_template' );
			set_transient( 'email_crons_progress_check', true );
			wp_send_json_success( $json_response, 200 );
		} else {
			$json_response['message'] = 'Something wrong.';
			wp_send_json_error( $json_response, 504 );
		}
	}
}

$send_email = new ECUN_SendEmail();
