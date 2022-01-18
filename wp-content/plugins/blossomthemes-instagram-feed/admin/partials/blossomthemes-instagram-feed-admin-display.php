<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://blossomthemes.com
 * @since      1.0.0
 *
 * @package    Blossomthemes_Instagram_Feed
 * @subpackage Blossomthemes_Instagram_Feed/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
        <?php esc_html_e( 'Title', 'blossomthemes-instagram-feed' ); ?>
    </label>
    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" 
        name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" 
        value="<?php echo esc_attr( $title ); ?>" />
</p>

<p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>">
        <?php esc_html_e( 'Username', 'blossomthemes-instagram-feed' ); ?>
    </label>
    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>" 
        name="<?php echo esc_attr( $this->get_field_name( 'username' ) ); ?>" type="text" 
        value="<?php echo esc_attr( $username ); ?>" />
</p>

<p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>">
        <?php esc_html_e( 'Number of photos', 'blossomthemes-instagram-feed' ); ?>
    </label>
    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" 
        name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" 
        min="1" step="1" max="25" 
        value="<?php echo esc_attr( $limit ); ?>" />
</p>

<p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'per_row' ) ); ?>">
        <?php esc_html_e( 'Photos Per Row', 'blossomthemes-instagram-feed' ); ?>
    </label>
    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'per_row' ) ); ?>" 
        name="<?php echo esc_attr( $this->get_field_name( 'per_row' ) ); ?>" type="number" 
        min="1" max="5" step="1"
        value="<?php echo esc_attr( $per_row ); ?>" />
</p>

<p>
    <label for="<?php echo esc_attr( $this->get_field_id( 'profile_link_text' ) ); ?>">
        <?php esc_html_e( 'Profile Link Text', 'blossomthemes-instagram-feed' ); ?>
    </label>
    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'profile_link_text' ) ); ?>" 
    name="<?php echo esc_attr( $this->get_field_name( 'profile_link_text' ) ); ?>" type="text" 
    value="<?php echo esc_attr( $profile_link_text ); ?>" />
</p>
<?php
