<?php
/**
 * Wheel of Life Section
 * 
 * @package Blossom_Coach
 */

$ed_section         = get_theme_mod( 'ed_wol_section', false );
$section_title      = get_theme_mod( 'wol_section_title' );
$section_content    = get_theme_mod( 'wol_section_content' );
$section_img        = get_theme_mod( 'wheeloflife_img', get_template_directory_uri() . '/images/chart.png' );
$alt_image          = attachment_url_to_postid( $section_img );
$section_shortcode  = get_theme_mod( 'wheeloflife_shortcode' );

if( ( $section_title || $section_content || $section_shortcode ) && $ed_section ){ ?>
    <section id="wheeloflife_section" class="wheeloflife-section">
        <div class="wrapper">
            <?php 
                if( $section_title ) echo '<h2 class="section-title"><span>' . esc_html( $section_title ) . '</span></h2>';
                if( $section_content || $section_img ){ ?>
                    <div class="section-content">
                        <?php echo '<p>' . esc_html( $section_content ) . '</p>'; ?>
                        <img src="<?php echo esc_url( $section_img ); ?>"  alt="<?php echo esc_attr( get_post_meta( $alt_image, '_wp_attachment_image_alt', true ) ); ?>">
                    </div>
                <?php }
                if( $section_shortcode ) echo do_shortcode( wp_kses_post( $section_shortcode ) ); 
            ?>
        </div>
    </section>    
<?php
}