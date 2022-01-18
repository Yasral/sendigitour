<?php
/**
 * Instagram API section of the plugin.
 *
 * Maintain a list of functions that are used for fetching items from Instagram API
 *
 * @package    BlossomThemes_Instagram_Feed
 * @subpackage BlossomThemes_Instagram_Feed/includes
 * @author    blossomthemes
 */
class Blossomthemes_Instagram_Feed_API {
    /**
     * @var Blossomthemes_Instagram_Feed_API The reference to *Singleton* instance of this class
     */
    private static $instance = null;

    private $media_url = 'https://graph.instagram.com/me/media';

    private $settings;

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @return Blossomthemes_Instagram_Feed_API The *Singleton* instance.
     */
    public static function get_instance($settings=null)
    {
        if ( null === self::$instance ) {
            self::$instance = new self($settings);
        }
        return self::$instance;
    }

    private function __construct($settings) {   
        $this->settings = $settings;

        add_action( 'btif_refresh_token_cron_hook', array( $this, 'btif_refresh_token_cron_exec' ) );
        add_filter( 'cron_schedules', array( $this, 'add_fifty_days_cron_interval' ) );
    }

    /**
     * @param int    $image_limit Number of images to retrieve
     *
     * @return array|bool Array of tweets or false if method fails
     */
    public function get_items( $image_limit ) {
        // Check if the user feed is already fetched.
        $fetch_already = get_transient( 'blossomthemes_instagram_data_fetch' );
        $feed          = get_option( 'blossomthemes_instagram_user_feed' );
        
        // Return data from cache if the images are already fetched and data is in the cache.
        if ( $fetch_already && ! empty( $feed )) {
            return $this->slice_images( $feed, $image_limit );
        }

        $url = add_query_arg( array(
            'fields'       => 'caption, id, media_type, media_url, permalink, thumbnail_url, timestamp, username',
            'access_token' => $this->settings->get_access_token(),
        ), $this->media_url );

        // Get the new images if the images are not fetched.
        $response = wp_remote_get( $url );

        // Return the images from cache if new images cannot be fetched.
        if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
            update_option( 'blossomthemes_instagram_invalid_token', true, false );
            return $this->slice_images( $feed, $image_limit );
        }

        $data = json_decode( wp_remote_retrieve_body( $response ) );

        // Bail if the data is empty.
        if ( empty( $data->data ) ) {
            update_option( 'blossomthemes_instagram_invalid_token', true, false );
            return false;
        }

        $data->data = array_map( function ($item) {
            return (object) wp_parse_args( $item, array(
                'caption' => 'Instagram-Caption'
            ) );
        }, $data->data );

        // Set the data fetch to true and update the cache with new posts.
        set_transient( 'blossomthemes_instagram_data_fetch', true, $this->settings->get_transient_lifetime() );
        update_option( 'blossomthemes_instagram_user_feed', $data->data, false );

        return $this->slice_images( $data->data, $image_limit );
    }

    private function slice_images( $data, $count ){
        
        if( ! is_array( $data ) ) return array();
        $data_length = count( $data );
        $min = min( $data_length, $count );
        
        return array_slice( $data, 0, $min );
    }
    
    /**
     * Check if given access token is valid for Instagram Api.
     */
    public static function is_access_token_valid( $access_token ) {

        $url = add_query_arg( array(
                'fields'       => 'username',
                'access_token' => $access_token,

            ), 'https://graph.instagram.com/me' );

        $response = wp_remote_get( $url );

        if ( is_wp_error( $response ) ) {
            update_option( 'blossomthemes_instagram_invalid_token', true, false );
            return false;
        }

        if ( 200 != wp_remote_retrieve_response_code( $response ) ) {
            update_option( 'blossomthemes_instagram_invalid_token', true, false );
            return false;
        }

        return true;
    }

    public function is_configured() {
        $transient = 'blossomthemes_instagram_is_configured';

        if ( false !== ( $result = get_transient( $transient ) ) ) {

            if ( 'yes' === $result ) {
                return true;
            }

            if ( 'no' === $result ) {
                return false;
            }
        }

        $condition = $this->is_access_token_valid( $this->settings->get_access_token() );

        if ( true === $condition ) {
            set_transient( $transient, 'yes', DAY_IN_SECONDS );

            return true;
        }

        set_transient( $transient, 'no', DAY_IN_SECONDS );

        return false;
    }

    /**
     * Reset the cache.
     */
    public static function reset_cache() {
        delete_transient( 'blossomthemes_instagram_is_configured' );
        delete_option( 'blossomthemes_instagram_invalid_token' );
    }

    /**
     * Add 50 days interval.
     */
    public function add_fifty_days_cron_interval( $schedules ) {
        $schedules['fifty_days'] = array(
        'interval' => 50 * DAY_IN_SECONDS,
        'display' => esc_html__( 'Every Fifty Days', 'blossomthemes-instagram-feed' ),
        );

        return $schedules;
    }

    /**
     * Add Instagra Refresh Token cron job execution function.
     */
    public function btif_refresh_token_cron_exec(){
        $access_token = $this->settings->get_access_token();
        $settings     = $this->settings->get_settings();

        $refresh_token_url = add_query_arg( array(
            'grant_type'   => 'ig_refresh_token',
            'access_token' => $access_token,
        ), 'https://graph.instagram.com/refresh_access_token' );
        
        $response = wp_remote_get( $refresh_token_url );

        if( is_wp_error( $response ) ) return;

        if( 200 !== wp_remote_retrieve_response_code( $response ) ) return;

        $body = json_decode( wp_remote_retrieve_body($response) );

        $settings['access_token'] = $body->access_token;

        update_option( 'blossomthemes_instagram_feed_settings', $settings );

    }

}
