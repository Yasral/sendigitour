<?php
/**
 * Main Wheel_Of_Life class
 *
 * @package Wheel_Of_Life
 */
namespace WheelOfLife;

defined( 'ABSPATH' ) || exit;

/**
 * Main Wheel_Of_Life Cass.
 *
 * @class Wheel_Of_Life
 */
final class Wheel_Of_Life {
    /**
     * Wheel_Of_Life verison.
     *
     * @var string
     */
    public $version = '1.0.7';

    /**
     * The single instance of the class.
     *
     * @var Wheel_Of_Life
     * @since 1.0.0
     */
    protected static $_instance = null;

    /**
     * Main Wheel_Of_Life Instance.
     *
     * Ensures only one instance of Wheel_Of_Life is loaded or can be loaded.
     *
     * @since 1.0.0
     * @static
     * @see wheeloflife_spinthewheels()
     * @return Wheel_Of_Life - Main instance.
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Wheel_Of_Life Constructor.
     */
    public function __construct() {

        $this->_defineConstants();
        $this->init_hooks();
        $this->includes();

        $this->admin_settings  = new Wheel_Of_Life_Admin();
        $this->public_settings = new Wheel_Of_Life_Public();
        $this->post_types      = new Wheel_Of_Life_PostTypes();
    }

    /**
     * Activation hook for WP Appointment plugin.
     *
     * @return void
     */
    public function activate() {
		update_option( 'wheeloflife_queue_flush_rewrite_rules', 'yes' );
    }

    /**
     * When WP has loaded all plugins, trigger the 'Wheel_Of_Life_loaded; hook.
     *
     * This ensures 'Wheel_Of_Life_loaded' is called only after all the other plugins
     * are loaded, to avoid issues caused by plugin directory naming changing
     * the load order.
     *
     * @since 1.0.0
     * @access public
     */
    public function onPluginLoaded() {
        do_action('Wheel_Of_Life_loaded');
    }

    /**
     * Define WTE_FORM_EDITOR Constants.
     *
     * @since 1.0.0
     * @access private
     */
    private function _defineConstants() {
        $this->define('WHEEL_OF_LIFE_PLUGIN_NAME', 'wheeloflife');
        $this->define('WHEEL_OF_LIFE_ABSPATH', dirname(WHEEL_OF_LIFE_PLUGIN_FILE) . '/');
        $this->define('WHEEL_OF_LIFE_VERSION', $this->version);
        $this->define('WHEEL_OF_LIFE_PLUGIN_URL', $this->plugin_url());
		$this->define('WHEEL_OF_LIFE_POST_TYPE', 'wheel');
		$this->define('WHEEL_OF_LIFE_SUBMISSIONS_POST_TYPE', 'wheel-submissions');
    }

    /**
     * Define constant if not already set.
     *
     * @param string      $name       Constant name.
     * @param string|bool $value      Constant value.
     * @return void
     */
    private function define($name, $value) {
        if (!defined($name)) {
            define($name, $value);
        }
    }

    /**
     * Include required files.
     *
     * @return void
     */
    public function includes() {
    }

    /**
	 * Hook into actions and filters.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function init_hooks() {
		add_action( 'init', array( $this, 'init' ) );
		register_activation_hook( WHEEL_OF_LIFE_PLUGIN_FILE, array( $this, 'activate' ) );
	}

    /**
     * Init Wheel_Of_Life when WordPress initializes.
     *
     * @since 1.0.0
     * @access public
     */
    public function init() {
        // Before init action.
		do_action( 'before_wheeloflife_init' );

        // Set up localization.
        $this->loadPluginTextdomain();
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since 1.0.0
     *
     * Note: the first-loaded translation file overrides any following ones -
     * - if the same translation is present.
     *
     * Locales found in:
     *      - WP_LANG_DIR/wheeloflife/wheeloflife-LOCALE.mo
     *      - WP_LANG_DIR/plugins/wheeloflife-LOCALE.mo
     */
    public function loadPluginTextdomain()
    {
        if (function_exists('determine_locale')) {
            $locale = determine_locale();
        } else {
            $locale = is_admin() ? get_user_locale() : get_locale();
        }

        $locale = apply_filters( 'plugin_locale', $locale, 'wheel-of-life' );

        unload_textdomain( 'wheel-of-life' );
        load_textdomain( 'wheel-of-life', WP_LANG_DIR . '/wheel-of-life/wheel-of-life-' . $locale . '.mo' );
        load_plugin_textdomain(
            'wheel-of-life',
            false,
            dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
        );
    }

    /**
     * Get the plugin URL.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string
     */
    public function plugin_url() {
        return untrailingslashit( plugins_url( '/', WHEEL_OF_LIFE_PLUGIN_FILE ) );
    }

    /**
     * Get the plugin path.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string
     */
    public function plugin_path() {
        return untrailingslashit( plugin_dir_path( WHEEL_OF_LIFE_PLUGIN_FILE ) );
    }

    /**
     * Check if all plugin requirements are met.
     *
     * @since 1.0.0
     *
     * @return bool True if requirements are met, otherwise false.
     */
    private function meets_requirements() {
        return true;
    }

}
