<?php
/**
 * Blossom Coach Front Page Settings
 *
 * @package Blossom_Coach
 */

function blossom_coach_customize_register_frontpage( $wp_customize ) {
	
    /** Front Page Settings */
    $wp_customize->add_panel( 
        'frontpage_settings',
         array(
            'priority'    => 60,
            'capability'  => 'edit_theme_options',
            'title'       => __( 'Front Page Settings', 'blossom-coach' ),
            'description' => __( 'Static Home Page settings.', 'blossom-coach' ),
        ) 
    );
    
    $wp_customize->get_section( 'header_image' )->panel                    = 'frontpage_settings';
    $wp_customize->get_section( 'header_image' )->title                    = __( 'Banner Section', 'blossom-coach' );
    $wp_customize->get_section( 'header_image' )->priority                 = 10;
    $wp_customize->get_control( 'header_image' )->active_callback          = 'blossom_coach_banner_ac';
    $wp_customize->get_control( 'header_video' )->active_callback          = 'blossom_coach_banner_ac';
    $wp_customize->get_control( 'external_header_video' )->active_callback = 'blossom_coach_banner_ac';
    $wp_customize->get_section( 'header_image' )->description              = '';                                               
    $wp_customize->get_setting( 'header_image' )->transport                = 'refresh';
    $wp_customize->get_setting( 'header_video' )->transport                = 'refresh';
    $wp_customize->get_setting( 'external_header_video' )->transport       = 'refresh';
    
    /** Banner Options */
    $wp_customize->add_setting(
		'ed_banner_section',
		array(
			'default'			=> 'slider_banner',
			'sanitize_callback' => 'blossom_coach_sanitize_select'
		)
	);

	$wp_customize->add_control(
		new Blossom_Coach_Select_Control(
    		$wp_customize,
    		'ed_banner_section',
    		array(
                'label'	      => __( 'Banner Options', 'blossom-coach' ),
                'description' => __( 'Choose banner as static image/video or as a slider.', 'blossom-coach' ),
    			'section'     => 'header_image',
    			'choices'     => array(
                    'no_banner'        => __( 'Disable Banner Section', 'blossom-coach' ),
                    'static_nl_banner' => __( 'Static/Video Newsletter Banner', 'blossom-coach' ),
                    'slider_banner'    => __( 'Banner as Slider', 'blossom-coach' ),
                ),
                'priority' => 5	
     		)            
		)
	);
    
    if( blossom_coach_is_btnw_activated() ){
        /** Banner Newsletter */
        $wp_customize->add_setting(
            'banner_newsletter',
            array(
                'default'           => '',
                'sanitize_callback' => 'wp_kses_post',
            )
        );
        
        $wp_customize->add_control(
            'banner_newsletter',
            array(
                'label'           => __( 'Banner Newsletter', 'blossom-coach' ),
                'description'     => __( 'Enter the BlossomThemes Email Newsletters Shortcode. Ex. [BTEN id="356"]', 'blossom-coach' ),
                'section'         => 'header_image',
                'type'            => 'text',
                'active_callback' => 'blossom_coach_banner_ac'
            )
        );
    }else{
        $wp_customize->add_setting(
    		'banner_nl_note',
    		array(
    			'sanitize_callback' => 'wp_kses_post'
    		)
    	);
    
    	$wp_customize->add_control(
    		new Blossom_Coach_Note_Control( 
    			$wp_customize,
    			'banner_nl_note',
    			array(
    				'section'     => 'header_image', 
                    'label'       => __( 'Banner Newsletter', 'blossom-coach' ),   				
                    'description' => sprintf( __( 'Please install and activate the recommended plugin %1$sBlossomThemes Email Newsletter%2$s.', 'blossom-coach' ), '<a href="' . admin_url( 'themes.php?page=tgmpa-install-plugins' ) . '" target="_blank">', '</a>' ),
    			)
    		)
       );
    }
    
    /** Slider Content Style */
    $wp_customize->add_setting(
		'slider_type',
		array(
			'default'			=> 'latest_posts',
			'sanitize_callback' => 'blossom_coach_sanitize_select'
		)
	);

	$wp_customize->add_control(
		new Blossom_Coach_Select_Control(
    		$wp_customize,
    		'slider_type',
    		array(
                'label'	  => __( 'Slider Content Style', 'blossom-coach' ),
    			'section' => 'header_image',
    			'choices' => array(
                    'latest_posts' => __( 'Latest Posts', 'blossom-coach' ),
                    'cat'          => __( 'Category', 'blossom-coach' )
                ),
                'active_callback' => 'blossom_coach_banner_ac'	
     		)
		)
	);
    
    /** Slider Category */
    $wp_customize->add_setting(
		'slider_cat',
		array(
			'default'			=> '',
			'sanitize_callback' => 'blossom_coach_sanitize_select'
		)
	);

	$wp_customize->add_control(
		new Blossom_Coach_Select_Control(
    		$wp_customize,
    		'slider_cat',
    		array(
                'label'	          => __( 'Slider Category', 'blossom-coach' ),
    			'section'         => 'header_image',
    			'choices'         => blossom_coach_get_categories(),
                'active_callback' => 'blossom_coach_banner_ac'	
     		)
		)
	);
    
    /** No. of slides */
    $wp_customize->add_setting(
        'no_of_slides',
        array(
            'default'           => 3,
            'sanitize_callback' => 'blossom_coach_sanitize_number_absint'
        )
    );
    
    $wp_customize->add_control(
        new Blossom_Coach_Slider_Control( 
            $wp_customize,
            'no_of_slides',
            array(
                'section'     => 'header_image',
                'label'       => __( 'Number of Slides', 'blossom-coach' ),
                'description' => __( 'Choose the number of slides you want to display', 'blossom-coach' ),
                'choices'     => array(
                    'min'   => 1,
                    'max'   => 20,
                    'step'  => 1,
                ),
                'active_callback' => 'blossom_coach_banner_ac'                 
            )
        )
    );
    
    /** Slider Animation */
    $wp_customize->add_setting(
		'slider_animation',
		array(
			'default'			=> '',
			'sanitize_callback' => 'blossom_coach_sanitize_select'
		)
	);

	$wp_customize->add_control(
		new Blossom_Coach_Select_Control(
    		$wp_customize,
    		'slider_animation',
    		array(
                'label'	      => __( 'Slider Animation', 'blossom-coach' ),
                'section'     => 'header_image',
    			'choices'     => array(
                    'fadeOut'        => __( 'Fade Out', 'blossom-coach' ),
                    'fadeOutLeft'    => __( 'Fade Out Left', 'blossom-coach' ),
                    'fadeOutRight'   => __( 'Fade Out Right', 'blossom-coach' ),
                    'fadeOutUp'      => __( 'Fade Out Up', 'blossom-coach' ),
                    'fadeOutDown'    => __( 'Fade Out Down', 'blossom-coach' ),
                    ''               => __( 'Slide', 'blossom-coach' ),
                    'slideOutLeft'   => __( 'Slide Out Left', 'blossom-coach' ),
                    'slideOutRight'  => __( 'Slide Out Right', 'blossom-coach' ),
                    'slideOutUp'     => __( 'Slide Out Up', 'blossom-coach' ),
                    'slideOutDown'   => __( 'Slide Out Down', 'blossom-coach' ),                    
                ),
                'active_callback' => 'blossom_coach_banner_ac'                                	
     		)
		)
	);
    /** Slider Settings Ends */
    
    /** Blog Section */
    $wp_customize->add_section(
        'blog_section',
        array(
            'title'    => __( 'Blog Section', 'blossom-coach' ),
            'priority' => 40,
            'panel'    => 'frontpage_settings',
        )
    );

    /** Enable Blog Section */
    $wp_customize->add_setting(
        'ed_blog_section',
        array(
            'default'           => true,
            'sanitize_callback' => 'blossom_coach_sanitize_checkbox'
        )
    );

    $wp_customize->add_control(
        new Blossom_Coach_Toggle_Control(
            $wp_customize,
            'ed_blog_section',
            array(
                'label'       => __( 'Enable Blog Section', 'blossom-coach' ),
                'description' => __( 'Enable to show blog section.', 'blossom-coach' ),
                'section'     => 'blog_section',
            )            
        )
    );

    /** Blog title */
    $wp_customize->add_setting(
        'blog_section_title',
        array(
            'default'           => __( 'Latest Articles', 'blossom-coach' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'blog_section_title',
        array(
            'section' => 'blog_section',
            'label'   => __( 'Blog Title', 'blossom-coach' ),
            'type'    => 'text',
        )
    );

    /** Selective refresh for blog title. */
    $wp_customize->selective_refresh->add_partial( 'blog_section_title', array(
        'selector'        => '.blog-section .section-title',
        'render_callback' => 'blossom_coach_get_blog_title'
    ) );
    
    /** Read More Label */
    $wp_customize->add_setting(
        'blog_readmore',
        array(
            'default'           => __( 'Read More', 'blossom-coach' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'blog_readmore',
        array(
            'label'           => __( 'Read More Label', 'blossom-coach' ),
            'section'         => 'blog_section',
            'type'            => 'text',
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'blog_readmore', array(
        'selector'        => '.blog-section .article-wrap .post .btn-link',
        'render_callback' => 'blossom_coach_get_blog_section_read_more',
    ) ); 
    
    /** View All Label */
    $wp_customize->add_setting(
        'blog_view_all',
        array(
            'default'           => __( 'See More Posts', 'blossom-coach' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'blog_view_all',
        array(
            'label'           => __( 'View All Label', 'blossom-coach' ),
            'section'         => 'blog_section',
            'type'            => 'text',
            'active_callback' => 'blossom_coach_get_blog_section_view_all_btn'
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'blog_view_all', array(
        'selector'        => '.blog-section .wrapper .btn-readmore',
        'render_callback' => 'blossom_coach_get_blog_view_all_btn',
    ) ); 
    /** Blog Section Ends */    

    /** Wheel of life section */
    $wp_customize->add_section(
        'wheel_of_life',
        array(
            'title'    => __( 'Wheel of Life Section', 'blossom-coach' ),
            'priority' => 38,
            'panel'    => 'frontpage_settings',
        )
    );

    if( blossom_coach_is_wheel_of_life_activated() ){

        $wp_customize->add_setting(
            'ed_wol_section',
            array(
                'default'           => false,
                'sanitize_callback' => 'blossom_coach_sanitize_checkbox'
            )
        );
    
        $wp_customize->add_control(
            new Blossom_Coach_Toggle_Control(
                $wp_customize,
                'ed_wol_section',
                array(
                    'label'       => __( 'Enable Wheel of Life Section', 'blossom-coach' ),
                    'section'     => 'wheel_of_life',
                )            
            )
        );

        /** Note */
        $wp_customize->add_setting(
            'wheeloflife_text',
            array(
                'default'           => '',
                'sanitize_callback' => 'wp_kses_post' 
            )
        );
        
        $wp_customize->add_control(
            new Blossom_Coach_Note_Control( 
                $wp_customize,
                'wheeloflife_text',
                array(
                    'section'           => 'wheel_of_life',
                    'description'       => sprintf( __( 'Refer to this %1$sdocumentation%2$s to configure the plugin.', 'blossom-coach' ), '<a href="https://kraftplugins.com/wheel-of-life/docs/" target="_blank">', '</a>' ),
                    'active_callback'   => 'blossom_coach_wheeloflife_ac'
                )
            )
        );
        
        $wp_customize->add_setting(
            'wol_section_title',
            array(
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage'
            )
        );
        
        $wp_customize->add_control(
            'wol_section_title',
            array(
                'label'             => __( 'Section Title', 'blossom-coach' ),
                'section'           => 'wheel_of_life',
                'type'              => 'text',
                'active_callback'   => 'blossom_coach_wheeloflife_ac'
            )
        );
        
        $wp_customize->selective_refresh->add_partial( 'wol_section_title', array(
            'selector'        => '.wheeloflife-section h2.section-title',
            'render_callback' => 'blossom_coach_pro_get_wol_section_title',
        ) );

        $wp_customize->add_setting(
            'wol_section_content',
            array(
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage'
            )
        );
        
        $wp_customize->add_control(
            'wol_section_content',
            array(
                'label'             => __( 'Section Content', 'blossom-coach' ),
                'section'           => 'wheel_of_life',
                'type'              => 'text',
                'active_callback'   => 'blossom_coach_wheeloflife_ac'
            )
        );

        $wp_customize->selective_refresh->add_partial( 'wol_section_content', array(
            'selector'        => '.wheeloflife-section .section-content p',
            'render_callback' => 'blossom_coach_pro_get_wol_section_content',
        ) );

        /** Image */
        $wp_customize->add_setting(
            'wheeloflife_img',
            array(
                'default'           => get_template_directory_uri() . '/images/chart.png',
                'sanitize_callback' => 'blossom_coach_sanitize_image',
            )
        );
        
        $wp_customize->add_control(
            new WP_Customize_Image_Control(
                $wp_customize,
                'wheeloflife_img',
                array(
                    'label'             => __( 'Wheel of Life Image', 'blossom-coach' ),
                    'section'           => 'wheel_of_life',
                    'active_callback'   => 'blossom_coach_wheeloflife_ac'
                )
            )
        );

        $wp_customize->add_setting(
			'wheeloflife_shortcode',
			array(
				'default'            => '',
				'sanitize_callback'  => 'wp_kses_post',
			)
		);

		$wp_customize->add_control(
			'wheeloflife_shortcode',
			array(
				'label'         => __('Wheel of Life shortcode', 'blossom-coach'),
				'description'   => __('Enter the Wheel of Life shortcode. Ex. [wheeloflife id=1456]', 'blossom-coach'),
				'section'       => 'wheel_of_life',
				'type'          => 'text',
                'active_callback' => 'blossom_coach_wheeloflife_ac'
			)
		);

        $wp_customize->add_setting(
            'wheeloflife_learn_text',
            array(
                'default'           => '',
                'sanitize_callback' => 'wp_kses_post' 
            )
        );
        
        $wp_customize->add_control(
            new Blossom_Coach_Note_Control( 
                $wp_customize,
                'wheeloflife_learn_text',
                array(
                    'section'     => 'wheel_of_life',
                    'description' => sprintf( __( 'Refer to this %1$sdocumentation%2$s to learn how to use the shortcode.', 'blossom-coach' ), '<a href="https://kraftplugins.com/wheel-of-life/docs/how-to-display-embed-wheel-of-life-assessments/" target="_blank">', '</a>' ),
                    'active_callback' => 'blossom_coach_wheeloflife_ac'
                )
            )
        );

        $wp_customize->add_setting( 
            'wheeloflife_color', 
            array(
                'default'           => '#e5f3f3',
                'sanitize_callback' => 'sanitize_hex_color',
            ) 
        );
    
        $wp_customize->add_control( 
            new WP_Customize_Color_Control( 
                $wp_customize, 
                'wheeloflife_color', 
                array(
                    'label'       => __( 'Section color', 'blossom-coach' ),
                    'section'     => 'wheel_of_life',
                    'active_callback' => 'blossom_coach_wheeloflife_ac'
                )
            )
        );

    }else{

        $wp_customize->add_setting(
    		'wol_activate_note',
    		array(
    			'sanitize_callback' => 'wp_kses_post'
    		)
    	);
    
    	$wp_customize->add_control(
    		new Blossom_Coach_Note_Control( 
    			$wp_customize,
    			'wol_activate_note',
    			array(
    				'section'     => 'wheel_of_life', 
                    'label'       => __( 'Wheel of Life', 'blossom-coach' ),   				
                    'description' => sprintf( __( 'Please install and activate the recommended plugin %1$sWheel of Life%2$s. After that option related with this section will be visible.', 'blossom-coach' ), '<a href="' . admin_url( 'themes.php?page=tgmpa-install-plugins' ) . '" target="_blank">', '</a>' ),
    			)
    		)
       ); 
    }
    /** Wheel of life section ends */
}
add_action( 'customize_register', 'blossom_coach_customize_register_frontpage' );