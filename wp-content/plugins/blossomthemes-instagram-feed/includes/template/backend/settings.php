<?php
/**
 * Settings template.
 */
?>
<div class="btif-wrap">
    <?php settings_errors(); ?>
    <div class="btif-header">
        <h3><?php _e( 'BlossomThemes Social Feed', 'blossomthemes-instagram-feed' ); ?></h3>
    </div>

    <div class="btif-inner-wrap">
        <h2 class="nav-tab-wrapper">
            <a href="#" class="btss-tab-trigger nav-tab nav-tab-active" data-configuration="general">
                <?php _e('General','blossomthemes-instagram-feed');?>
            </a>
            
            <a href="#" class="btss-tab-trigger nav-tab" data-configuration="usage">
                <?php _e('Usage','blossomthemes-instagram-feed');?>
            </a>
        </h2>  
        
        <form method="post" action="options.php" class="btif-settings-form">
            <?php
                /**
                 * Add hook to include the settings general tab content
                 */
                do_action( 'btif_settings_general_tab_content' );

                /**
                 * Add hook toinclude the settings ussae tab content.
                 */
                do_action( 'btif_settings_usage_tab_content' );
            ?>
            
            <!-- Generate CSRF token -->
            <?php $nonce = wp_create_nonce( 'blossomthemes-instagram-feed-nonce' ); ?>
            <input type="hidden"name="blossomthemes-instagram-feed-nonce" value="<?php echo esc_attr( $nonce ); ?>" />
            <!-- ./ Generate CSRF token -->

            <!-- Submit button -->
            <div class="blossomthemes-instagram-feed-settings-submit">
                <?php
                    settings_fields( 'blossomthemes_instagram_feed_settings' );
                    do_settings_sections( __FILE__ );
                    echo submit_button();
                ?>
            </div>
            <!-- ./ Submit button -->
        </form>
    </div>

    <?php
        /**
         * Add hook to include sidebar.
         */
        do_action( 'btif_settings_sidebar' );
    ?>
</div>
<?php
