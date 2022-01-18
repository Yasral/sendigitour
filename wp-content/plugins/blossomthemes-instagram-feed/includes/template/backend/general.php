<?php
/**
 * Settings general tab content template.
 */
?>
<div class="blossomthemes-instagram-feed-settings general" 
    id="blossomthemes-instagram-feed-settings-general">
    <div class="btif-option-field-wrap">
        <h2><?php _e('Connect with Instagram', 'blossomthemes-instagram-feed'); ?></h2>
        <p>
            <?php _e( 'To get started click the button below. You’ll be prompted to authorize BlossomThemes Social Feed to access your Instagram photos.', 'blossomthemes-instagram-feed' ); ?>
            <a href="https://blossomthemes.com/authenticate-instagram-account/" target="_blank">
                <?php _e('For help, please refer to this tutorial.', 'blossomthemes-instagram-feed');?>
            </a>
        </p>

        <p class="description">
            <?php _e( 'Due to recent Instagram API changes it is no longer possible to display photos from a different Instagram account than yours. The widget will automatically display the latest photos of the account which was authorized on this page.', 'blossomthemes-instagram-feed' ); ?>
        </p>

        <br /> 

        <!-- Connect with Instagram button -->
        <a class="button button-connect" target="_blank" href="<?php echo esc_url( $oauth_url ); ?>">
            <?php if ( ! Blossomthemes_Instagram_Feed_API::get_instance()->is_configured() ) : ?>
                <span><?php _e( 'Connect with Instagram', 'blossomthemes-instagram-feed' ); ?></span>
            <?php else: ?>
                <span class="btif-instagarm-connected"><?php _e( 'Re-connect with Instagram', 'blossomthemes-instagram-feed' ); ?></span>
            <?php endif; ?>
        </a>
        <!-- ./ Connect with Instagram button -->

        <!-- Access token input -->
        <p>
            <label for="blossomthemes_instagram_feed_settings[access_token]">
                <?php _e('Access Token', 'blossomthemes-instagram-feed'); ?>
            </label>
            <input class="" id="blossomthemes_instagram_feed_settings[access_token]"
                name="blossomthemes_instagram_feed_settings[access_token]" type="text" 
                value="<?php echo esc_attr( $access_token ); ?>" />
        </p>
        <!-- ./ Access token input -->

        <p class="btif-description">
            <?php _e( 'Access Token is used as key to access your photos from Instagram so they can be displayed.', 'blossomthemes-instagram-feed' ); ?>
        </p>
    </div>

    <!-- Username input -->
    <div class="btif-option-field-wrap">
        <label for="blossomthemes_instagram_feed_settings[username]">
            <?php _e('Username', 'blossomthemes-instagram-feed'); ?>
        </label>
        <input id="blossomthemes_instagram_feed_settings[username]" 
            name="blossomthemes_instagram_feed_settings[username]" type="text" 
            value="<?php echo esc_attr( $username ); ?>" />
    </div>
    <!-- ./ Username input -->

    <!-- Number of photos -->
    <div class="btif-option-field-wrap">
        <label for="blossomthemes_instagram_feed_settings[photos]">
            <?php _e('Number of Photos', 'blossomthemes-instagram-feed'); ?>
        </label>
        <input min="1" max="25" id="blossomthemes_instagram_feed_settings[photos]" 
            name="blossomthemes_instagram_feed_settings[photos]" type="number" 
            value="<?php echo esc_attr( $photos ); ?>" />
    </div>
    <!-- ./ Number of photos -->

    <!-- Photos per row -->
    <div class="btif-option-field-wrap">
        <label for="blossomthemes_instagram_feed_settings[photos_row]">
            <?php _e('Photos Per Row', 'blossomthemes-instagram-feed'); ?>
        </label>
        <input min="1" max="10" id="blossomthemes_instagram_feed_settings[photos_row]" 
            name="blossomthemes_instagram_feed_settings[photos_row]" type="number" 
            value="<?php echo esc_attr( $photos_row); ?>">
    </div>
    <!-- ./ Photos per row -->

    <!-- Profiel link text -->
    <div class="btif-option-field-wrap">
        <label for="blossomthemes_instagram_feed_settings[follow_me]">
            <?php _e('Profile Link Text', 'blossomthemes-instagram-feed'); ?>
        </label>
        <input id="blossomthemes_instagram_feed_settings[follow_me]" 
            name="blossomthemes_instagram_feed_settings[follow_me]" type="text" 
            value="<?php echo esc_attr( $follow_me ); ?>">
    </div>
    <!-- ./ Profile link text -->

    <!-- Check for New Posts -->
    <div class="btif-option-field-wrap">
        <label for="transient-pull-interval">
            <?php _e( 'Check for new posts every', 'blossomthemes-instagram-feed' ); ?>
        </label>
        <input type="number"
            id="transient-pull-interval"
            style="width: 102px;"
            name="blossomthemes_instagram_feed_settings[pull_duration]"
            value="<?php echo esc_attr( $pull_duration ) ?>"
            min="1" />

        <select id="blossomthemes_instagram_feed_settings[pull_unit]"
            style = "width: 102px;"
            name="blossomthemes_instagram_feed_settings[pull_unit]">
            <option <?php selected( $pull_unit, 'hours' ); ?> value="hours"><?php _e( 'Hours', 'blossomthemes-instagram-feed' ) ?></option>
            <option <?php selected( $pull_unit, 'days' ); ?> value="days"><?php _e( 'Days', 'blossomthemes-instagram-feed' ) ?></option>
        </select>
        <button type="button" id="btif-fetch-new-posts" 
            class="button button-large">
            <?php echo _e( 'Fetch new posts', 'blossomthemes-instagram-feed'); ?> 
            <span id="btif-fetch-new-posts-loader" style="display: none;">
                <svg class="lds-spinner" width="28px"  height="28px"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" style="background: none;"><g transform="rotate(0 50 50)">
                    <rect x="45.5" y="19.5" rx="9.1" ry="3.9" width="9" height="21" fill="#1087b0">
                        <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.875s" repeatCount="indefinite"></animate>
                    </rect>
                    </g><g transform="rotate(45 50 50)">
                    <rect x="45.5" y="19.5" rx="9.1" ry="3.9" width="9" height="21" fill="#1087b0">
                        <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.75s" repeatCount="indefinite"></animate>
                    </rect>
                    </g><g transform="rotate(90 50 50)">
                    <rect x="45.5" y="19.5" rx="9.1" ry="3.9" width="9" height="21" fill="#1087b0">
                        <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.625s" repeatCount="indefinite"></animate>
                    </rect>
                    </g><g transform="rotate(135 50 50)">
                    <rect x="45.5" y="19.5" rx="9.1" ry="3.9" width="9" height="21" fill="#1087b0">
                        <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.5s" repeatCount="indefinite"></animate>
                    </rect>
                    </g><g transform="rotate(180 50 50)">
                    <rect x="45.5" y="19.5" rx="9.1" ry="3.9" width="9" height="21" fill="#1087b0">
                        <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.375s" repeatCount="indefinite"></animate>
                    </rect>
                    </g><g transform="rotate(225 50 50)">
                    <rect x="45.5" y="19.5" rx="9.1" ry="3.9" width="9" height="21" fill="#1087b0">
                        <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.25s" repeatCount="indefinite"></animate>
                    </rect>
                    </g><g transform="rotate(270 50 50)">
                    <rect x="45.5" y="19.5" rx="9.1" ry="3.9" width="9" height="21" fill="#1087b0">
                        <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="-0.125s" repeatCount="indefinite"></animate>
                    </rect>
                    </g><g transform="rotate(315 50 50)">
                    <rect x="45.5" y="19.5" rx="9.1" ry="3.9" width="9" height="21" fill="#1087b0">
                        <animate attributeName="opacity" values="1;0" keyTimes="0;1" dur="1s" begin="0s" repeatCount="indefinite"></animate>
                    </rect>
                    </g>
                </svg>
            </span>

            <span id="btif-fetch-new-posts-success" style="display: none;">                
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="#32BEA6" d="M504.1,256C504.1,119,393,7.9,256,7.9C119,7.9,7.9,119,7.9,256C7.9,393,119,504.1,256,504.1C393,504.1,504.1,393,504.1,256z"/><path fill="#FFF" d="M392.6,172.9c-5.8-15.1-17.7-12.7-30.6-10.1c-7.7,1.6-42,11.6-96.1,68.8c-22.5,23.7-37.3,42.6-47.1,57c-6-7.3-12.8-15.2-20-22.3C176.7,244.2,152,229,151,228.4c-10.3-6.3-23.8-3.1-30.2,7.3c-6.3,10.3-3.1,23.8,7.2,30.2c0.2,0.1,21.4,13.2,39.6,31.5c18.6,18.6,35.5,43.8,35.7,44.1c4.1,6.2,11,9.8,18.3,9.8c1.2,0,2.5-0.1,3.8-0.3c8.6-1.5,15.4-7.9,17.5-16.3c0.1-0.2,8.8-24.3,54.7-72.7c37-39.1,61.7-51.5,70.3-54.9c0.1,0,0.1,0,0.3,0c0,0,0.3-0.1,0.8-0.4c1.5-0.6,2.3-0.8,2.3-0.8c-0.4,0.1-0.6,0.1-0.6,0.1l0-0.1c4-1.7,11.4-4.9,11.5-5C393.3,196.1,397,184.1,392.6,172.9z"/></svg>
            </span>

            <span id="btif-fetch-new-posts-failure" style="display: none;">
                <img width="16" height="16" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACwAAAAsCAYAAAAehFoBAAAExklEQVRYR9WZbUxbZRTH/+e2HYwh0NZpZAu0TYyLRsWpH8biCx/UJVMj0AtmkSgx6hL5YpyKyXTKVOSDxsXM16kzmuloYQvGvWiMM3Eqic6ZuIX50nYwJonrBWRhUHrvMbelHQVK2/vc4nySph/uPf//7zl53u55CIJtuM5dCYlqGVgFoEL/cfwfBPRj+kdAHzTeY+8OnhSxJCPBw7KriiE1gnEXCFflpME4Bmg9ROi0+0JHc4qNJyH7FpYrrgQsbcSoA1FOsXNcmJkBv02lzSV7Ar9lS5GVqSJ7KsD8IggbAJKyFc/qPUYUxO8XTEafX9YzcDpTTEbgEa/7do2oE0BpJjHB56MSc0OZP/jFQjoLAiv17kchYRtAFkGYLMNZJaDZ7gt+lC4gLbAiu18HqCVLJ1NfY+YXHP7gswTwbOF5gZV610ZI0pumUuQoxho/7OwKvpsROOytrCayHAJgy9HD7NenoEZrHN39h2cKp2Q4XLdiJVkKjgBYbra7Qb2/WZ1c7ewePJWITwWW3QcIdIdB8byEMfig0xdcNwd42Ou6lUn6Oi+ugqLEWo3dH9KH6fmdTvF6DoNQLaidr/BvHb7ATUlgxeu6FyR9ki83M3RJ02rtXaG9sTGsyO5PAWo0QzhfGsz8odMffID4etgUj+cMASUiZrYbb4M68Du0oVCKjHRxOayXVyHy/T4ReTD4tNMXXEFmTLaljY+hsL4FfHYEY21NUEPHY3BSuQclz+0ClS3H5MGPMf7eFjFoVVtDitfzMghPGVXSoUpf+zIZnoDmyEQSNvFw7JkGRE/8ZNRK36g7KCy69koSip98B7bVNSnQiE7FMpto0b4fMba1CZiKGAbW12RSZPdRgK41rKIHWpegeNMbKdAz9WKwLzUDE+NCNszcq2d4iECXCilNQ1/UugPWa9amSKl//IJ/2u4Tho2JMp/Ux/AECAWiwNJlLpRs2QVypPZ99kQU8mFMkiJ7RkS/JtLBJuBMhB7Vh0Qfga4w2nNaVoLSVw6kZDZ6vBc8MT5nIo5uWg9Whoxa6WvxCX3SHQLoFqMqZL8EZdu+AgqLYhI67Fj7g4Cqpk7EaASjT9wJbfBPo1b6IP5GBxbelq2rbkBx6w6ogV8x1vEQMHkuDqWvHo9vh+3qapx9tQVTR0QPg7xbH8OtANoFuh0PLVh6HnS2mJ59wSVtWvJpmq7i/CwMvAgCBO262Gkt7PUMEGHlIngatkgefuLA7p1EdL9htcUIZLzt8Ac2xs/D/7cDfAzajDNFvjLN+M7hD8T2/ORXc1h2rSNI+/PlKaI770fodJaFNhERqHSxaT/zY5PvwiuknClEpKrId2ow0aE5tTWlrmItLFZ9S/rPS1Wsajc7u0M/zMz+BVsMJGjNdl9o5+yhkr7c6vW8BcIj+RiXGTUZHQ5/QD8yzGkLF7RlzwYAHwBYktHEnBcirHGTsyuoV/znbRmvDMJy5RpA8hOo3Bym+VWY+S8rc21pV6h3IZ+MwHrwyPoKOxdZNnO8Im92tiPM2nbLOW1r2ef9w5mSkhVwQmT4HpeLrVI7wI1mXHuB0ElT3GrfG0otFy1AnRNwEly/WGQ0ANLdhi4WCZ8RtN15v1icr+PZX93yMWjoEb26/Rc6jtfU5/NTSgAAAABJRU5ErkJggg==" />
            </span>
        </button>
    </div>

    <!-- Lazy Load enable input -->
    <div class="btif-option-field-wrap">
        <label for="blossomthemes_instagram_feed_settings[enable_lazy_load]">
            <?php _e('Lazy Load', 'blossomthemes-instagram-feed'); ?>
        </label>
        <input id="blossomthemes_instagram_feed_settings[enable_lazy_load]" 
            name="blossomthemes_instagram_feed_settings[enable_lazy_load]" type="checkbox" 
            value="1" <?php checked( $enable_lazy_load, 1 ); ?>/>

        <p class="btif-description">
            <?php _e( 'Enable lazy loading of Instagram images.', 'blossomthemes-instagram-feed' ); ?>
        </p>
    </div>
    <!-- ./ Lazy Load enable input -->

    <!-- Square Images enable input -->
    <div class="btif-option-field-wrap">
        <label for="blossomthemes_instagram_feed_settings[enable_square_images]">
            <?php _e('Square Images', 'blossomthemes-instagram-feed'); ?>
        </label>
        <input id="blossomthemes_instagram_feed_settings[enable_square_images]" 
            name="blossomthemes_instagram_feed_settings[enable_square_images]" type="checkbox" 
            value="1" <?php checked( $enable_square_images, 1 ); ?>/>

        <p class="btif-description">
            <?php _e( 'Enable square Instagram images.', 'blossomthemes-instagram-feed' ); ?>
        </p>
    </div>
    <!-- ./ Square Images enable input -->
</div>
<?php
