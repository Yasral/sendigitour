<?php
/**
 * Banner Section
 * 
 * @package Blossom_Coach
 */

$ed_banner         = get_theme_mod( 'ed_consulting_banner_section', 'slider_banner' );
$slider_type       = get_theme_mod( 'slider_type', 'latest_posts' ); 
$slider_cat        = get_theme_mod( 'slider_cat' );
$posts_per_page    = get_theme_mod( 'no_of_slides', 3 );
$banner_newsletter = get_theme_mod( 'banner_newsletter' );
$banner_title      = get_theme_mod( 'banner_title', __( 'The Secrets to Successful Team Leadership', 'blossom-consulting' ) );
$banner_subtitle   = get_theme_mod( 'banner_subtitle', __( 'Sally is a solution focused therapist offering brief and often single session therapy. She has built a reputation for engaging workshops on Solution Focused Brief Therapy and Single Session Therapy, and have recently started to put her courses online.', 'blossom-consulting' ) );
$banner_label      = get_theme_mod( 'banner_label', __( 'Discover More', 'blossom-consulting' ) );
$banner_link       = get_theme_mod( 'banner_link', '#' );
    
if( ( $ed_banner == 'static_banner' || $ed_banner == 'static_nl_banner' ) && has_custom_header() ){ ?>
    <div id="banner_section" class="site-banner<?php if( has_header_video() ) echo esc_attr( ' video-banner' ); ?>">
        <?php 
            the_custom_header_markup();            
            if( $ed_banner == 'static_banner' && ( $banner_title || $banner_subtitle || ( $banner_label && $banner_link ) ) ){
                echo '<div class="banner-caption"><div class="wrapper"><div class="banner-wrap">';
                if( $banner_title ) echo '<h2 class="banner-title">' . esc_html( $banner_title ) . '</h2>';
                if( $banner_subtitle ) echo '<div class="banner-content b-content">' . wpautop( wp_kses_post( $banner_subtitle ) ) . '</div>';
                if( $banner_label && $banner_link ) echo '<a href="' . esc_url( $banner_link ) . '" class="banner-link">' . esc_html( $banner_label ) . '</a>';
                echo '</div></div></div>';
            }elseif( blossom_coach_is_btnw_activated() && $banner_newsletter && has_shortcode( $banner_newsletter, 'BTEN' ) ){
                echo '<div class="banner-caption"><div class="wrapper">';
                echo do_shortcode( wp_kses_post( $banner_newsletter ) );
                echo '</div></div>';
            }
        ?>
    </div>
<?php
}elseif( $ed_banner == 'slider_banner' ){
    $args = array(
        'post_type'           => 'post',
        'post_status'         => 'publish',            
        'ignore_sticky_posts' => true
    );
    
    if( $slider_type === 'cat' && $slider_cat ){
        $args['cat']            = $slider_cat; 
        $args['posts_per_page'] = -1;  
    }else{
        $args['posts_per_page'] = $posts_per_page;
    }
        
    $qry = new WP_Query( $args );
    
    if( $qry->have_posts() ){ ?>
    <div id="banner_section" class="site-banner">
		<div id="banner-slider" class="owl-carousel">
			<?php while( $qry->have_posts() ){ $qry->the_post(); ?>
            <div class="item">
				<?php 
                if( has_post_thumbnail() ){
				    the_post_thumbnail( 'blossom-coach-slider', array( 'itemprop' => 'image' ) );    
				}else{ 
                    blossom_coach_get_fallback_svg( 'blossom-coach-slider' ); 
                }
                ?>                        
				<div class="banner-text">
					<div class="container">
						<div class="text-holder">
							<?php
                                blossom_coach_category();
                                the_title( '<h2 class="title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
                            ?>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>
            
		</div>
	</div>
    <?php
    }
    wp_reset_postdata();
}