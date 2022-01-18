<?php
/**
 * Register Post Type.
 *
 * @package Wheel_Of_Life
 * @subpackage  Wheel_Of_Life
 */

namespace WheelOfLife;

defined( 'ABSPATH' ) || exit;

class Wheel_Of_Life_PostTypes {

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
	 * @return void
	 */
	private function init_hooks() {
		// Register Custom Post Type : Wheel
		add_action( 'init', array( $this, 'register_post_type_wheel' ), 99 );
		add_action( 'init', array( $this, 'register_post_meta' ), 999 );
		add_action( 'init', array( $this, 'register_post_type_submissions' ), 99 );
		add_action( 'rest_api_init', array( $this, 'register_rest_metas' ) );
	}

	/**
	 * Register Custom post type
	 * Wheel
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function register_post_type_wheel() {

		$wheel_labels = array(
			'name'               => _x( 'Wheels', 'post type general name', 'wheel-of-life' ),
			'singular_name'      => _x( 'Wheel', 'post type singular name', 'wheel-of-life' ),
			'menu_name'          => _x( 'Wheels', 'admin menu', 'wheel-of-life' ),
			'name_admin_bar'     => _x( 'Wheel', 'add new on admin bar', 'wheel-of-life' ),
			'add_new'            => _x( 'Add New', 'wheel', 'wheel-of-life' ),
			'add_new_item'       => __( 'Add New wheel', 'wheel-of-life' ),
			'new_item'           => __( 'New wheel', 'wheel-of-life' ),
			'edit_item'          => __( 'Edit wheel', 'wheel-of-life' ),
			'view_item'          => __( 'View wheel', 'wheel-of-life' ),
			'all_items'          => __( 'All wheels', 'wheel-of-life' ),
			'search_items'       => __( 'Search wheels', 'wheel-of-life' ),
			'parent_item_colon'  => __( 'Parent wheels:', 'wheel-of-life' ),
			'not_found'          => __( 'No wheels found.', 'wheel-of-life' ),
			'not_found_in_trash' => __( 'No wheel found in Trash.', 'wheel-of-life' ),
		);

		$wheeloflife_args = array(
			'labels'             => $wheel_labels,
			'description'        => __( 'Description.', 'wheel-of-life' ),
			'public'             => true,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => false,
			'show_in_rest'       => true,
			'query_var'          => true,
			'rewrite'            => array(
				'slug'       => WHEEL_OF_LIFE_POST_TYPE,
				'with_front' => false,
			),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 30,
			'supports'           => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
		);
		register_post_type( WHEEL_OF_LIFE_POST_TYPE, $wheeloflife_args );

		if ( 'yes' === get_option( 'wheeloflife_queue_flush_rewrite_rules' ) ) {
			update_option( 'wheeloflife_queue_flush_rewrite_rules', 'no' );
			flush_rewrite_rules();
		}
	}

	/**
	 * Register Block meta
	 *
	 * @return void
	 */
	public function register_post_meta() {
		register_post_meta(
			WHEEL_OF_LIFE_POST_TYPE,
			'wheel_meta_input_type',
			array(
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
				'default'      => 'likert',
			)
		);

		register_post_meta(
			WHEEL_OF_LIFE_POST_TYPE,
			'wheel_meta_range_min',
			array(
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'number',
				'default'      => 1,
			)
		);

		register_post_meta(
			WHEEL_OF_LIFE_POST_TYPE,
			'wheel_meta_range_max',
			array(
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'number',
				'default'      => 10,
			)
		);

		register_post_meta(
			WHEEL_OF_LIFE_POST_TYPE,
			'wheel_meta_wheels_data',
			array(
				'single'       => true,
				'type'         => 'array',
				// 'default' => [
				// 'legendColor' => '#ffffff',
				// 'showIcon' => true
				// ],
				'show_in_rest' => array(
					'schema' => array(
						'items' => array(
							'type'       => 'object',
							'properties' => array(
								'legendColor' => array(
									'type' => 'string',
									// 'default' => '#1db53f'
								),
								'showIcon'    => array(
									'type' => 'boolean',
									// 'default' => false
								),
								'icon'        => array(
									'type' => 'string',
								),
								'skipText'    => array(
									'type' => 'string',
								),
							),
						),
					),
				),
			)
		);
	}

	/**
	 * Register Custom post type
	 * Submissions
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function register_post_type_submissions() {

		$labels = array(
			'name'               => _x( 'Submissions', 'post type general name', 'wheel-of-life' ),
			'singular_name'      => _x( 'Submission', 'post type singular name', 'wheel-of-life' ),
			'menu_name'          => _x( 'Submissions', 'admin menu', 'wheel-of-life' ),
			'name_admin_bar'     => _x( 'Submission', 'add new on admin bar', 'wheel-of-life' ),
			'add_new'            => _x( 'Add New', 'Submission', 'wheel-of-life' ),
			'add_new_item'       => __( 'Add New Submission', 'wheel-of-life' ),
			'new_item'           => __( 'New Submission', 'wheel-of-life' ),
			'edit_item'          => __( 'Edit Submission', 'wheel-of-life' ),
			'view_item'          => __( 'View Submission', 'wheel-of-life' ),
			'all_items'          => __( 'All Submissions', 'wheel-of-life' ),
			'search_items'       => __( 'Search Submissions', 'wheel-of-life' ),
			'parent_item_colon'  => __( 'Parent Submissions:', 'wheel-of-life' ),
			'not_found'          => __( 'No Submissions found.', 'wheel-of-life' ),
			'not_found_in_trash' => __( 'No Submission found in Trash.', 'wheel-of-life' ),
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'This is where wheel submissions are stored.', 'wheel-of-life' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => 'edit.php?post_type=wheel',
			'show_in_rest'       => true,
			'query_var'          => true,
			'rewrite'            => array(
				'slug'       => WHEEL_OF_LIFE_SUBMISSIONS_POST_TYPE,
				'with_front' => true,
			),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 40,
			'supports'           => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
		);
		register_post_type( WHEEL_OF_LIFE_SUBMISSIONS_POST_TYPE, $args );

		if ( 'yes' === get_option( 'wheeloflife_queue_flush_rewrite_rules' ) ) {
			update_option( 'wheeloflife_queue_flush_rewrite_rules', 'no' );
			flush_rewrite_rules();
		}
	}

	function register_rest_metas() {

		// Register Wheel featured image
		register_rest_field(
			array( WHEEL_OF_LIFE_POST_TYPE ),
			'fimg_url',
			array(
				'get_callback'    => array( $this, 'get_rest_featured_image' ),
				'update_callback' => null,
				'schema'          => null,
			)
		);

		// Register user id
		register_rest_field(
			array( WHEEL_OF_LIFE_SUBMISSIONS_POST_TYPE ),
			'userId',
			array(
				'get_callback'    => array( $this, 'get_user_id' ),
				'update_callback' => null,
				'schema'          => null,
			)
		);

		// Register Submitted Wheel id
		register_rest_field(
			array( WHEEL_OF_LIFE_SUBMISSIONS_POST_TYPE ),
			'wheelId',
			array(
				'get_callback'    => array( $this, 'get_submitted_wheel_id' ),
				'update_callback' => null,
				'schema'          => null,
			)
		);

		// Register Submission chart data
		register_rest_field(
			array( WHEEL_OF_LIFE_SUBMISSIONS_POST_TYPE ),
			'chartData',
			array(
				'get_callback'    => array( $this, 'get_chartData' ),
				'update_callback' => null,
				'schema'          => null,
			)
		);

		// Register Submission chart options
		register_rest_field(
			array( WHEEL_OF_LIFE_SUBMISSIONS_POST_TYPE ),
			'chartOption',
			array(
				'get_callback'    => array( $this, 'get_chartOption' ),
				'update_callback' => null,
				'schema'          => null,
			)
		);

		// Register logged in user id
		register_rest_field(
			array( WHEEL_OF_LIFE_POST_TYPE ),
			'userId',
			array(
				'get_callback'    => array( $this, 'get_logged_user' ),
				'update_callback' => null,
				'schema'          => null,
			)
		);

		// Register Wheel submission chart type
		register_rest_field(
			array( WHEEL_OF_LIFE_SUBMISSIONS_POST_TYPE ),
			'chartType',
			array(
				'get_callback'    => array( $this, 'get_wheel_chart_type' ),
				'update_callback' => null,
				'schema'          => null,
			)
		);
	}

	function get_rest_featured_image( $object, $field_name, $request ) {
		if ( $object['featured_media'] ) {
			$img = wp_get_attachment_image_src( $object['featured_media'], 'full' );
			return $img[0];
		}
		return false;
	}

	function get_user_id( $object, $field_name, $request ) {
		if ( isset( $object['id'] ) ) {
			$user_id = get_post_meta( $object['id'], 'userId', true );
			return $user_id;
		}

		return false;
	}

	function get_submitted_wheel_id( $object, $field_name, $request ) {
		if ( isset( $object['id'] ) ) {
			$wheelId = get_post_meta( $object['id'], 'wheelId', true );
			return $wheelId;
		}

		return false;
	}

	function get_chartData( $object, $field_name, $request ) {
		if ( isset( $object['id'] ) ) {
			$chartData = get_post_meta( $object['id'], 'chartData', true );
			return $chartData;
		}

		return false;
	}

	function get_chartOption( $object, $field_name, $request ) {
		if ( isset( $object['id'] ) ) {
			$chartOption = get_post_meta( $object['id'], 'chartOptions', true );
			return $chartOption;
		}

		return false;
	}

	function get_logged_user( $object, $field_name, $request ) {
		if ( is_user_logged_in() ) {
			return get_current_user_id();
		}

		return false;
	}

	function get_wheel_chart_type( $object, $field_name, $request ) {
		if ( isset( $object['id'] ) ) {
			$chartType = get_post_meta( $object['id'], 'chartType', true );
			$chartType = isset( $chartType ) && '' != $chartType ? $chartType : "polar-chart";
			return $chartType;
		}
		return false;
	}
}
