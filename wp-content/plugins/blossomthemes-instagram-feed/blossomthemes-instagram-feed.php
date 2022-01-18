<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://blossomthemes.com
 * @since             1.0.0
 * @package           Blossomthemes_Instagram_Feed
 *
 * @wordpress-plugin
 * Plugin Name:       BlossomThemes Social Feed 
 * Plugin URI:        https://wordpress.org/plugins/blossomthemes-instagram-feed
 * Description:       Show instagram feed on your website using shortcode and widget.
 * Version:           2.0.3
 * Author:            blossomthemes
 * Author URI:        https://blossomthemes.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       blossomthemes-instagram-feed
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'BTIF_BASE_PATH', dirname( __FILE__ ) );
define( 'BTIF_FILE_PATH', __FILE__ );
define( 'BTIF_FILE_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );
define( 'BTIF_PLUGIN_VERSION', '2.0.3' );

// Instagram API image limit.
if ( ! defined( 'BTIF_INSTAGRAM_API_IMAGE_LIMIT' ) ) {
	define( 'BTIF_INSTAGRAM_API_IMAGE_LIMIT', 25 );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-blossomthemes-instagram-feed-activator.php
 */
function activate_blossomthemes_instagram_feed() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-blossomthemes-instagram-feed-activator.php';
	Blossomthemes_Instagram_Feed_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-blossomthemes-instagram-feed-deactivator.php
 */
function deactivate_blossomthemes_instagram_feed() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-blossomthemes-instagram-feed-deactivator.php';
	Blossomthemes_Instagram_Feed_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_blossomthemes_instagram_feed' );
register_deactivation_hook( __FILE__, 'deactivate_blossomthemes_instagram_feed' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-blossomthemes-instagram-feed.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_blossomthemes_instagram_feed() {

	$plugin = new Blossomthemes_Instagram_Feed();
	$plugin->run();

}
run_blossomthemes_instagram_feed();
