<?php

/**
 * Fired during plugin activation
 *
 * @link       https://blossomthemes.com
 * @since      1.0.0
 *
 * @package    Blossomthemes_Instagram_Feed
 * @subpackage Blossomthemes_Instagram_Feed/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Blossomthemes_Instagram_Feed
 * @subpackage Blossomthemes_Instagram_Feed/includes
 * @author     blossomthemes <info@blossomthemes.com>
 */
class Blossomthemes_Instagram_Feed_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$settings = get_option( 'blossomthemes_instagram_feed_settings ' );		
		if( isset( $settings['access-token'] ) ) {
			delete_option( 'blossomthemes_instagram_feed_settings' );
			delete_option( 'blossomthemes_instagram_invalid_token' );
			delete_option( 'blossomthemes_instagram_user_feed' );
			delete_transient( 'blossomthemes_instagram_data_fetch' );			
			delete_transient( 'blossomthemes_instagram_is_configured' );
		}
	}
}
