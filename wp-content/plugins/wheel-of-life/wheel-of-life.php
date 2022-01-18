<?php
/**
 * Plugin Name:     Wheel of Life
 * Plugin URI:      https://kraftplugins.com/wheel-of-life/
 * Description:     The Wheel of Life assessment tool helps clients quickly visualize every vital aspect of their lives and helps them understand which area of their life can be improved to bring in the perfect balance they need.
 * Author:          Kraft Plugins
 * Author URI:      https://kraftplugins.com
 * Text Domain:     wheel-of-life
 * Domain Path:     /languages
 * Version:         1.0.7
 * Requires at least: 5.2
 * Requires PHP:      7.0
 * @package         Wheeloflife
 */

// Your code starts here.
use WheelOfLife\Wheel_Of_Life;

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'WHEEL_OF_LIFE_PLUGIN_FILE' ) ) {
	define( 'WHEEL_OF_LIFE_PLUGIN_FILE', __FILE__ );
}

// Include the autoloader.
require_once __DIR__ . '/vendor/autoload.php';


/**
 * Return the main instance of Wheel_Of_Life.
 *
 * @since 1.0.0
 * @return Wheel_Of_Life
 */
function wheeloflife_spinthewheels() {
	return Wheel_Of_Life::instance();
}

$GLOBALS['WHL_OF_LIFE'] = wheeloflife_spinthewheels();

// Invokes all functions attached to the 'wheeloflife_free_loaded' hook
do_action( 'wheeloflife_free_loaded' );
