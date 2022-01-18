<?php
/**
 * Theme Customizer
 *
 * @package Blossom_Consulting
 */

/**
 * Customize Controls
*/
function blossom_consulting_customize_controls( $wp_customize ){
    //Removed control from parent theme.
    $wp_customize->remove_setting( 'ed_banner_section' );
    $wp_customize->remove_control( 'ed_banner_section' );
    $wp_customize->remove_setting( 'site_title_font_size' );
    $wp_customize->remove_control( 'site_title_font_size' );
    
    //Overwrite default value from parent theme.
    $wp_customize->add_setting( 
        'site_title_font_size', 
        array(
            'default'           => 25,
            'sanitize_callback' => 'blossom_coach_sanitize_number_absint'
        ) 
    );

    $wp_customize->add_control(
        new Blossom_Coach_Slider_Control( 
            $wp_customize,
            'site_title_font_size',
            array(
                'section'     => 'title_tagline',
                'label'       => __( 'Site Title Font Size', 'blossom-consulting' ),
                'description' => __( 'Change the font size of your site title.', 'blossom-consulting' ),
                'priority'    => 65,
                'choices'     => array(
                    'min'   => 10,
                    'max'   => 200,
                    'step'  => 1,
                )                 
            )
        )
    );

    //Overwrite default value from parent theme.
    $wp_customize->add_setting( 
        'ed_consulting_banner_section', 
        array(
            'default'           => 'slider_banner',
            'sanitize_callback' => 'blossom_coach_sanitize_select'
        ) 
    );

    //Added control with same ID with extra choice.
    $wp_customize->add_control(
		new Blossom_Coach_Select_Control(
    		$wp_customize,
    		'ed_consulting_banner_section',
    		array(
                'label'	      => __( 'Banner Options', 'blossom-consulting' ),
                'description' => __( 'Choose banner as static image/video or as a slider.', 'blossom-consulting' ),
    			'section'     => 'header_image',
    			'choices'     => array(
                    'no_banner'        => __( 'Disable Banner Section', 'blossom-consulting' ),                            
                    'static_banner'    => __( 'Static/Video CTA Banner', 'blossom-consulting' ),
                    'static_nl_banner' => __( 'Static/Video Newsletter Banner', 'blossom-consulting' ),
                    'slider_banner'    => __( 'Banner as Slider', 'blossom-consulting' ),
                ),
                'priority' => 5	
     		)            
		)
	);
    
    /** Title */
    $wp_customize->add_setting(
        'banner_title',
        array(
            'default'           => __( 'The Secrets to Successful Team Leadership', 'blossom-consulting' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'banner_title',
        array(
            'label'           => __( 'Title', 'blossom-consulting' ),
            'section'         => 'header_image',
            'type'            => 'text',
            'active_callback' => 'blossom_coach_banner_ac'
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'banner_title', array(
        'selector'        => '.site-banner .banner-caption .banner-wrap .banner-title',
        'render_callback' => 'blossom_coach_get_banner_title',
    ) );
    
    /** Sub Title */
    $wp_customize->add_setting(
        'banner_subtitle',
        array(
            'default'           => __( 'Sally is a solution focused therapist offering brief and often single session therapy. She has built a reputation for engaging workshops on Solution Focused Brief Therapy and Single Session Therapy, and have recently started to put her courses online.', 'blossom-consulting' ),
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'banner_subtitle',
        array(
            'label'           => __( 'Sub Title', 'blossom-consulting' ),
            'section'         => 'header_image',
            'type'            => 'textarea',
            'active_callback' => 'blossom_coach_banner_ac'
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'banner_subtitle', array(
        'selector'        => '.site-banner .banner-caption .banner-wrap .b-content',
        'render_callback' => 'blossom_coach_get_banner_sub_title',
    ) );
    
    /** Banner Label */
    $wp_customize->add_setting(
        'banner_label',
        array(
            'default'           => __( 'Discover More', 'blossom-consulting' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'banner_label',
        array(
            'label'           => __( 'Banner Label', 'blossom-consulting' ),
            'section'         => 'header_image',
            'type'            => 'text',
            'active_callback' => 'blossom_coach_banner_ac'
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'banner_label', array(
        'selector' => '.site-banner .banner-caption .banner-wrap .banner-link',
        'render_callback' => 'blossom_coach_get_banner_label',
    ) );
    
    /** Banner Link */
    $wp_customize->add_setting(
        'banner_link',
        array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'banner_link',
        array(
            'label'           => __( 'Banner Link', 'blossom-consulting' ),
            'section'         => 'header_image',
            'type'            => 'text',
            'active_callback' => 'blossom_coach_banner_ac'
        )
    );

    /** Shop Section */
    $wp_customize->add_section(
        'shop_section',
        array(
            'title'    => __( 'Shop Section', 'blossom-consulting' ),
            'priority' => 46,
            'panel'    => 'frontpage_settings',
            'active_callback' => 'blossom_coach_is_woocommerce_activated'
        )
    );

    /** Enable Shop Section */
    $wp_customize->add_setting(
        'ed_shop_section',
        array(
            'default'           => true,
            'sanitize_callback' => 'blossom_coach_sanitize_checkbox'
        )
    );

    $wp_customize->add_control(
        new Blossom_Coach_Toggle_Control(
            $wp_customize,
            'ed_shop_section',
            array(
                'label'       => __( 'Enable Shop Section', 'blossom-consulting' ),
                'description' => __( 'Enable to show shop section.', 'blossom-consulting' ),
                'section'     => 'shop_section',
            )            
        )
    );

    /** Section title */
    $wp_customize->add_setting(
        'shop_section_title',
        array(
            'default'           => __( 'Shop', 'blossom-consulting' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'shop_section_title',
        array(
            'section' => 'shop_section',
            'label'   => __( 'Section Title', 'blossom-consulting' ),
            'type'    => 'text',
        )
    );

    /** Selective refresh for blog title. */
    $wp_customize->selective_refresh->add_partial( 'shop_section_title', array(
        'selector'        => '.shop-section .section-title',
        'render_callback' => 'blossom_coach_get_shop_section_title',
    ) );

    /** Section Content */
    $wp_customize->add_setting(
        'shop_section_content',
        array(
            'default'           => __( 'Show your products here. You can modify this section from Appearance > Customize > Front Page Settings > Shop Section.', 'blossom-consulting' ),
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'shop_section_content',
        array(
            'section' => 'shop_section',
            'label'   => __( 'Section Content', 'blossom-consulting' ),
            'type'    => 'textarea',
        )
    ); 

    /** Selective refresh for blog description. */
    $wp_customize->selective_refresh->add_partial( 'shop_section_content', array(
        'selector'        => '.shop-section .section-content',
        'render_callback' => 'blossom_coach_get_shop_section_content',
    ) );
    
    /** Product One */
    $wp_customize->add_setting(
        'product_one',
        array(
            'default'           => '',
            'sanitize_callback' => 'blossom_coach_sanitize_select'
        )
    );

    $wp_customize->add_control(
        new Blossom_Coach_Select_Control(
            $wp_customize,
            'product_one',
            array(
                'label'   => __( 'Product One', 'blossom-consulting' ),
                'section' => 'shop_section',
                'choices' => blossom_coach_get_posts( 'product' ),
            )
        )
    );
    
    /** Product Two */
    $wp_customize->add_setting(
        'product_two',
        array(
            'default'           => '',
            'sanitize_callback' => 'blossom_coach_sanitize_select'
        )
    );

    $wp_customize->add_control(
        new Blossom_Coach_Select_Control(
            $wp_customize,
            'product_two',
            array(
                'label'   => __( 'Product Two', 'blossom-consulting' ),
                'section' => 'shop_section',
                'choices' => blossom_coach_get_posts( 'product' ),
            )
        )
    ); 
    
    /** Product Three */
    $wp_customize->add_setting(
        'product_three',
        array(
            'default'           => '',
            'sanitize_callback' => 'blossom_coach_sanitize_select'
        )
    );

    $wp_customize->add_control(
        new Blossom_Coach_Select_Control(
            $wp_customize,
            'product_three',
            array(
                'label'   => __( 'Product Three', 'blossom-consulting' ),
                'section' => 'shop_section',
                'choices' => blossom_coach_get_posts( 'product' ),
            )
        )
    );
    
    /** Product Four */
    $wp_customize->add_setting(
        'product_four',
        array(
            'default'           => '',
            'sanitize_callback' => 'blossom_coach_sanitize_select'
        )
    );

    $wp_customize->add_control(
        new Blossom_Coach_Select_Control(
            $wp_customize,
            'product_four',
            array(
                'label'   => __( 'Product Four', 'blossom-consulting' ),
                'section' => 'shop_section',
                'choices' => blossom_coach_get_posts( 'product' ),
            )
        )
    );

    // Theme Demo & Documantation
    $wp_customize->add_section( 'theme_info', array(
        'title'       => __( 'Demo & Documentation' , 'blossom-consulting' ),
        'priority'    => 6,
    ) );
    
    /** Important Links */
    $wp_customize->add_setting( 'theme_info_theme',
        array(
            'default' => '',
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    
    $theme_info = '<p>';
    $theme_info .= sprintf( __( 'Demo Link: %1$sClick here.%2$s', 'blossom-consulting' ),  '<a href="' . esc_url( 'https://blossomthemes.com/theme-demo/?theme=blossom-consulting' ) . '" target="_blank">', '</a>' );
    $theme_info .= '</p><p>';
    $theme_info .= sprintf( __( 'Documentation Link: %1$sClick here.%2$s', 'blossom-consulting' ),  '<a href="' . esc_url( 'https://docs.blossomthemes.com/docs/blossom-consulting/' ) . '" target="_blank">', '</a>' );
    $theme_info .= '</p>';

    $wp_customize->add_control( new Blossom_Coach_Note_Control( $wp_customize,
        'theme_info_theme', 
            array(
                'section'     => 'theme_info',
                'description' => $theme_info
            )
        )
    );
    
}
add_action( 'customize_register', 'blossom_consulting_customize_controls', 99 );

/**
 * Get Banner Title
*/
function blossom_coach_get_banner_title(){
    return esc_html( get_theme_mod( 'banner_title', __( 'The Secrets to Successful Team Leadership', 'blossom-consulting' ) ) );
}

/**
 * Get Banner Sub Title
*/
function blossom_coach_get_banner_sub_title(){
    return wpautop( wp_kses_post( get_theme_mod( 'banner_subtitle', __( 'Sally is a solution focused therapist offering brief and often single session therapy. She has built a reputation for engaging workshops on Solution Focused Brief Therapy and Single Session Therapy, and have recently started to put her courses online.', 'blossom-consulting' ) ) ) );
}

/**
 * Get Banner Label
*/
function blossom_coach_get_banner_label(){
    return esc_html( get_theme_mod( 'banner_label', __( 'Discover More', 'blossom-consulting' ) ) );
}

/**
 * Shop Section Title
*/
function blossom_coach_get_shop_section_title(){
    return esc_html( get_theme_mod( 'shop_section_title', __( 'Shop', 'blossom-consulting' ) ) );
}

/**
 * Shop Section Content
*/
function blossom_coach_get_shop_section_content(){
    return wpautop( wp_kses_post( get_theme_mod( 'shop_section_content', __( 'Show your products here. You can modify this section from Appearance > Customize > Front Page Settings > Shop Section.', 'blossom-consulting' ) ) ) );
}

/**
 * Active Callback for Banner Slider
*/
function blossom_coach_banner_ac( $control ){
    $banner      = $control->manager->get_setting( 'ed_consulting_banner_section' )->value();
    $slider_type = $control->manager->get_setting( 'slider_type' )->value();
    $control_id  = $control->id;
    
    if ( $control_id == 'header_image' && ( $banner == 'static_banner' || $banner == 'static_nl_banner' ) ) return true;
    if ( $control_id == 'header_video' && ( $banner == 'static_banner' || $banner == 'static_nl_banner' ) ) return true;
    if ( $control_id == 'external_header_video' && ( $banner == 'static_banner' || $banner == 'static_nl_banner' ) ) return true;
    if ( $control_id == 'banner_title' && $banner == 'static_banner' ) return true;
    if ( $control_id == 'banner_subtitle' && $banner == 'static_banner' ) return true;
    if ( $control_id == 'banner_label' && $banner == 'static_banner' ) return true;
    if ( $control_id == 'banner_link' && $banner == 'static_banner' ) return true;
    if ( $control_id == 'banner_newsletter' && $banner == 'static_nl_banner' ) return true;
    
    if ( $control_id == 'slider_type' && $banner == 'slider_banner' ) return true;          
    if ( $control_id == 'slider_animation' && $banner == 'slider_banner' ) return true;
    
    if ( $control_id == 'slider_cat' && $banner == 'slider_banner' && $slider_type == 'cat' ) return true;
    if ( $control_id == 'no_of_slides' && $banner == 'slider_banner' && $slider_type == 'latest_posts' ) return true;
    
    return false;
}

function blossom_consulting_dequeue_script(){
    wp_dequeue_script( 'blossom-coach-customize' );
}
add_action( 'wp_print_scripts', 'blossom_consulting_dequeue_script', 99 );

function blossom_consulting_customize_script(){
    wp_enqueue_script( 'blossom-consulting-customize', get_stylesheet_directory_uri() . '/inc/js/customize.js', array( 'jquery', 'customize-controls' ), BLOSSOM_COACH_THEME_VERSION, true );
}
add_action( 'customize_controls_enqueue_scripts', 'blossom_consulting_customize_script' );