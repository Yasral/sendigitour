<?php
/**
 * Settings section of the plugin.
 *
 * Maintain a list of functions that are used for settings purposes of the plugin
 *
 * @package    BlossomThemes_Instagram_Feed
 * @subpackage BlossomThemes_Instagram_Feed/includes
 * @author    blossomthemes
 */
class BlossomThemes_Instagram_Feed_Settings {

    private static $_instance =  null;

    private $base_url      = 'https://api.instagram.com/oauth/authorize';
    
    private $client_id     = '1236030103272716';
    
    private $redirect_uri  = 'https://blossomthemes.com/blossomthemes-social-feed-oauth/';
    // private $redirect_uri  = 'https://blossom.test/instagram-api-test/'; 
    
    private $response_type = 'code';
    
    private $scope         = 'user_profile,user_media';
    
    /**
     * Instagram 0auth URL.
     * @var string $auth_url Instagram Oauth URL with callback.
     */
    private $oauth_url = "";

    public static function get_instance() {
        if( null === self::$_instance ) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    private function __construct() {
        add_action( 'btif_settings_general_tab_content', array( $this, 'render_general_tab_content' ) );
        add_action( 'btif_settings_usage_tab_content', array( $this, 'render_usage_tab_content' ) );
        add_action( 'btif_settings_sidebar', array( $this, 'render_sidebar' ) );
        // add_shortcode( 'btif_instagram_new_accesscode', array( $this, 'btif_get_instagram_accesscode' ) );

        $this->oauth_url = add_query_arg( array(
            'client_id'     => $this->client_id,
            'response_type' => $this->response_type,
            'redirect_uri'  => $this->redirect_uri,
            'scope'         => $this->scope,
            'state'         => admin_url( 'admin.php?page=class-blossomthemes-instagram-feed-admin.php' )

        ), $this->base_url );
    }

    /**
     * Include settings sidebar.
     */
    public function render_sidebar() {
        require_once BTIF_BASE_PATH . '/includes/template/backend/sidebar.php';      
    }

    /**
     * Include the settings usage tab content template.
     */
    public function render_usage_tab_content() {
        require_once BTIF_BASE_PATH . '/includes/template/backend/usage.php';      
    }

    /**
     * Include the settings general tab content template.
     */
    public function render_general_tab_content() {
        // Authorize URL with client site redirect link
        $oauth_url = $this->oauth_url; 

        $options = self::get_settings();

        // Override options Access Token if received new access token by POST method
        if ( isset( $_POST['access_token'] ) ) {
            $options['access_token'] = $_POST['access_token'];

            $user_node = add_query_arg( array(
                'fields'       => 'username',
                'access_token' => $options['access_token'],

            ), 'https://graph.instagram.com/me' );
            
            $response = wp_remote_get( $user_node );

            if( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
                $body = json_decode( wp_remote_retrieve_body($response) );
    
                $options['username'] = $body->username;
    
                if( ! defined( 'DISABLE_WP_CRON' ) || 
                    ( defined( 'DISABLE_WP_CRON') && false === DISABLE_WP_CRON ) ){
                    if ( ! wp_next_scheduled( 'btif_refresh_token_cron_hook' ) ) {
                        wp_schedule_event( time(), 'fifty_days', 'btif_refresh_token_cron_hook' );
                    }
                }
            } else {
                update_option( 'blossomthemes_instagram_invalid_token', true, false );
            }

        }

        // If user denies the authorization
        if ( isset( $_GET['error_reason'] ) ) {
            update_option( 'blossomthemes_instagram_invalid_token', true, false );
        }

        $options['enable_lazy_load']     = isset( $options['enable_lazy_load'] ) && '' != $options['enable_lazy_load'] ? $options['enable_lazy_load'] : '';
        $options['enable_square_images'] = isset( $options['enable_square_images'] ) && '' != $options['enable_square_images'] ? $options['enable_square_images'] : '';
        
        extract( $options );

        require_once BTIF_BASE_PATH . '/includes/template/backend/general.php';      
    }

    function render_backend_settings() {
        require_once BTIF_BASE_PATH . '/includes/template/backend/settings.php';      
    }  

    public static function get_settings(){
        $settings = get_option( 'blossomthemes_instagram_feed_settings' );

        $settings = wp_parse_args( $settings, array(
            'access_token'         => '',
            'username'             => '',
            'photos'               => 5,
            'photos_row'           => 5,
            'follow_me'            => 'Follow Me!',
            'pull_duration'        => 1,
            'pull_unit'            => 'days',
            'enable_lazy_load'     => '',
            'enable_square_images' => ''
        ) );

        return $settings;
    }

    /**
     * Get access token.
     */
    public function get_access_token() {
        $settings = self::get_settings();
        return $settings['access_token'];
    }

    /**
     * Calculate posts fetch interval.
     */
    public function get_transient_lifetime() {
        $settings = self::get_settings();

        $values = array( 'minutes' => MINUTE_IN_SECONDS, 'hours' => HOUR_IN_SECONDS, 'days' => DAY_IN_SECONDS );
        $keys   = array_keys( $values );
        $type   = in_array( $settings['pull_unit'], $keys ) ? $values[ $settings['pull_unit'] ] : $values['minutes'];

        return $type * $settings['pull_duration'];
    }

    /*
     * Shortcode to fetch Access Token from Instagram using Instagram API 
     * and redirect to client URL 
     */
    function btif_get_instagram_accesscode(){
        if( is_admin() ) return;

        if( isset( $_GET['state'] ) ) {
            $page = stristr( $_GET['state'], 'page' );
            if( 'page=class-blossomthemes-instagram-feed-admin.php' !== $page ) {
                $_GET['state'] = str_replace( 'page', 'page=class-blossomthemes-instagram-feed-admin.php', $_GET['state'] );
            }
        }

        if( isset( $_GET['error_reason'] ) && 'user_denied' === $_GET['error_reason'] ) {
            echo $_GET['error_description'];

            $url = add_query_arg( array(
                'error_reason'      => $_GET['error_reason'],
                'error_description' => $_GET['error_description']
            ), esc_url( $_GET['state'] ) );
        ?>
            <script>       
            jQuery(document).ready(function($){
                setTimeout(function () {
                    window.location.href = "<?php echo $url;?>";
                }, 2000);
            });
            </script>
        <?php
            return;
        }
            
        if( !isset( $_GET['code'] ) && !isset( $_GET['state'] ) ) return;
        
        $url = 'https://api.instagram.com/oauth/access_token';

        $response = wp_remote_post( $url, array(
            'method'      => 'POST',
            'timeout'     => 45,
            'redirection' => 5,
            'blocking'    => true,
            'body'        => array(
                'client_id'     => $this->client_id,
                'client_secret' => 'ed9157ed049b42369d8931b95f877077',
                'grant_type'    => 'authorization_code',
                'redirect_uri'  => $this->redirect_uri,
                'code'          => $_GET['code'],
            )

        ) );

        if( is_wp_error( $response ) ) return;

        if( 200 !== wp_remote_retrieve_response_code( $response ) ) return;

        $body = json_decode( wp_remote_retrieve_body($response) );

        $long_live_token_url = add_query_arg( array(
            'client_secret' => 'ed9157ed049b42369d8931b95f877077',
            'access_token'  => $body->access_token,
            'grant_type'    => 'ig_exchange_token',

        ), 'https://graph.instagram.com/access_token' );
        
        $response = wp_remote_get( $long_live_token_url );

        if( is_wp_error( $response ) ) return;

        if( 200 !== wp_remote_retrieve_response_code( $response ) ) return;

        $body = json_decode( wp_remote_retrieve_body($response) );

        ?>
        <form id="btif_instagram_form" method="post" action="<?php echo $_GET['state'];?>">
            <input type="hidden" name="access_token" id="access_token" value="<?php echo $body->access_token;?>">
        </form>

        <script>
        
        jQuery(document).ready(function($){

            $("#btif_instagram_form").submit();

        });

        </script>

    <?php   
    }
}
