<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://blossomthemes.com
 * @since      1.0.0
 *
 * @package    Blossomthemes_Instagram_Feed
 * @subpackage Blossomthemes_Instagram_Feed/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Blossomthemes_Instagram_Feed
 * @subpackage Blossomthemes_Instagram_Feed/public
 * @author     blossomthemes <info@blossomthemes.com>
 */
class Blossomthemes_Instagram_Feed_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/blossomthemes-instagram-feed-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'magnific-popup', plugin_dir_url( __FILE__ ) . 'css/magnific-popup.min.css', array(), '1.0.0', 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/blossomthemes-instagram-feed-public.js', array( 'jquery' ), $this->version, true );

		$magnific_popup = apply_filters('btif_magnific_popup_enqueue',true);
		if( $magnific_popup == true )
		{
			wp_enqueue_script( 'magnific-popup', plugin_dir_url( __FILE__ ) . 'js/jquery.magnific-popup.min.js', array( 'jquery' ), '1.0.0', true );
		}

		// Get feed settings.
        $settings = BlossomThemes_Instagram_Feed_Settings::get_settings();

		if( $settings['enable_lazy_load'] ) {
			wp_enqueue_script( 'jquery-lazy', plugin_dir_url( __FILE__ ) . 'js/jquery.lazy.min.js', array( 'jquery' ), '1.7.10', true );
		}

	}

	/**
	 * Defer js assets.
	 */
	function btif_js_defer_files($tag)
	{
		$btif_assets = apply_filters('btif_public_assets_enqueue',true);

		if( is_admin() || $btif_assets == true ) return $tag;
		
		$async_files = apply_filters( 'btif_js_async_files', array( 
	        plugin_dir_url( __FILE__ ) . 'js/blossomthemes-instagram-feed-public.js',
	        plugin_dir_url( __FILE__ ) . 'js/jquery.magnific-popup.min.js'	
		 ) );
		
		$add_async = false;
		foreach( $async_files as $file ){
			if( strpos( $tag, $file ) !== false ){
				$add_async = true;
				break;
			}
		}

		if( $add_async ) $tag = str_replace( ' src', ' defer="defer" src', $tag );

		return $tag;
		
	}

}
