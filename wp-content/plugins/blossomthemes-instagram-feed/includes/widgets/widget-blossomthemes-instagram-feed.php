<?php
/**
 * Widget Instagram
 *
 * @package BlossomThemes
 */

// register BlossomThemes_Instagram_Widget widget
function btif_register_instagram_widget() {
    register_widget( 'BlossomThemes_Instagram_Widget' );
}
add_action( 'widgets_init', 'btif_register_instagram_widget' );

/**
 * Adds BlossomThemes_Instagram_Widget widget.
 */
class BlossomThemes_Instagram_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'btif_instagram_widget', // Base ID
            __( 'BlossomThemes: Feed for Instagram', 'blossomthemes-instagram-feed' ), // Name
            array( 'description' => __( 'A widget that displays your latest Instagram photos.', 'blossomthemes-instagram-feed' ), ) // Args
        );
    }
    
    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */   
    function widget( $args, $instance ) {

        $settings               = BlossomThemes_Instagram_Feed_Settings::get_settings();
        $title                  = empty( $instance['title'] ) ? '' : $instance['title'];
        $settings['photos']     = empty( $instance['number'] ) ? 6 : $instance['number'];
        $settings['photos_row'] = empty( $instance['per_row'] ) ? 5 : $instance['per_row'];
        $username               = empty( $instance['username'] ) ? $settings['username'] : $instance['username'];
        $instaUrl               = 'https://www.instagram.com/'.$username ;
        $settings['follow_me']  = 'Follow Me!';

        if( isset( $instance['profile_link_text'] ) && $instance['profile_link_text']!='' ){
            $settings['follow_me'] = $instance['profile_link_text'];
        } 

        echo $args['before_widget'];

        if ( $title ) 
        echo $args['before_title'] . apply_filters( 'widget_title', $title, $instance, $this->id_base ) . $args['after_title'];

        ob_start();

        $this->api = Blossomthemes_Instagram_Feed_API::get_instance();
        $items = $this->api->get_items( $settings['photos'] );

        add_filter('widget_text','do_shortcode');

        if ( ! is_array( $items ) ) {
			if ( current_user_can( 'edit_theme_options' ) ) { 
                echo '<p>'.__( 'BlossomThemes Social Feed misconfigured, check plugin &amp; widget settings.',
                'blossomthemes-instagram-feed' ).'</p>'; 
            } else {
				echo '<b style="color:red;">'.__('No posts available!','blossomthemes-instagram-feed').'</b>';
			}
		} else {
            require plugin_dir_path( BTIF_FILE_PATH ) . 'public/partials/blossomthemes-instagram-feed-public-display.php';            
        }                        
        
        $output = ob_get_clean();
        echo $output;
        echo $args['after_widget'];
    }
    
    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    function form( $instance ) {
        $default = array( 
            'title'         => __( 'Instagram', 'blossomthemes-instagram-feed' ), 
            'number'        => 6, 
            'per_row'       => 5 
        );
        $instance = wp_parse_args( (array) $instance, $default );
        $options  = BlossomThemes_Instagram_Feed_Settings::get_settings();
        $username           = empty( $instance['username'] ) ? $options['username'] : $instance['username'];
        $title              = empty( $instance['title'] ) ? '' : $instance['title'];
        $limit              = empty( $instance['number'] ) ? 6 : $instance['number'];
        $per_row            = empty( $instance['per_row'] ) ? 5 : $instance['per_row'];
        $profile_link_text  = 'Follow Me!';
        if( isset( $instance['profile_link_text'] ) && $instance['profile_link_text']!='' ){
            $profile_link_text = $instance['profile_link_text'];
        }

        if ( ! Blossomthemes_Instagram_Feed_API::get_instance()->is_configured() ) : ?>

            <p><b style="color: red;">
                <?php
                printf( __( 'Please configure <a href="%1$s">Plugin Settings</a> before using this widget.', 'blossomthemes-instagram-feed' ),
                    admin_url( 'admin.php?page=class-blossomthemes-instagram-feed-admin.php' ) );
                ?>
            </b></p>

        <?php endif; 

        require plugin_dir_path( BTIF_FILE_PATH ) . 'admin/partials/blossomthemes-instagram-feed-admin-display.php';
    }
    
    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        
        $instance['title']             = strip_tags( $new_instance['title'] );
        $instance['number']            = ! absint( $new_instance['number'] ) ? 6 : $new_instance['number'];
        $instance['per_row']           = ! absint( $new_instance['per_row'] ) ? 5 : $new_instance['per_row'];
        $instance['username']          = $new_instance['username'] ;
        $instance['profile_link_text'] = $new_instance['profile_link_text'] ;

        return $instance;
    }
}
