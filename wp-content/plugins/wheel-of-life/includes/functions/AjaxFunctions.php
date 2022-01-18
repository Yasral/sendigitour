<?php

/**
 * Ajax of wheel of life.
 *
 * @package Wheel_Of_Life
 */

namespace WheelOfLife;

defined( 'ABSPATH' ) || exit;

/**
 * Admin class
 *
 * @package WheelOfLife
 */
class Wheel_Of_Life_Ajax {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Initialization.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @return void
	 */
	private function init() {
		// Initialize hooks.
		$this->init_hooks();
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @return void
	 */
	private function init_hooks() {
		 // Register pages
		add_action( 'wp_ajax_addNewPost', array( $this, 'add_new_wheel_of_life' ) );
		add_action( 'wp_ajax_editPost', array( $this, 'edit_wheel_of_life' ) );
		add_action( 'wp_ajax_viewPost', array( $this, 'view_submission_wheel_of_life' ) );
		add_action( 'wp_ajax_duplicatePost', array( $this, 'copy_wheel_of_life' ) );
		add_action( 'wp_ajax_trashPost', array( $this, 'trash_wheel_of_life' ) );
		add_action( 'wp_ajax_restorePost', array( $this, 'restore_wheel_of_life' ) );
		add_action( 'wp_ajax_deletePost', array( $this, 'delete_wheel_of_life' ) );
		add_action( 'wp_ajax_saveSocialShare', array( $this, 'save_social_share' ) );
		add_action( 'wp_ajax_saveData', array( $this, 'save_wheel_of_life_setting' ) );
		add_action( 'wp_ajax_getFormOption', array( $this, 'get_all_wheel' ) );
		add_action( 'wp_ajax_saveCTA', array( $this, 'save_call_to_action' ) );

		// Public ajax functions
		$this->add_ajax( 'getSocialShare', array( $this, 'get_social_share' ) );
		$this->add_ajax( 'sendMyWheelEmail', array( $this, 'send_my_wheel_email' ) );
		$this->add_ajax( 'saveWheelReport', array( $this, 'save_my_wheel_report' ) );
		$this->add_ajax( 'getCTA', array( $this, 'get_call_to_action' ) );
	}

	/**
	 * Make Ajax handler
	 *
	 * @param [type] $action
	 * @param [type] $callback
	 * @return void
	 */
	public function add_ajax( $action = false, $callback = false ) {
		if ( ! $action || ! $callback ) {
			return;
		}

		add_action( "wp_ajax_{$action}", $callback );
		add_action( "wp_ajax_nopriv_{$action}", $callback );
	}

	/**
	 * Get all wheel id and title
	 */
	public function get_all_wheel() {
		check_ajax_referer( 'wheeloflife_ajax_nonce', 'security' );
		$args   = array(
			'post_type'      => 'wheel',
			'posts_per_page' => -1,
		);
		$option = array();
		$posts  = get_posts( $args );
		if ( $posts ) {
			foreach ( $posts as $post ) {
				$post_title = $post->post_title !== '' ? $post->post_title : 'Untitled';
				$option[]   = array(
					'label' => $post_title,
					'value' => $post->ID,
				);
			}
		}

		wp_send_json_success( $option );
	}

	/**
	 * Save Call To Action values.
	 */
	public function save_call_to_action() {
		check_ajax_referer( 'wheeloflife_ajax_nonce', 'security' );
		$data = isset( $_POST['data'] ) ? json_decode( stripslashes_deep( $_POST['data'] ), true ) : '';

		update_option( 'wheel_of_life_CTA', json_encode( $data ) );

		wp_send_json_success( __( 'Saved successfully . ', 'wheel-of-life' ) );
	}

	/**
	 * Get Call to Action values.
	 */
	public function get_call_to_action() {

		$mode = isset( $_POST['mode'] ) ? $_POST['mode'] : '';
		if ( $mode !== 'front' ) {
			check_ajax_referer( 'wheeloflife_ajax_nonce', 'security' );
		}

		// $data = get_option( 'wheel_of_life_CTA', array() );
		$data = wol_get_cta_settings( true );
		wp_send_json_success( $data );
	}

	/**
	 * Save Wheel of life Setting
	 */
	public function save_wheel_of_life_setting() {
		check_ajax_referer( 'wheeloflife_ajax_nonce', 'security' );
	}

	/**
	 * Generate add New Post link.
	 */
	public function add_new_wheel_of_life() {
		check_ajax_referer( 'wheeloflife_ajax_nonce', 'security' );
		$add_link = admin_url( 'post-new.php?post_type=wheel' );
		wp_send_json_success( $add_link );
	}

	/**
	 * Generate edit post link.
	 */
	public function edit_wheel_of_life() {
		check_ajax_referer( 'wheeloflife_ajax_nonce', 'security' );
		$id        = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : '';
		$edit_link = get_edit_post_link( $id );
		$edit_link = str_replace( '&amp;', '&', $edit_link );

		if ( is_wp_error( $edit_link ) ) {
			wp_send_json_error( __( 'Something went wrong ! Please try again', 'wheel-of-life' ) );
		} else {
			wp_send_json_success( $edit_link );
		}
	}

	/**
	 * Generate view post link.
	 */
	public function view_submission_wheel_of_life() {
		check_ajax_referer( 'wheeloflife_ajax_nonce', 'security' );
		$id        = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : '';
		$view_link = esc_url( get_the_permalink( $id ) );
		$view_link = str_replace( '&#038;', '&', $view_link );

		if ( is_wp_error( $view_link ) ) {
			wp_send_json_error( __( 'Something went wrong ! Please try again', 'wheel-of-life' ) );
		} else {
			wp_send_json_success( $view_link );
		}
	}

	/**
	 * Trash post.
	 */
	public function trash_wheel_of_life() {
		check_ajax_referer( 'wheeloflife_ajax_nonce', 'security' );
		$id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : '';

		$post = array(
			'ID'          => $id,
			'post_status' => 'trash',
		);

		$trash   = wp_update_post( $post );
		$message = get_post_type( $id ) === 'wheel' ? __( 'Wheel trashed successfully.', 'wheel-of-life' ) : __( 'Submission trashed successfully.', 'wheel-of-life' );

		if ( is_wp_error( $trash ) ) {
			wp_send_json_error( __( 'Something went wrong ! Please try again', 'wheel-of-life' ) );
		} else {
			wp_send_json_success( $message );
		}
	}

	/**
	 * Restore post.
	 */
	public function restore_wheel_of_life() {
		check_ajax_referer( 'wheeloflife_ajax_nonce', 'security' );
		$id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : '';

		$post = array(
			'ID'          => $id,
			'post_status' => 'draft',
		);

		$trash   = wp_update_post( $post );
		$message = get_post_type( $id ) === 'wheel' ? __( 'Wheel restored successfully.', 'wheel-of-life' ) : __( 'Submission restored successfully.', 'wheel-of-life' );

		if ( is_wp_error( $trash ) ) {
			wp_send_json_error( __( 'Something went wrong ! Please try again', 'wheel-of-life' ) );
		} else {
			wp_send_json_success( $message );
		}
	}

	/**
	 * Delete post.
	 */
	public function delete_wheel_of_life() {
		check_ajax_referer( 'wheeloflife_ajax_nonce', 'security' );
		$id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : '';

		$message = get_post_type( $id ) === 'wheel' ? __( 'Wheel deleted successfully.', 'wheel-of-life' ) : __( 'Submission deleted successfully.', 'wheel-of-life' );
		$delete  = wp_delete_post( $id );

		if ( is_wp_error( $delete ) ) {
			wp_send_json_error( __( 'Something went wrong ! Please try again', 'wheel-of-life' ) );
		} else {
			wp_send_json_success( $message );
		}
	}

	/**
	 * Duplicate/Copy the post.
	 */
	public function copy_wheel_of_life() {
		check_ajax_referer( 'wheeloflife_ajax_nonce', 'security' );
		$id   = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : '';
		$post = get_post( $id );

		$post_id = wp_insert_post(
			array(
				'post_type'    => $post->post_type,
				'post_title'   => __( 'Copy of ', 'wheel-of-life' ) . $post->post_title,
				'post_content' => $post->post_content,
				'post_status'  => $post->post_status,
			)
		);

		if ( is_wp_error( $post_id ) ) {
			wp_send_json_error( __( 'Something went wrong ! Please try again', 'wheel-of-life' ) );
		} else {
			wp_send_json_success( __( 'Wheel copied successfully . ', 'wheel-of-life' ) );
		}
	}
	/**
	 * Save Socail Sharing global settings.
	 */
	public function save_social_share() {
		check_ajax_referer( 'wheeloflife_ajax_nonce', 'security' );
		$data = isset( $_POST['data'] ) ? wheeloflife_clean_vars( json_decode( stripslashes_deep( $_POST['data'] ) ), true ) : '';

		update_option( 'wheel_of_life_social_sharing', json_encode( $data ) );
		wp_send_json_success( __( 'Saved successfully . ', 'wheel-of-life' ) );
	}

	/**
	 * Get the social sharing option value.
	 */
	public function get_social_share() {
		check_ajax_referer( 'wheeloflife_ajax_nonce', 'security' );

		$data = get_option( 'wheel_of_life_social_sharing', array() );
		wp_send_json_success( $data );
	}

	/**
	 * Send My Wheel Email.
	 */
	public function send_my_wheel_email() {
		check_ajax_referer( 'wheeloflife_ajax_nonce', 'security' );
		$link       = isset( $_POST['reportLink'] ) ? esc_url( $_POST['reportLink'] ) : '';
		$to_email   = isset( $_POST['toEmail'] ) ? sanitize_email( $_POST['toEmail'] ) : '';
		$from_email = sanitize_email( get_option( 'admin_email' ) );
		$from_name  = wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );

		// Check the email address.
		if ( empty( $to_email ) || ! is_email( $to_email ) ) {
			wp_send_json_error( __( 'Please provide a valid email address.', 'wheel-of-life' ) );
		}

		if( ! class_exists( 'WheelOfLife_Pro\Wheel_Of_Life_Pro' ) ) {
			$subject = __( 'Your Assessment report has been created!', 'wheel-of-life' );
			$headers = 'Content-Type: text/html' . "\r\n";
			$headers .= 'Reply-to: ' . $from_name . ' <' . $from_email . ">\r\n";

			$email_content  = '<p>' . __( 'Dear {email},', 'wheel-of-life' ) . '<br /></p>';
			$email_content .= '<p>' . __( 'Thank you for taking the Assessment. You can view your assessment information via the link below.', 'wheel-of-life' ) . '</p>';
			$email_content .= '<a href="{link}">' . __( 'My Wheel Report', 'wheel-of-life' ) . '</a>';
			$email_content .= '<p>' . __( 'Thank you.', 'wheel-of-life' ) . '<br /></p>';
			$email_content .= get_bloginfo( 'name' ) . '<br />';

			$email_content = str_replace( '{email}', $to_email, $email_content );
			$email_content = str_replace( '{link}', $link, $email_content );

			$email_sent = wp_mail( $to_email, $subject, $email_content, $headers );

			if ( ! $email_sent ) {
				wp_send_json_error( __( 'Email sent failed. Please try again later.', 'wheel-of-life' ) );
			} else {
				wp_send_json_success( __( 'Email sent successfully.', 'wheel-of-life' ) );
			}
		}

		// Send email notification.
		do_action( 'wheeloflife_pro_send_report', $to_email, $link );

		wp_send_json_success( __( 'Email sent successfully.', 'wheel-of-life' ) );
	}

	/**
	 * Save the Wheel Assessment.
	 */
	public function save_my_wheel_report() {
		check_ajax_referer( 'wheeloflife_ajax_nonce', 'security' );

		$chartData    = isset( $_POST['chartData'] ) ? wheeloflife_clean_vars( json_decode( stripslashes_deep( $_POST['chartData'] ) ), true, 512, JSON_OBJECT_AS_ARRAY ) : array();
		$chartOptions = isset( $_POST['chartOptions'] ) ? wheeloflife_clean_vars( json_decode( stripslashes_deep( $_POST['chartOptions'] ) ), true, 512, JSON_OBJECT_AS_ARRAY ) : array();
		$wheelId      = isset( $_POST['wheelId'] ) ? absint( $_POST['wheelId'] ) : false;
		$chartType    = isset( $_POST['chartType'] ) ? esc_attr( $_POST['chartType'] ) : "polar-chart";
		$user_id      = '';

		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();
		}

		$new_post = array(
			'post_status' => 'publish',
			'post_type'   => WHEEL_OF_LIFE_SUBMISSIONS_POST_TYPE,
			'post_title'  => 'Wheel Submission',
		);

		$submission_id = wp_insert_post( $new_post );

		if ( is_wp_error( $submission_id ) ) {
			wp_send_json_error( __( 'Something went wrong! Please try again', 'wheel-of-life' ) );
		}

		$submission_post = array(
			'ID'         => $submission_id,
			'post_title' => 'Wheel Submission #' . $submission_id,
		);

		$submission_post_updated = wp_update_post( $submission_post );

		update_post_meta( $submission_id, 'chartData', $chartData );
		update_post_meta( $submission_id, 'chartOptions', $chartOptions );
		update_post_meta( $submission_id, 'chartType', $chartType );
		update_post_meta( $submission_id, 'wheelId', $wheelId );
		update_post_meta( $submission_id, 'userId', $user_id );

		if ( is_wp_error( $submission_post_updated ) ) {
			wp_send_json_error( __( 'Something went wrong! Please try again', 'wheel-of-life' ) );
		}

		wp_send_json_success(
			array(
				'id'      => $submission_id,
				'title'   => get_the_title( $submission_id ),
				'link'    => esc_url( get_the_permalink( $submission_id ) ),
				'message' => __( 'Report Saved Successfully.', 'wheel-of-life' ),
			)
		);
	}
}

new Wheel_Of_Life_Ajax();
