<?php
/**
 * General usage tab content template.
 */
?>
<div class="blossomthemes-instagram-feed-settings usage" id="blossomthemes-instagram-feed-settings-usage">
    <h4><?php _e( 'Uses', 'blossomthemes-instagram-feed' ); ?></h4>
    <div class="wp-tm-settings-wrapper">
        <h4 class="wp-tm-setting-title">
            <?php _e('Display via Shortcode','blossomthemes-instagram-feed');?>
        </h4>
        <div class="wp-tm--option-wrapper">
            <div class="wp-tm-option-field">
                <label class="wp-tm-plain-label">
                    <div class="tm-option-side-note">
                        <?php _e('Copy this Shortcode to display your instagram gallery in pages/posts => ', 'blossomthemes-instagram-feed') ?>
                        <br>
                        <input type="text" readonly="readonly" class="shortcode-usage" 
                            value="[blossomthemes_instagram_feed]" 
                            onClick="this.setSelectionRange(0, this.value.length)" />
                    </div>
                </label>
            </div>
        </div>
        <h4 class="wp-tm-setting-title">
            <?php _e('Display via PHP Function','blossomthemes-instagram-feed');?>
        </h4>
        <div class="wp-tm--option-wrapper">
            <div class="wp-tm-option-field">
                <label class="wp-tm-plain-label">
                    <div class="tm-option-side-note">
                        <?php _e('Copy the PHP Function below to display your instagram gallery in templates :', 'blossomthemes-instagram-feed') ?>
                        <br>
                        <textarea rows="2" cols="50" readonly="readonly" 
                            onClick="this.setSelectionRange(0, this.value.length)">&lt;?php echo do_shortcode("[blossomthemes_instagram_feed]"); ?&gt; </textarea>
                    </div>
                </label>
            </div>
        </div>
    </div>
</div>
<?php
