<?php

/**
 * Public area settings and hooks.
 *
 * @package Wheel_Of_Life
 */

namespace WheelOfLife;

defined( 'ABSPATH' ) || exit;
/**
 * Global Settings.
 */
class Wheel_Of_Life_Public {
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

		// Allow 3rd party to remove hooks.
		do_action( 'wheel_of_life_public_unhook', $this );
	}

	/**
	 * Init Hooks
	 *
	 * @return void
	 */
	public function init_hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_filter( 'rest_prepare_' . WHEEL_OF_LIFE_POST_TYPE, array( $this, 'blocks_to_rest_api' ), 10, 3 );
		/* Filter the single_template with our custom function*/
		add_filter( 'single_template', array( $this, 'submission_template' ) );

		// Add noindex for Submission posts SEO.
		add_action( 'wp_head', array( $this, 'add_noindex_robots' ), 99 );

		// Public Script Translations
		add_action( 'wp_enqueue_scripts', array( $this, 'set_script_translations' ), 99999999999 );
	}

	/**
	 * Enqueue Frontend Assets
	 *
	 * @return void
	 */
	public function enqueue_assets() {
		// Load in all screens
		$wheelsFrontend     = include_once plugin_dir_path( WHEEL_OF_LIFE_PLUGIN_FILE ) . '/app/build/wheelsFrontend.asset.php';
		$frontendComponents = include_once plugin_dir_path( WHEEL_OF_LIFE_PLUGIN_FILE ) . '/app/build/frontendComponents.asset.php';

		wp_register_script( 'wheeloflife-frontend-component', plugin_dir_url( WHEEL_OF_LIFE_PLUGIN_FILE ) . 'app/build/frontendComponents.js', $frontendComponents['dependencies'], $frontendComponents['version'], true );
		wp_register_script( 'wheeloflife-frontend', plugin_dir_url( WHEEL_OF_LIFE_PLUGIN_FILE ) . 'app/build/wheelsFrontend.js', $wheelsFrontend['dependencies'], $wheelsFrontend['version'], true );

		$ajax_nonce = wp_create_nonce( 'wheeloflife_ajax_nonce' );
		$wheeloflife_wpapp_object_array = array(
			'admin_url'  => admin_url( 'admin.php' ),
			'ajax_url'   => admin_url( 'admin-ajax.php' ),
			'ajax_nonce' => $ajax_nonce,
		);
		wp_localize_script( 'wheeloflife-frontend', 'wolVariablesFrontend', $wheeloflife_wpapp_object_array );

		wp_enqueue_script( 'wheeloflife-frontend-component' );
		wp_enqueue_script( 'wheeloflife-frontend' );

		wp_enqueue_style(
			'wheeloflife-frontend', // Handle.
			plugin_dir_url( WHEEL_OF_LIFE_PLUGIN_FILE ) . '/app/build/wheelsFrontendCSS.css'
		);

		wp_enqueue_style( 'toastr', plugin_dir_url( WHEEL_OF_LIFE_PLUGIN_FILE ) . '/assets/admin/css/toastr.min.css', array(), '2.1.3', 'all' );
	}

	/**
	 * Set Script Translations
	 *
	 * @return void
	 */
	public function set_script_translations() {
		wp_set_script_translations( 'wheeloflife-frontend', 'wheel-of-life' );
		wp_set_script_translations( 'wheeloflife-frontend-component', 'wheel-of-life' );
	}

	/**
	 * Include all Gutenberg blocks from content in REST API response
	 */
	public function blocks_to_rest_api( $response, $post, $request ) {
		if ( ! function_exists( 'parse_blocks' ) ) {
			return $response;
		}
		if ( isset( $post ) ) {
			$response->data['blocks'] = parse_blocks( $post->post_content ); // https://developer.wordpress.org/reference/functions/parse_blocks
		}
		return $response;
	}

	/**
	 * Submission template filter.
	 *
	 * @return void
	 */
	public function submission_template( $single ) {
		global $post;

		/* Checks for single template by post type */
		if ( $post->post_type == WHEEL_OF_LIFE_SUBMISSIONS_POST_TYPE ) {
			if ( file_exists( plugin_dir_path( WHEEL_OF_LIFE_PLUGIN_FILE ) . '/templates/single-wheel-submissions.php' ) ) {
				return plugin_dir_path( WHEEL_OF_LIFE_PLUGIN_FILE ) . '/templates/single-wheel-submissions.php';
			}
		}

		return $single;
	}

	/**
	 * Add noindex for Submission posts SEO.
	 *
	 * @return void
	 */
	public function add_noindex_robots() {
		if ( is_singular( WHEEL_OF_LIFE_SUBMISSIONS_POST_TYPE ) ) {
			echo '<meta name="robots" content="noindex" />' . "\n";
			echo '<meta name="robots" content="noindex" />' . "\n";
		}
	}
}
