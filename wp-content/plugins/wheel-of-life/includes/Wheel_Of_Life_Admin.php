<?php

/**
 * Admin area settings and hooks.
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
class Wheel_Of_Life_Admin {



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
		do_action( 'wheel_of_life_admin_unhook', $this );
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
		add_action( 'admin_menu', array( $this, 'add_wheels_menu' ) );
		// Admin Scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_ui_components' ) );

		// UI Components in Blocks
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_ui_components' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 999 );
		// Lock block template
		add_action( 'init', array( $this, 'locked_template' ), 999 );

		// Add block categories
		add_filter( 'block_categories_all', array( $this, 'add_block_category' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'gb_editor_assets' ) );

		// Admin Script Translations
		add_action( 'admin_enqueue_scripts', array( $this, 'set_script_translations' ), 99999999999 );

		// Add shortcode
		add_shortcode( 'wheeloflife', array( $this, 'wheeloflife_form_render' ) );

		add_filter( 'rest_api_init', array( $this, 'filter_wheel_apiquery' ) );

		add_filter( 'allowed_block_types_all', array( $this, 'allowed_block_types' ), 10, 2 );

		/**
		 * Redirect admin pages.
		 *
		 * Redirect specific admin page to another specific admin page.
		 *
		 * @return void
		 */
		add_action(
			'admin_init',
			function () {
				global $pagenow;
				// Check current admin page.
				if ( $pagenow == 'edit.php' && isset( $_GET['post_type'] ) && $_GET['post_type'] == 'wheel' ) {
					wp_safe_redirect( admin_url( 'admin.php?page=wheels_of_life_all_lists' ) );
					exit;
				}
				if ( $pagenow == 'edit.php' && isset( $_GET['post_type'] ) && $_GET['post_type'] == 'wheel-submissions' ) {
					wp_safe_redirect( admin_url( 'admin.php?page=wheels_of_life_submissions' ) );
					exit;
				}
			}
		);
	}

	/**
	 * Enqueue Admin Scripts
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		$screen = get_current_screen();

		$post_types = array( WHEEL_OF_LIFE_POST_TYPE );
		$page_ids   = array( 'toplevel_page_wheels_of_life_all_lists', 'wheel-of-life_page_wheels_of_life_global_settings', 'wheel-of-life_page_wheels_of_life_submissions' );
		$page_paths = apply_filters( 'wol_page_paths', array( 'wheels_of_life_submissions', 'wheels_of_life_global_settings' ) );

		if ( ! in_array( $screen->post_type, $post_types ) ) {
			// Load in all screens
			$blocksFilter = include_once plugin_dir_path( WHEEL_OF_LIFE_PLUGIN_FILE ) . '/app/build/blocksFilter.asset.php';

			wp_enqueue_script( 'wheeloflife-blocksFilter', plugin_dir_url( WHEEL_OF_LIFE_PLUGIN_FILE ) . 'app/build/blocksFilter.js', $blocksFilter['dependencies'], $blocksFilter['version'], true );
		}

		if ( in_array( $screen->post_type, $post_types ) || in_array( $screen->id, $page_ids ) || ( isset( $_GET['page'] ) && in_array( $_GET['page'], $page_paths ) ) ) {

			wp_enqueue_media();

			if ( in_array( $screen->post_type, $post_types ) ) {
				wp_enqueue_style(
					'wheeloflife-wheels-edit', // Handle.
					plugin_dir_url( WHEEL_OF_LIFE_PLUGIN_FILE ) . '/app/build/wheelsEditCSS.css'
				);
			}

			if ( in_array( $screen->id, $page_ids ) || ( isset( $_GET['page'] ) && in_array( $_GET['page'], $page_paths ) ) ) {
				wp_enqueue_style(
					'wheeloflife-wheels-settings', // Handle.
					plugin_dir_url( WHEEL_OF_LIFE_PLUGIN_FILE ) . '/app/build/globalCSS.css'
				);

				wp_enqueue_style( 'toastr', plugin_dir_url( WHEEL_OF_LIFE_PLUGIN_FILE ) . '/assets/admin/css/toastr.min.css', array(), '2.1.3', 'all' );

				$admin_deps = include_once plugin_dir_path( WHEEL_OF_LIFE_PLUGIN_FILE ) . '/app/build/globalApp.asset.php';

				wp_register_script( 'wheeloflife-admin', plugin_dir_url( WHEEL_OF_LIFE_PLUGIN_FILE ) . 'app/build/globalApp.js', $admin_deps['dependencies'], $admin_deps['version'], true );

				wp_enqueue_style(
					'wheeloflife-wheels-settings', // Handle.
					plugin_dir_url( WHEEL_OF_LIFE_PLUGIN_FILE ) . '/app/build/globalCSS.css'
				);
				$ajax_nonce                     = wp_create_nonce( 'wheeloflife_ajax_nonce' );
				$wheeloflife_wpapp_object_array = array(
					'admin_url'  => admin_url( 'admin.php' ),
					'ajax_url'   => admin_url( 'admin-ajax.php' ),
					'gradients'  => isset( get_theme_support( 'editor-gradient-presets' )[0] ) ? get_theme_support( 'editor-gradient-presets' )[0] : array(),
					'ajax_nonce' => $ajax_nonce,
					'proActive'  => wol_is_pro_activated(),
				);
				wp_localize_script( 'wheeloflife-admin', 'wolVariables', $wheeloflife_wpapp_object_array );

				wp_enqueue_script( 'wheeloflife-admin');
			}

			wp_enqueue_style(
				'wheeloflife-wheels-common', // Handle.
				plugin_dir_url( WHEEL_OF_LIFE_PLUGIN_FILE ) . '/app/build/admin.css'
			);
		}
	}

	public function enqueue_ui_components() {
		// $ui_dependencies = include_once plugin_dir_path( WHEEL_OF_LIFE_PLUGIN_FILE ) . '/app/build/uiComponents.asset.php';
		wp_enqueue_script( 'wheeloflife-uicomponent', plugin_dir_url( WHEEL_OF_LIFE_PLUGIN_FILE ) . 'app/build/uiComponents.js', array( 'jquery', 'react', 'wp-api-fetch', 'wp-element', 'wp-i18n', 'wp-polyfill', 'wp-url' ), rand(), true );
	}

	/**
	 * Set Script Translations
	 *
	 * @return void
	 */
	public function set_script_translations() {
		wp_set_script_translations( 'wheeloflife-admin', 'wheel-of-life' );
		wp_set_script_translations( 'wheeloflife-uicomponent', 'wheel-of-life' );
		wp_set_script_translations( 'wheeloflife-blocks', 'wheel-of-life' );
		wp_set_script_translations( 'wheeloflife-editor', 'wheel-of-life' );
	}

	/**
	 * Add Block Category
	 *
	 * @param [type] $categories
	 * @return void
	 */
	public function add_block_category( $categories ) {
		$category_slugs = wp_list_pluck( $categories, 'slug' );
		return array_merge(
			$categories,
			array(
				array(
					'slug'  => 'wheeloflife',
					'title' => __( 'Wheels of Life', 'wheel-of-life' ),
				),
			)
		);
	}

	/**
	 * Add Wheels Menu page
	 *
	 * @return void
	 */
	public function add_wheels_menu() {
		$ADMIN_ICON = base64_encode( '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C12.5523 2 13 1.55228 13 1C13 0.447715 12.5523 0 12 0C11.4477 0 11 0.447715 11 1C11 1.55228 11.4477 2 12 2Z" fill="#F05537"/><path d="M12 24C12.5523 24 13 23.5523 13 23C13 22.4477 12.5523 22 12 22C11.4477 22 11 22.4477 11 23C11 23.5523 11.4477 24 12 24Z" fill="#F05537"/><path d="M23 13C23.5523 13 24 12.5523 24 12C24 11.4477 23.5523 11 23 11C22.4477 11 22 11.4477 22 12C22 12.5523 22.4477 13 23 13Z" fill="#F05537"/><path d="M1 13C1.55228 13 2 12.5523 2 12C2 11.4477 1.55228 11 1 11C0.447715 11 0 11.4477 0 12C0 12.5523 0.447715 13 1 13Z" fill="#F05537"/><path d="M19.78 5.21997C20.3323 5.21997 20.78 4.77226 20.78 4.21997C20.78 3.66769 20.3323 3.21997 19.78 3.21997C19.2277 3.21997 18.78 3.66769 18.78 4.21997C18.78 4.77226 19.2277 5.21997 19.78 5.21997Z" fill="#F05537"/><path d="M4.21997 20.78C4.77226 20.78 5.21997 20.3323 5.21997 19.78C5.21997 19.2277 4.77226 18.78 4.21997 18.78C3.66769 18.78 3.21997 19.2277 3.21997 19.78C3.21997 20.3323 3.66769 20.78 4.21997 20.78Z" fill="#F05537"/><path d="M19.78 20.78C20.3323 20.78 20.78 20.3323 20.78 19.78C20.78 19.2277 20.3323 18.78 19.78 18.78C19.2277 18.78 18.78 19.2277 18.78 19.78C18.78 20.3323 19.2277 20.78 19.78 20.78Z" fill="#F05537"/><path d="M4.21997 5.21997C4.77226 5.21997 5.21997 4.77226 5.21997 4.21997C5.21997 3.66769 4.77226 3.21997 4.21997 3.21997C3.66769 3.21997 3.21997 3.66769 3.21997 4.21997C3.21997 4.77226 3.66769 5.21997 4.21997 5.21997Z" fill="#F05537"/><path d="M12 2C10.0222 2 8.08879 2.58649 6.4443 3.6853C4.79981 4.78412 3.51809 6.3459 2.76121 8.17316C2.00434 10.0004 1.8063 12.0111 2.19215 13.9509C2.578 15.8907 3.53041 17.6725 4.92894 19.0711C6.32746 20.4696 8.10929 21.422 10.0491 21.8079C11.9889 22.1937 13.9996 21.9957 15.8268 21.2388C17.6541 20.4819 19.2159 19.2002 20.3147 17.5557C21.4135 15.9112 22 13.9778 22 12C22 9.34784 20.9464 6.8043 19.0711 4.92893C17.1957 3.05357 14.6522 2 12 2V2ZM12.5 4.025C14.2737 4.13805 15.9584 4.8422 17.285 6.025L13.735 9.57C13.366 9.31287 12.9439 9.14199 12.5 9.07V4.025ZM11.5 4.025V9.025C11.0561 9.09699 10.634 9.26787 10.265 9.525L6.715 5.98C8.04771 4.81353 9.73177 4.12548 11.5 4.025V4.025ZM6 6.715L9.545 10.265C9.28787 10.634 9.11699 11.0561 9.045 11.5H4.045C4.14548 9.73176 4.83353 8.0477 6 6.715V6.715ZM4 12.5H9C9.07199 12.9439 9.24287 13.366 9.5 13.735L5.95501 17.285C4.79759 15.9489 4.11848 14.2652 4.02501 12.5H4ZM11.5 20C9.72628 19.8869 8.0416 19.1828 6.715 18L10.265 14.455C10.634 14.7121 11.0561 14.883 11.5 14.955V20ZM10 12C10 11.6044 10.1173 11.2178 10.3371 10.8889C10.5568 10.56 10.8692 10.3036 11.2346 10.1522C11.6001 10.0009 12.0022 9.96126 12.3902 10.0384C12.7781 10.1156 13.1345 10.3061 13.4142 10.5858C13.6939 10.8655 13.8844 11.2219 13.9616 11.6098C14.0387 11.9978 13.9991 12.3999 13.8478 12.7654C13.6964 13.1308 13.44 13.4432 13.1111 13.6629C12.7822 13.8827 12.3956 14 12 14C11.4696 14 10.9609 13.7893 10.5858 13.4142C10.2107 13.0391 10 12.5304 10 12ZM12.5 20V15C12.9439 14.928 13.366 14.7571 13.735 14.5L17.285 18.045C15.9523 19.2115 14.2682 19.8995 12.5 20V20ZM18 17.31L14.455 13.76C14.7121 13.391 14.883 12.9689 14.955 12.525H19.955C19.8491 14.2845 19.1614 15.9589 18 17.285V17.31ZM14.955 11.5C14.883 11.0561 14.7121 10.634 14.455 10.265L18 6.715C19.1828 8.04159 19.887 9.72628 20 11.5H14.955Z" fill="#F05537"/></svg>' );
		add_menu_page(
			esc_html__( 'Wheel of Life', 'wheel-of-life' ),
			'Wheel of Life',
			'manage_options',
			'wheels_of_life_all_lists',
			array( $this, 'wheels_of_life_page' ),
			'data:image/svg+xml;base64,' . $ADMIN_ICON,
			40
		);
		add_submenu_page(
			'wheels_of_life_all_lists',
			esc_html__( 'Wheel of Life - Global Settings', 'wheel-of-life' ),
			'Settings',
			'manage_options',
			'wheels_of_life_global_settings',
			array( $this, 'wheels_of_life_page' )
		);
		add_submenu_page(
			'wheels_of_life_all_lists',
			esc_html__( 'Wheel of Life - Submissions', 'wheel-of-life' ),
			'Submissions',
			'manage_options',
			'wheels_of_life_submissions',
			array( $this, 'wheels_of_life_page' )
		);
	}

	/**
	 * Page output
	 *
	 * @return void
	 */
	public function wheels_of_life_page() {
		echo '<div id="wheelOfLifeAdminRoot"></div>';
	}

	/**
	 * Display a All Wheels
	 */
	public function wheels_of_life_list_page() {
		echo '<div id="appointmentRootApp"></div>';
	}


	/**
	 * Function
	 * Booking form for the appointment
	 *
	 * @param [type] $atts
	 * @return void
	 */
	function wheeloflife_form_render( $atts = array() ) {
		$atts = array_change_key_case( (array) $atts, CASE_LOWER );

		// override default attributes with user attributes
		$wheeloflife_atts = shortcode_atts(
			array(
				'id'          => '',
				'label'       => __( 'Start Assessment', 'wheel-of-life' ),
				'popup_label' => __( 'Start Assessment', 'wheel-of-life' ),
				'mode'        => 'popup',
			),
			$atts
		);
		ob_start();
		echo '<div class="wheelOfLifeRootAppSelector" data-mode=' . esc_attr( $wheeloflife_atts['mode'] ) . ' data-wheel-id=' . esc_attr( $wheeloflife_atts['id'] ) . ' data-btn-label="' . esc_attr( $wheeloflife_atts['label'] ) . '" data-popup-lbl="' . esc_attr( $wheeloflife_atts['popup_label'] ) . '"></div>';
		return ob_get_clean();
	}

	/**
	 * Locked post type template
	 *
	 * @return void
	 */
	function locked_template() {
		$post_type_object           = get_post_type_object( WHEEL_OF_LIFE_POST_TYPE );
		$post_type_object->template = array(
			array( 'core/paragraph', array( 'placeholder' => __( 'Enter Wheel Description...', 'wheel-of-life' ) ) ),
			array( 'wheeloflife/add-wheels' ),
		);
	}

	/**
	 * Enqueue Blocks.
	 *
	 * @return void
	 */
	public function gb_editor_assets() {
		$blocks_deps = include_once plugin_dir_path( WHEEL_OF_LIFE_PLUGIN_FILE ) . '/app/build/blocks.asset.php';

		wp_register_script( 'wheeloflife-blocks', plugin_dir_url( WHEEL_OF_LIFE_PLUGIN_FILE ) . 'app/build/blocks.js', $blocks_deps['dependencies'], $blocks_deps['version'], true );

		// Styles.
		wp_enqueue_style(
			'wheeloflife-blocks-gb-style-css', // Handle.
			plugin_dir_url( WHEEL_OF_LIFE_PLUGIN_FILE ) . '/app/build/blocksCSS.css'
		);
		$ajax_nonce                     = wp_create_nonce( 'wheeloflife_ajax_nonce' );
		$wheeloflife_wpapp_object_array = array(
			'admin_url'      => admin_url( 'admin.php' ),
			'ajax_url'       => admin_url( 'admin-ajax.php' ),
			'ajax_nonce'     => $ajax_nonce,
			'isProActivated' => function_exists( 'wheel_of_life_pro' ) ? true : false,
		);
		wp_localize_script( 'wheeloflife-blocks', 'wolVariables', $wheeloflife_wpapp_object_array );

		wp_enqueue_script( 'wheeloflife-blocks' );

		$screen = get_current_screen();

		$post_types = array( WHEEL_OF_LIFE_POST_TYPE );

		if ( in_array( $screen->post_type, $post_types ) ) {
			$editor_deps = include_once plugin_dir_path( WHEEL_OF_LIFE_PLUGIN_FILE ) . '/app/build/editor.asset.php';
			wp_enqueue_script( 'wheeloflife-editor', plugin_dir_url( WHEEL_OF_LIFE_PLUGIN_FILE ) . 'app/build/editor.js', $editor_deps['dependencies'], $editor_deps['version'], true );
		}
	}

	/**
	 * Disallow dynamic recipe blocks in widgets and customizer screen.
	 *
	 * @return Array $allowed_block_types
	 */
	public function allowed_block_types( $allowed_block_types, $editor_context ) {

		if ( ! empty( $editor_context->post ) && WHEEL_OF_LIFE_POST_TYPE === $editor_context->post->post_type ) {
			return array( 'wheeloflife/wheel-row' );
		}
		return $allowed_block_types;
	}

	/**
	 * API Filter for Wheel
	 *
	 * @return void
	 */
	public function filter_wheel_apiquery() {
		// Field name to register.
		$field = 'wheel_CTA';
		register_rest_field(
			WHEEL_OF_LIFE_POST_TYPE,
			$field,
			array(
				'get_callback'    => function ( $object ) use ( $field ) {
					// Get field as single value from post meta.
					$post_metas = get_post_meta( $object['id'], $field, true );

					if ( ! empty( $post_metas ) ) {
						return get_post_meta( $object['id'], $field, true );
					}
					return $this->get_cta_defaults();
				},
				'update_callback' => function ( $value, $object ) use ( $field ) {
					// Update the field/meta value.
					update_post_meta( $object->ID, $field, $value );
				},
			)
		);
	}

	/**
	 * CTA default values
	 *
	 * @return void
	 */
	public function get_cta_defaults() {
		return array(
			'ctaType'     => 'no-cta',
			'title'       => '',
			'description' => '',
			'page'        => '',
			'btn_label'   => '',
			'openInTab'   => false,
			'customizer'  => array(
				'background'     => array(
					'background_type'       => 'color',
					'background_pattern'    => 'type-1',
					'background_image'      => array(
						'attachment_id' => null,
						'x'             => 0,
						'y'             => 0,
					),

					'background_repeat'     => 'no-repeat',
					'background_size'       => 'cover',
					'background_attachment' => 'scroll',

					'patternColor'          => array(
						'default' => array(
							'color' => '#e5e7ea',
						),
					),

					'overlayColor'          => array(
						'default' => array(
							'color' => 'rgba(22, 16, 16, 0.3)',
						),
					),

					'backgroundColor'       => array(
						'default' => array(
							'color' => '#ff5037',
						),
					),
				),
				'alignment'      => 'center',
				'fontSize'       => 32,
				'fontColor'      => '#ffffff',
				'descFontSize'   => 18,
				'descFontColor'  => '#fff',
				'pbFontSize'     => 16,
				'pbBorderRadius' => 4,
				'pbfontColors'   => array(
					'pbfontColor'      => '#fff',
					'pbfontHoverColor' => '#000',
				),
				'pbBgColors'     => array(
					'pbBgColor'      => '#f05537',
					'pbBgHoverColor' => '#fff',
				),
				'margin'         => array(
					'top'    => '0',
					'left'   => '0',
					'right'  => '0',
					'bottom' => '0',
				),
				'padding'        => array(
					'top'    => '50px',
					'left'   => '38px',
					'right'  => '38px',
					'bottom' => '50px',
				),
			),
		);
	}
}
