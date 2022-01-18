<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://blossomthemes.com
 * @since      1.0.0
 *
 * @package    Blossomthemes_Instagram_Feed
 * @subpackage Blossomthemes_Instagram_Feed/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<ul class="popup-gallery photos-<?php echo $settings['photos_row']; echo $settings['enable_square_images'] ? ' btif-square-images' : ''; ?>">
<?php foreach ( $items as $item ) : ?>
    <li>
        <?php if( 'VIDEO' === $item->media_type ) : ?>
        <a href="<?php echo esc_url($item->media_url); ?>" class="mfp-iframe">
            <?php if( $settings['enable_lazy_load'] ) : ?>
                <img class="btif-lazy-load" data-src="<?php echo esc_url($item->thumbnail_url); ?>" 
                    data-alt="<?php echo esc_attr($item->caption); ?>">
            <?php else: ?>
                <img src="<?php echo esc_url($item->thumbnail_url); ?>" 
                    alt="<?php echo esc_attr($item->caption); ?>">
            <?php endif; ?>
        </a>
        <?php else: ?>
        <a href="<?php echo esc_url($item->media_url); ?>">
            <?php if( $settings['enable_lazy_load'] ) : ?>
                <img class="btif-lazy-load" data-src="<?php echo esc_url($item->media_url); ?>" 
                    data-alt="<?php echo esc_attr($item->caption); ?>">
            <?php else: ?>
                <img src="<?php echo esc_url($item->media_url); ?>" 
                    alt="<?php echo esc_attr($item->caption); ?>">
            <?php endif; ?>
        </a>
        <?php endif; ?>
    </li>
<?php endforeach; ?>
</ul>

<a class="profile-link" href="<?php echo esc_url($instaUrl);?>" target="_blank" rel="noreferrer">
    <span class="insta-icon"><i class="fab fa-instagram"></i></span><?php echo esc_html( $settings['follow_me'] );?>
</a>

<?php 
