<?php
/**
 * Theme functions and definitions
 *
 * @package Blossom_Consulting
 */
 
/**
 * Enqueue parent style 
*/	 
function blossom_consulting_enqueue_styles() {
    
    if( blossom_coach_is_woocommerce_activated() ){
        $dependencies = array( 'blossom-coach-woocommerce', 'owl-carousel', 'animate', 'blossom-coach-google-fonts' );    
    }else{
        $dependencies = array( 'owl-carousel', 'animate', 'blossom-coach-google-fonts' );
    }
        
    wp_enqueue_style( 'blossom-consulting-parent-style', get_template_directory_uri() . '/style.css', $dependencies );

} 
add_action( 'wp_enqueue_scripts', 'blossom_consulting_enqueue_styles' );

/**
 * Sets up theme defaults and registers support for various WordPress features.
*/
function blossom_consulting_setup(){
    /*
     * Make chile theme available for translation.
     * Translations can be filed in the /languages/ directory.
     */
    load_child_theme_textdomain( 'blossom-consulting', get_stylesheet_directory() . '/languages' );

    //add image size
    add_image_size( 'blossom-coach-shop', 540, 690, true );
}
add_action( 'after_setup_theme', 'blossom_consulting_setup' );

/**
 * Remove action from parent
*/
function blossom_consulting_remove_action(){
    remove_action( 'wp_enqueue_scripts', 'blossom_coach_dynamic_css', 99 );
    remove_action( 'customize_register', 'blossom_coach_customizer_theme_info' );
}
add_action( 'init', 'blossom_consulting_remove_action' );

function blossom_consulting_overide_values( $wp_customize ){
    $wp_customize->get_setting( 'wheeloflife_color' )->default = '#fafbfd';
}
add_action( 'customize_register', 'blossom_consulting_overide_values', 999 );

/**
 * Dynamic Styles
*/
function blossom_consulting_dynamic_css(){
    
    $primary_font    = get_theme_mod( 'primary_font', 'Nunito Sans' );
    $primary_fonts   = blossom_coach_get_fonts( $primary_font, 'regular' );
    $secondary_font  = get_theme_mod( 'secondary_font', 'Nunito' );
    $secondary_fonts = blossom_coach_get_fonts( $secondary_font, 'regular' );
    
    $site_title_font      = get_theme_mod( 'site_title_font', array( 'font-family'=>'Nunito', 'variant'=>'700' ) );
    $site_title_fonts     = blossom_coach_get_fonts( $site_title_font['font-family'], $site_title_font['variant'] );
    $site_title_font_size = get_theme_mod( 'site_title_font_size', 25 );

    $wheeloflife_color = get_theme_mod( 'wheeloflife_color', '#fafbfd' );
    
    $custom_css = '';
    $custom_css .= '

    :root {
        --primary-font: ' . esc_html( $primary_fonts['font'] ) . ';
        --secondary-font: ' . esc_html( $secondary_fonts['font'] ) . ';
    }
    
    .site-title, 
    .site-title-wrap .site-title{
        font-size   : ' . absint( $site_title_font_size ) . 'px;
        font-family : ' . esc_html( $site_title_fonts['font'] ) . ';
        font-weight : ' . esc_html( $site_title_fonts['weight'] ) . ';
        font-style  : ' . esc_html( $site_title_fonts['style'] ) . ';
    }
    
    section#wheeloflife_section {
        background-color: ' . blossom_coach_sanitize_hex_color( $wheeloflife_color ) . ';
    }';

    wp_add_inline_style( 'blossom-coach', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'blossom_consulting_dynamic_css', 99 );

/**
 * Returns Home Sections 
*/
function blossom_coach_get_home_sections(){
    $ed_banner = get_theme_mod( 'ed_banner_section', 'slider_banner' );
    $sections = array( 
        'client'        => array( 'sidebar' => 'client' ),
        'about'         => array( 'sidebar' => 'about' ),
        'cta'           => array( 'sidebar' => 'cta' ),
        'testimonial'   => array( 'sidebar' => 'testimonial' ),
        'service'       => array( 'sidebar' => 'service' ),
        'wheel-of-life' => array( 'section' => 'wheel-of-life' ),
        'blog'          => array( 'section' => 'blog' ),
        'simple-cta'    => array( 'sidebar' => 'simple-cta' ),
        'shop'          => array( 'section' => 'shop' ),
        'contact'       => array( 'sidebar' => 'contact' ), 
    );
    
    $enabled_section = array();
    
    if( $ed_banner == 'static_nl_banner' || $ed_banner == 'slider_banner' || $ed_banner == 'static_banner' ) array_push( $enabled_section, 'banner' );
    
    foreach( $sections as $k => $v ){
        if( array_key_exists( 'sidebar', $v ) ){
            if( is_active_sidebar( $v['sidebar'] ) ) array_push( $enabled_section, $v['sidebar'] );
        }else{
            if( get_theme_mod( 'ed_' . $v['section'] . '_section', true ) ) array_push( $enabled_section, $v['section'] );
        }
    }  
    
    return apply_filters( 'blossom_coach_home_sections', $enabled_section );
}

/**
 * Footer Bottom
*/
function blossom_coach_footer_bottom(){ ?>
    <div class="bottom-footer">
        <div class="wrapper">
            <div class="copyright">            
            <?php
                blossom_coach_get_footer_copyright();
                esc_html_e( ' Blossom Consulting | Developed By ', 'blossom-consulting' );                
                echo '<a href="' . esc_url( 'https://blossomthemes.com/' ) .'" rel="nofollow" target="_blank">' . esc_html__( 'Blossom Themes', 'blossom-consulting' ) . '</a>.';                
                printf( esc_html__( ' Powered by %s', 'blossom-consulting' ), '<a href="'. esc_url( __( 'https://wordpress.org/', 'blossom-consulting' ) ) .'" target="_blank">WordPress</a>.' );
                if ( function_exists( 'the_privacy_policy_link' ) ) {
                    the_privacy_policy_link();
                }
            ?>               
            </div>
        </div><!-- .wrapper -->
    </div><!-- .bottom-footer -->
    <?php
}

/**
 * Customizer Controls
*/
require get_stylesheet_directory() . '/inc/customizer.php';