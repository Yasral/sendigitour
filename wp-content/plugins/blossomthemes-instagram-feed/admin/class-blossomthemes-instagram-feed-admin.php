<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://blossomthemes.com
 * @since      1.0.0
 *
 * @package    Blossomthemes_Instagram_Feed
 * @subpackage Blossomthemes_Instagram_Feed/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Blossomthemes_Instagram_Feed
 * @subpackage Blossomthemes_Instagram_Feed/admin
 * @author     blossomthemes <info@blossomthemes.com>
 */
class Blossomthemes_Instagram_Feed_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Blossomthemes_Instagram_Feed_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Blossomthemes_Instagram_Feed_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/blossomthemes-instagram-feed-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Blossomthemes_Instagram_Feed_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Blossomthemes_Instagram_Feed_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/blossomthemes-instagram-feed-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'blossomthemesInstagramFeed', array(
			'ajaxurl' =>  admin_url('admin-ajax.php')
		));
		wp_enqueue_script( $this->plugin_name);

	}

   /**
	* Registers settings page for Social Share
	*
	* @since 1.0.0
	*/
	public function blossomthemes_instagram_feed_settings_page() {
		add_menu_page('BlossomThemes Social Feed', 'BlossomThemes Social Feed', 'manage_options', basename(__FILE__), array($this,'blossomthemes_instagram_feed_callback_function'), esc_url(BTIF_FILE_URL).'\admin\css\images\menu-icon.png');
	}

   /**
	* Registers settings.
	*
	* @since 1.0.0
	*/
	public function blossomthemes_instagram_feed_register_settings(){
	//The third parameter is a function that will validate input values.
		register_setting( 'blossomthemes_instagram_feed_settings', 'blossomthemes_instagram_feed_settings', array($this, 'sanitize') );
	}

	/**
	* 
	* Retrives saved settings from the database if settings are saved. Else, displays fresh forms for settings.
	*
	* @since 1.0.0
	*/
	function blossomthemes_instagram_feed_callback_function() { 
		$settings = BlossomThemes_Instagram_Feed_Settings::get_instance();
		$settings->render_backend_settings();
	}

	public function sanitize( $settings ) {
        $result = array();

		// Delete user feed cache. 
		delete_option( 'blossomthemes_instagram_user_feed' );

        $result['access_token'] = sanitize_text_field( $settings['access_token'] );

        $validation_result = Blossomthemes_Instagram_Feed_API::is_access_token_valid( $result['access_token'] );

        if ( $validation_result !== true ) {
            $access_token_error_message = __( 'Access Token rejected by Instagram API. Please add valid Access Token.', 'blossomthemes-instagram-feed' );

            if ( is_wp_error( $validation_result ) ) {
                $access_token_error_message = $validation_result->get_error_message();
            }

            if ( $validation_result !== true ) {
                add_settings_error(
                    'blossomthemes_instagram_feed_settings',
                    esc_attr( 'blossomthemes_instagram_feed_invalid_access_token' ),
                    $access_token_error_message,
                    'error'
                );
            }

            $settings['access_token'] = '';
        }

        Blossomthemes_Instagram_Feed_API::reset_cache();

        return $settings;
    }

    function blossomthemes_instagram_feed_admin_notice(){
    	global $current_user ;
		$user_id = $current_user->ID;
		$meta = get_user_meta($user_id, 'blossomthemes_instagram_admin_notice');
		$screen = get_current_screen();

		// Display invalid access token notice.
		$invalid_access_token = get_option( 'blossomthemes_instagram_invalid_token' );
		if ( $invalid_access_token ): ?>
			<div class="error notice is-dismissible">
				<p>
					<?php _e( 'BlossomThemes: Invalid or expired Instagram Access Token. Please reconnect Instagram.', 'blossomthemes-instagram-feed' ); ?>
				</p>
			</div>

		<?php endif;

		// Check that the user hasn't already clicked to ignore the message
		if ( empty( $meta ) && isset($screen->parent_file) && $screen->parent_file !=='class-blossomthemes-instagram-feed-admin.php'): ?>
			<div class="error notice is-dismissible" style="position:relative">
				<p>
					<strong><?php _e( 'Please configure BlossomThemes Social Feed', 'blossomthemes-instagram-feed' );?></strong>
					<br /><br/> 
					<?php _e( 'If you have just installed or updated this plugin, please go to the', 'blossomthemes-instagram-feed' );?> 
					<strong>
						<a href="admin.php?page=class-blossomthemes-instagram-feed-admin.php"><?php _e( 'Settings Page', 'blossomthemes-instagram-feed' );?></a> 
						<?php _e( 'and connect it with your Instagram account.', 'blossomthemes-instagram-feed');?>
					</strong> <?php _e( 'You can ignore this message if you have already configured it.', 'blossomthemes-instagram-feed' );?> | <strong>
						<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'btif-dismiss', 'dismiss_admin_notices' ), 'confirm=0' ) ); ?>" class="dismiss-notice" target="_parent"> <?php _e( 'Dismiss this notice', 'blossomthemes-instagram-feed' ); ?> </a>
					</strong>
				</p>
			</div>
		<?php endif; 
    }

    function blossomthemes_instagram_feed_ignore_admin_notice() {
		global $current_user;
		$user_id = $current_user->ID;

		/* If user clicks to ignore the notice, add that to their user meta */
		if ( isset( $_GET['btif-dismiss'] ) && check_admin_referer( 'confirm=0' ) || ( Blossomthemes_Instagram_Feed_API::get_instance()->is_configured() ) ) {

			update_user_meta($user_id, 'blossomthemes_instagram_admin_notice', 1);
		}
	}
}
