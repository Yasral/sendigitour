<?php
/**
 * Blossom Coach Custom functions and definitions
 *
 * @package Blossom_Coach
 */

if ( ! function_exists( 'blossom_coach_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function blossom_coach_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Blossom Coach, use a find and replace
	 * to change 'blossom-coach' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'blossom-coach', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'blossom-coach' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
        'comment-form',
        'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'blossom_coach_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support( 'custom-logo', array( 
        'height'      => 70,
        'width'       => 70,
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => array( 'site-title', 'site-description' 
    ) ) );
    
    /**
     * Add support for custom header.
    */
    add_theme_support( 'custom-header', apply_filters( 'blossom_coach_custom_header_args', array(
		'default-image'  => esc_url( get_template_directory_uri() . '/images/banner-img.jpg' ),
        'video'          => true,
		'width'          => 1920, 
		'height'         => 700, 
		'header-text'    => false, 
	) ) );
    
    register_default_headers( array(
		'default-image' => array(
			'url'           => '%s/images/banner-img.jpg',
			'thumbnail_url' => '%s/images/banner-img.jpg',
			'description'   => __( 'Default Header Image', 'blossom-coach' ),
		),
	) );
    
    /**
     * Add Custom Images sizes.
    */    
    add_image_size( 'blossom-coach-schema', 600, 60 );
    add_image_size( 'blossom-coach-slider', 1920, 700, true );
    add_image_size( 'blossom-coach-fullwidth', 1170, 578, true );
    add_image_size( 'blossom-coach-with-sidebar', 810, 500, true );
    add_image_size( 'blossom-coach-latest', 540, 400, true );
    
    /** Starter Content */
    $starter_content = array(
        // Specify the core-defined pages to create and add custom thumbnails to some of them.
		'posts' => array( 'home', 'blog' ),
		
        // Default to a static front page and assign the front and posts pages.
		'options' => array(
			'show_on_front' => 'page',
			'page_on_front' => '{{home}}',
			'page_for_posts' => '{{blog}}',
		),
        
        // Set up nav menus for each of the two areas registered in the theme.
		'nav_menus' => array(
			// Assign a menu to the "top" location.
			'primary' => array(
				'name' => __( 'Primary', 'blossom-coach' ),
				'items' => array(
					'page_home',
					'page_blog'
				)
			)
		),
    );
    
    $starter_content = apply_filters( 'travel_agency_starter_content', $starter_content );

	add_theme_support( 'starter-content', $starter_content );
    
    // Add theme support for Responsive Videos.
    add_theme_support( 'jetpack-responsive-videos' );

    // Add theme support for excerpt
    add_post_type_support( 'page', 'excerpt' );

    // Remove widget block.
    remove_theme_support( 'widgets-block-editor' );
}
endif;
add_action( 'after_setup_theme', 'blossom_coach_setup' );

if( ! function_exists( 'blossom_coach_content_width' ) ) :
/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function blossom_coach_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'blossom_coach_content_width', 810 );
}
endif;
add_action( 'after_setup_theme', 'blossom_coach_content_width', 0 );

if( ! function_exists( 'blossom_coach_template_redirect_content_width' ) ) :
/**
* Adjust content_width value according to template.
*
* @return void
*/
function blossom_coach_template_redirect_content_width(){
	$sidebar = blossom_coach_sidebar();
    if( $sidebar ){	   
        $GLOBALS['content_width'] = 810;       
	}else{
        if( is_singular() ){
            if( blossom_coach_sidebar( true ) === 'full-width centered' ){
                $GLOBALS['content_width'] = 810;
            }else{
                $GLOBALS['content_width'] = 1170;                
            } 
        }else{
            $GLOBALS['content_width'] = 1170;
        }
	}
}
endif;
add_action( 'template_redirect', 'blossom_coach_template_redirect_content_width' );

if( ! function_exists( 'blossom_coach_scripts' ) ) :
/**
 * Enqueue scripts and styles.
 */
function blossom_coach_scripts(){
	// Use minified libraries if SCRIPT_DEBUG is false
    $build  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '/build' : '';
    $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
    
    if( blossom_coach_is_woocommerce_activated() )
    wp_enqueue_style( 'blossom-coach-woocommerce', get_template_directory_uri(). '/css' . $build . '/woocommerce' . $suffix . '.css', array(), BLOSSOM_COACH_THEME_VERSION );
    
    wp_enqueue_style( 'owl-carousel', get_template_directory_uri(). '/css' . $build . '/owl.carousel' . $suffix . '.css', array(), '2.2.1' );
    wp_enqueue_style( 'animate', get_template_directory_uri(). '/css' . $build . '/animate' . $suffix . '.css', array(), '3.5.2' );
    wp_enqueue_style( 'blossom-coach-google-fonts', blossom_coach_fonts_url(), array(), null );
    wp_enqueue_style( 'blossom-coach', get_stylesheet_uri(), array(), BLOSSOM_COACH_THEME_VERSION );
    
    wp_enqueue_script( 'all', get_template_directory_uri() . '/js' . $build . '/all' . $suffix . '.js', array( 'jquery' ), '5.6.3', true );
    wp_enqueue_script( 'v4-shims', get_template_directory_uri() . '/js' . $build . '/v4-shims' . $suffix . '.js', array( 'jquery' ), '5.6.3', true );
    wp_enqueue_script( 'owl-carousel', get_template_directory_uri() . '/js' . $build . '/owl.carousel' . $suffix . '.js', array( 'jquery' ), '2.2.1', true );
    wp_enqueue_script( 'owlcarousel2-a11ylayer', get_template_directory_uri() . '/js' . $build . '/owlcarousel2-a11ylayer' . $suffix . '.js', array( 'jquery', 'owl-carousel' ), '0.2.1', true );
	wp_enqueue_script( 'blossom-coach', get_template_directory_uri() . '/js' . $build . '/custom' . $suffix . '.js', array( 'jquery', 'masonry' ), BLOSSOM_COACH_THEME_VERSION, true );
    wp_enqueue_script( 'blossom-coach-modal', get_template_directory_uri() . '/js' . $build . '/modal-accessibility' . $suffix . '.js', array( 'jquery' ), BLOSSOM_COACH_THEME_VERSION, true );
    
    $array = array( 
        'rtl'       => is_rtl(),
        'animation' => esc_attr( get_theme_mod( 'slider_animation' ) ),
    );
    
    wp_localize_script( 'blossom-coach', 'blossom_coach_data', $array );
    
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
endif;
add_action( 'wp_enqueue_scripts', 'blossom_coach_scripts' );

if( ! function_exists( 'blossom_coach_admin_scripts' ) ) :
/**
 * Enqueue admin scripts and styles.
*/
function blossom_coach_admin_scripts(){
    wp_enqueue_style( 'blossom-coach-admin', get_template_directory_uri() . '/inc/css/admin.css', '', BLOSSOM_COACH_THEME_VERSION );
}
endif; 
add_action( 'admin_enqueue_scripts', 'blossom_coach_admin_scripts' );

if( ! function_exists( 'blossom_coach_body_classes' ) ) :
/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function blossom_coach_body_classes( $classes ) {
    global $wp_query;
	$blog_layout = get_theme_mod( 'blog_page_layout', 'grid' );
    
    // Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

    if ( $wp_query->found_posts == 0 ) {
        $classes[] = 'no-post';
    }
    
    // Adds a class of custom-background-image to sites with a custom background image.
	if ( get_background_image() ) {
		$classes[] = 'custom-background-image';
	}
    
    // Adds a class of custom-background-color to sites with a custom background color.
    if ( get_background_color() != 'ffffff' ) {
		$classes[] = 'custom-background-color';
	}
    
    if( is_archive() || is_search() ){
        $classes[] = 'grid-view';
    }  
    
    if( is_home() ){
        switch( $blog_layout ){
            case 'grid':
            $classes[] = 'grid-view';
            break;
            case 'classic':
            $classes[] = 'largeimage-grid-view';
            break;
            case 'listing':
            $classes[] = 'list-view';
            break;
        }
    }
    
    if( is_singular() ){
        $classes[] = 'style1';
    }

    if( is_single() || is_page() ){
        $classes[] = 'underline';
    }  
    
    $classes[] = blossom_coach_sidebar( true );
    
	return $classes;
}
endif;
add_filter( 'body_class', 'blossom_coach_body_classes' );

if( ! function_exists( 'blossom_coach_post_classes' ) ) :
/**
 * Add custom classes to the array of post classes.
*/
function blossom_coach_post_classes( $classes ){
    $blog_layout = get_theme_mod( 'blog_page_layout', 'grid' );
    
    if( ! is_singular() ){
        if( $blog_layout == 'grid' )
        $classes[] = 'grid-sizer';
    }

    if( is_archive() || is_search() ){
        $classes[] = 'grid-sizer';    
    }
    
    return $classes;
}
endif;
add_filter( 'post_class', 'blossom_coach_post_classes' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function blossom_coach_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'blossom_coach_pingback_header' );

if( ! function_exists( 'blossom_coach_change_comment_form_default_fields' ) ) :
/**
 * Change Comment form default fields i.e. author, email & url.
*/
function blossom_coach_change_comment_form_default_fields( $fields ){    
    // get the current commenter if available
    $commenter = wp_get_current_commenter();
 
    // core functionality
    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );    
 
    // Change just the author field
    $fields['author'] = '<p class="comment-form-author"><label class="screen-reader-text">' . esc_html__( 'Full Name', 'blossom-coach' ) . '</label><input id="author" name="author" placeholder="' . esc_attr__( 'Name*', 'blossom-coach' ) . '" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>';
    
    $fields['email'] = '<p class="comment-form-email"><label class="screen-reader-text">' . esc_html__( 'Email', 'blossom-coach' ) . '</label><input id="email" name="email" placeholder="' . esc_attr__( 'Email*', 'blossom-coach' ) . '" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>';
    
    $fields['url'] = '<p class="comment-form-url"><label class="screen-reader-text">' . esc_html__( 'Website', 'blossom-coach' ) . '</label><input id="url" name="url" placeholder="' . esc_attr__( 'Website', 'blossom-coach' ) . '" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>'; 
    
    return $fields;    
}
endif;
add_filter( 'comment_form_default_fields', 'blossom_coach_change_comment_form_default_fields' );

if( ! function_exists( 'blossom_coach_change_comment_form_defaults' ) ) :
/**
 * Change Comment Form defaults
*/
function blossom_coach_change_comment_form_defaults( $defaults ){    
    $defaults['comment_field'] = '<p class="comment-form-comment"><label class="screen-reader-text">' . esc_html__( 'Comment', 'blossom-coach' ) . '</label><textarea id="comment" name="comment" placeholder="' . esc_attr__( 'Comment*', 'blossom-coach' ) . '" cols="45" rows="8" aria-required="true"></textarea></p>';
    $defaults['title_reply'] = esc_html__( 'Leave a Comment', 'blossom-coach' );
    return $defaults;    
}
endif;
add_filter( 'comment_form_defaults', 'blossom_coach_change_comment_form_defaults' );

if ( ! function_exists( 'blossom_coach_excerpt_more' ) ) :
/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... * 
 */
function blossom_coach_excerpt_more( $more ) {
	return is_admin() ? $more : ' &hellip; ';
}

endif;
add_filter( 'excerpt_more', 'blossom_coach_excerpt_more' );

if ( ! function_exists( 'blossom_coach_excerpt_length' ) ) :
/**
 * Changes the default 55 character in excerpt 
*/
function blossom_coach_excerpt_length( $length ) {
	$excerpt_length = get_theme_mod( 'excerpt_length', 55 );
    return is_admin() ? $length : absint( $excerpt_length );    
}
endif;
add_filter( 'excerpt_length', 'blossom_coach_excerpt_length', 999 );

if( ! function_exists( 'blossom_coach_get_the_archive_title' ) ) :
/**
 * Filter Archive Title
*/
function blossom_coach_get_the_archive_title( $title ){
    $ed_prefix = get_theme_mod( 'ed_prefix_archive', false );
    if( is_category() ){
        if( $ed_prefix ){
            $title = '<h1 class="page-title">' . single_cat_title( '', false ) . '</h1>';
        }else{
            /* translators: Category archive title. 1: Category name */
            $title = sprintf( __( '%1$sBrowsing Category%2$s %3$s', 'blossom-coach' ), '<p class="subtitle">', '</p>', '<h1 class="page-title">' . single_cat_title( '', false ) . '</h1>' );
        }
    }elseif( is_tag() ){
        if( $ed_prefix ){
            $title = '<h1 class="page-title">' . single_tag_title( '', false ) . '</h1>';    
        }else{
            /* translators: Tag archive title. 1: Tag name */
            $title = sprintf( __( '%1$sBrowsing Tag%2$s %3$s', 'blossom-coach' ), '<p class="subtitle">', '</p>', '<h1 class="page-title">' . single_tag_title( '', false ) . '</h1>' );
        }
    }elseif( is_year() ){
        if( $ed_prefix ){
            $title = '<h1 class="page-title">' . get_the_date( _x( 'Y', 'yearly archives date format', 'blossom-coach' ) ) . '</h1>';
        }else{
            /* translators: Yearly archive title. 1: Year */
            $title = sprintf( __( '%1$sBrowsing Year%2$s %3$s', 'blossom-coach' ), '<p class="subtitle">', '</p>', '<h1 class="page-title">' . get_the_date( _x( 'Y', 'yearly archives date format', 'blossom-coach' ) ) . '</h1>' );
        }
    }elseif( is_month() ){
        if( $ed_prefix ){
            $title = '<h1 class="page-title">' . get_the_date( _x( 'F Y', 'monthly archives date format', 'blossom-coach' ) ) . '</h1>';
        }else{
            /* translators: Monthly archive title. 1: Month name and year */
            $title = sprintf( __( '%1$sBrowsing Month%2$s %3$s', 'blossom-coach' ), '<p class="subtitle">', '</p>', '<h1 class="page-title">' . get_the_date( _x( 'F Y', 'monthly archives date format', 'blossom-coach' ) ) . '</h1>' );
        }
    }elseif( is_day() ){
        if( $ed_prefix ){
            $title = '<h1 class="page-title">' . get_the_date( _x( 'F j, Y', 'daily archives date format', 'blossom-coach' ) ) . '</h1>';
        }else{
            /* translators: Daily archive title. 1: Date */
            $title = sprintf( __( '%1$sBrowsing Day%2$s %3$s', 'blossom-coach' ), '<p class="subtitle">', '</p>', '<h1 class="page-title">' . get_the_date( _x( 'F j, Y', 'daily archives date format', 'blossom-coach' ) ) . '</h1>' );
        }
    }elseif( is_post_type_archive() ) {
        if( is_post_type_archive( 'product' ) ){
            $title = '<h1 class="page-title">' . get_the_title( get_option( 'woocommerce_shop_page_id' ) ) . '</h1>';
        }else{
            if( $ed_prefix ){
                $title = '<h1 class="page-title">' . post_type_archive_title( '', false ) . '</h1>';
            }else{
                /* translators: Post type archive title. 1: Post type name */
                $title = sprintf( __( '%1$sBrowsing Archives%2$s %3$s', 'blossom-coach' ), '<p class="subtitle">', '</p>', '<h1 class="page-title">' . post_type_archive_title( '', false ) . '</h1>' );
            }
        }
    }elseif( is_tax() ) {
        $tax = get_taxonomy( get_queried_object()->taxonomy );
        if( $ed_prefix ){
            $title = '<h1 class="page-title">' . single_term_title( '', false ) . '</h1>';
        }else{                                                            
            /* translators: Taxonomy term archive title. 1: Taxonomy singular name, 2: Current taxonomy term */
            $title = sprintf( __( '%1$s: %2$s', 'blossom-coach' ), '<span>' . $tax->labels->singular_name . '</span>', '<h1 class="page-title">' . single_term_title( '', false ) . '</h1>' );
        }
    }else {
        $title = sprintf( __( '%1$sArchives%2$s', 'blossom-coach' ), '<h1 class="page-title">', '</h1>' );
    }
    return $title;
}
endif;
add_filter( 'get_the_archive_title', 'blossom_coach_get_the_archive_title' );

if( ! function_exists( 'blossom_coach_remove_archive_description' ) ) :
/**
 * filter the_archive_description & get_the_archive_description to show post type archive
 * @param  string $description original description
 * @return string post type description if on post type archive
 */
function blossom_coach_remove_archive_description( $description ){
    $ed_shop_archive_description = get_theme_mod( 'ed_shop_archive_description', false );
    if( is_post_type_archive( 'product' ) ) {
        if( ! $ed_shop_archive_description ){
            $description = '';
        }
    }
    return $description;
}
endif;
add_filter( 'get_the_archive_description', 'blossom_coach_remove_archive_description' );


if( ! function_exists( 'blossom_coach_exclude_cat' ) ) :
/**
 * Exclude post with Category from blog and archive page. 
*/
function blossom_coach_exclude_cat( $query ){
    $ed_banner      = get_theme_mod( 'ed_banner_section', 'slider_banner' );
    $slider_type    = get_theme_mod( 'slider_type', 'latest_posts' ); 
    $slider_cat     = get_theme_mod( 'slider_cat' );
    $posts_per_page = get_theme_mod( 'no_of_slides', 3 );
    
    if( ! is_admin() && $query->is_main_query() ){
        //filtering posts when slider is enable in blog home page
        if( $query->is_home() && $query->is_front_page() && $ed_banner == 'slider_banner' ){
            if( $slider_type === 'cat' && $slider_cat  ){            
     			$query->set( 'category__not_in', array( $slider_cat ) );    		
            }elseif( $slider_type == 'latest_posts' ){
                $args = array(
                    'post_type'           => 'post',
                    'post_status'         => 'publish',
                    'posts_per_page'      => $posts_per_page,
                    'ignore_sticky_posts' => true
                );
                $latest = get_posts( $args );
                $excludes = array();
                foreach( $latest as $l ){
                    array_push( $excludes, $l->ID );
                }
                $query->set( 'post__not_in', $excludes );
            } 
        }    
    }    
}
endif;
add_filter( 'pre_get_posts', 'blossom_coach_exclude_cat' );

if( ! function_exists( 'blossom_coach_single_post_schema' ) ) :
/**
 * Single Post Schema
 *
 * @return string
 */
function blossom_coach_single_post_schema() {
    if ( is_singular( 'post' ) ) {
        global $post;
        $custom_logo_id = get_theme_mod( 'custom_logo' );

        $site_logo   = wp_get_attachment_image_src( $custom_logo_id , 'blossom-coach-schema' );
        $images      = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
        $excerpt     = blossom_coach_escape_text_tags( $post->post_excerpt );
        $content     = $excerpt === "" ? mb_substr( blossom_coach_escape_text_tags( $post->post_content ), 0, 110 ) : $excerpt;
        $schema_type = ! empty( $custom_logo_id ) && has_post_thumbnail( $post->ID ) ? "BlogPosting" : "Blog";

        $args = array(
            "@context"  => "http://schema.org",
            "@type"     => $schema_type,
            "mainEntityOfPage" => array(
                "@type" => "WebPage",
                "@id"   => get_permalink( $post->ID )
            ),
            "headline"  => get_the_title( $post->ID ),
            "datePublished" => get_the_time( DATE_ISO8601, $post->ID ),
            "dateModified"  => get_post_modified_time(  DATE_ISO8601, __return_false(), $post->ID ),
            "author"        => array(
                "@type"     => "Person",
                "name"      => blossom_coach_escape_text_tags( get_the_author_meta( 'display_name', $post->post_author ) )
            ),
            "description" => ( class_exists('WPSEO_Meta') ? WPSEO_Meta::get_value( 'metadesc' ) : $content )
        );

        if ( has_post_thumbnail( $post->ID ) ) :
            $args['image'] = array(
                "@type"  => "ImageObject",
                "url"    => $images[0],
                "width"  => $images[1],
                "height" => $images[2]
            );
        endif;

        if ( ! empty( $custom_logo_id ) ) :
            $args['publisher'] = array(
                "@type"       => "Organization",
                "name"        => get_bloginfo( 'name' ),
                "description" => get_bloginfo( 'description' ),
                "logo"        => array(
                    "@type"   => "ImageObject",
                    "url"     => $site_logo[0],
                    "width"   => $site_logo[1],
                    "height"  => $site_logo[2]
                )
            );
        endif;

        echo '<script type="application/ld+json">';
        if ( version_compare( PHP_VERSION, '5.4.0' , '>=' ) ) {
            echo wp_json_encode( $args, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT );
        } else {
            echo wp_json_encode( $args );
        }
        echo '</script>';
    }
}
endif;
add_action( 'wp_head', 'blossom_coach_single_post_schema' );

if( ! function_exists( 'blossom_coach_get_comment_author_link' ) ) :
/**
 * Filter to modify comment author link
 * @link https://developer.wordpress.org/reference/functions/get_comment_author_link/
 */
function blossom_coach_get_comment_author_link( $return, $author, $comment_ID ){
    $comment = get_comment( $comment_ID );
    $url     = get_comment_author_url( $comment );
    $author  = get_comment_author( $comment );
 
    if ( empty( $url ) || 'http://' == $url )
        $return = '<span itemprop="name">'. esc_html( $author ) .'</span>';
    else
        $return = '<span itemprop="name"><a href=' . esc_url( $url ) . ' rel="external nofollow" class="url" itemprop="url">' . esc_html( $author ) . '</a></span>';

    return $return;
}
endif;
add_filter( 'get_comment_author_link', 'blossom_coach_get_comment_author_link', 10, 3 );

if( ! function_exists( 'blossom_coach_search_form' ) ) :
/**
 * Search Form
*/
function blossom_coach_search_form(){ 
    $placeholder = is_404() ? _x( 'Try searching for what you were looking for&hellip;', 'placeholder', 'blossom-coach' ) : _x( 'Enter Keywords&hellip;', 'placeholder', 'blossom-coach' );
    $form = '<form role="search" method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '">
                <label class="screen-reader-text">' . esc_html__( 'Looking for Something?', 'blossom-coach' ) . '</label>
                <input type="search" class="search-field" placeholder="' . esc_attr( $placeholder ) . '" value="' . esc_attr( get_search_query() ) . '" name="s" />
                <label for="submit-field">
                    <span><i class="fa fa-search"></i></span>
                    <input type="submit" id="submit-field" class="search-submit" value="'. esc_attr_x( 'Search', 'submit button', 'blossom-coach' ) .'" />
                </label>
            </form>';
 
    return $form;
}
endif;
add_filter( 'get_search_form', 'blossom_coach_search_form' );

if( ! function_exists( 'blossom_coach_admin_notice' ) ) :
/**
 * Adding Getting Started Page in admin menu
 */
function blossom_coach_admin_notice() {
    global $pagenow;
    $theme_args      = wp_get_theme();
    $meta            = get_option( 'blossom-coach-update-notice' );
    $name            = $theme_args->__get( 'Name' );
    $current_screen  = get_current_screen();
    
    if ( is_admin() && 'themes.php' == $pagenow && !$meta ) {
        
        if( $current_screen->id !== 'dashboard' && $current_screen->id !== 'themes' ) {
            return;
        }

        if ( is_network_admin() ) {
            return;
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        } ?>

        <div class="welcome-message notice notice-info">
            <div class="notice-wrapper">
                <div class="notice-text">
                    <h3><?php esc_html_e( 'Congratulations!', 'blossom-coach' ); ?></h3>
                    <p><?php printf( __( '%1$s is now installed and ready to use. Click below to see theme documentation, plugins to install and other details to get started.', 'blossom-coach' ), esc_html( $name ) ) ; ?></p>
                    <p><a href="<?php echo esc_url( admin_url( 'themes.php?page=blossom-coach-getting-started' ) ); ?>" class="button button-primary" style="text-decoration: none;"><?php esc_html_e( 'Go to the getting started.', 'blossom-coach' ); ?></a></p>
                    <p class="dismiss-link"><strong><a href="?blossom-coach-update-notice=1"><?php esc_html_e( 'Dismiss','blossom-coach' ); ?></a></strong></p>
                </div>
            </div>
            <style>
                .notice-info .notice-text {
                   position: relative;
                }

                .notice-text p.dismiss-link {
                   position: absolute;
                   top: 0;
                   right: 0;
                   margin: 0;
                   padding: 0;
                }
            </style>
        </div>
    <?php }
}
endif;
add_action( 'admin_notices', 'blossom_coach_admin_notice' );

if( ! function_exists( 'blossom_coach_ignore_admin_notice' ) ) :
/**
 * ignore notice
 */
function blossom_coach_ignore_admin_notice() {

    if ( isset( $_GET['blossom-coach-update-notice'] ) && $_GET['blossom-coach-update-notice'] = '1' ) {

        update_option( 'blossom-coach-update-notice', true );
    }
}
endif;
add_action( 'admin_init', 'blossom_coach_ignore_admin_notice' );

if ( ! function_exists( 'blossom_coach_get_fontawesome_ajax' ) ) :
/**
 * Return an array of all icons.
 */
function blossom_coach_get_fontawesome_ajax() {
    // Bail if the nonce doesn't check out
    if ( ! isset( $_POST['blossom_coach_customize_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['blossom_coach_customize_nonce'] ), 'blossom_coach_customize_nonce' ) ) {
        wp_die();
    }

    // Do another nonce check
    check_ajax_referer( 'blossom_coach_customize_nonce', 'blossom_coach_customize_nonce' );

    // Bail if user can't edit theme options
    if ( ! current_user_can( 'edit_theme_options' ) ) {
        wp_die();
    }

    // Get all of our fonts
    $fonts = blossom_coach_get_fontawesome_list();
    
    ob_start();
    if( $fonts ){ ?>
        <ul class="font-group">
            <?php 
                foreach( $fonts as $font ){
                    echo '<li data-font="' . esc_attr( $font ) . '"><i class="' . esc_attr( $font ) . '"></i></li>';                        
                }
            ?>
        </ul>
        <?php
    }
    echo ob_get_clean();

    // Exit
    wp_die();
}
endif;
add_action( 'wp_ajax_blossom_coach_get_fontawesome_ajax', 'blossom_coach_get_fontawesome_ajax' );