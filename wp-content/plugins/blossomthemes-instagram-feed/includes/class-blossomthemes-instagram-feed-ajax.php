<?php
/**
 * Handles Ajax requests.
 */
class Blossomthemes_Instagram_Feed_Ajax {
    /**
     * Ajax actions with function callback lists.
     */
    private $ajax_actions = array(
        'btif_fetch_new_posts' => array(            
            'priv'   => 'fetch_new_posts'
        ),
    );

    public function __construct() {
        // Initializes hooks.
        foreach( $this->ajax_actions as $action => $callbacks ) {
            if ( isset( $callbacks['nopriv'] ) ) {
                add_action( "wp_ajax_nopriv_{$action}", array( $this, $callbacks['nopriv'] ) );
            }

            if ( isset( $callbacks['priv'] ) ) {
                add_action( "wp_ajax_{$action}", array( $this, $callbacks['priv'] ) );
            }
        }
    }

    public function fetch_new_posts() {
        // Return if error if token is invalid.
        $invalid_access_token = get_option( 'blossomthemes_instagram_invalid_token' );
        if ( false === $invalid_access_token ) {
            wp_send_json_error();
            return;
        }

        // Get feed settings.
        $options = BlossomThemes_Instagram_Feed_Settings::get_settings();

        $photos       = isset( $options['photos'] ) ? absint( $options['photos'] ) : 5;

        // Delete fetch already to fetch again.
        delete_transient( 'blossomthemes_instagram_data_fetch' );

        // Get instance.
        $feed_api = Blossomthemes_Instagram_Feed_API::get_instance();

       // Get images.
        $feed = $feed_api->get_items( $photos );

        // Send response.
        if ( false === $feed) {
            wp_send_json_error();
        } else {
            wp_send_json_success();
        }
    }
}
