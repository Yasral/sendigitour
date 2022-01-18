<?php
/**
 * Shop Section
 * 
 * @package Blossom_Coach_Pro
 */
 
$section_title   = get_theme_mod( 'shop_section_title', __( 'Shop', 'blossom-consulting' ) );
$section_content = get_theme_mod( 'shop_section_content', __( 'Show your latest blog posts here. You can modify this section from Appearance > Customize > Front Page Settings > Shop Section.', 'blossom-consulting' ) );
$product_one     = get_theme_mod( 'product_one' );
$product_two     = get_theme_mod( 'product_two' );
$product_three   = get_theme_mod( 'product_three' );
$product_four    = get_theme_mod( 'product_four' );

$products = array( $product_one, $product_two, $product_three, $product_four );
$products = array_diff( array_unique( $products ), array('') );

$args = array(
    'post_type'      => 'product',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'post__in'       => $products,
    'orderby'        => 'post__in'
);

$qry = new WP_Query( $args );

if( blossom_coach_is_woocommerce_activated() && ( $section_title || $section_content || ( $products && $qry->have_posts() ) ) ){ ?>
<section id="shop_section" class="shop-section">
	<div class="wrapper">
		<?php 
            if( $section_title ) echo '<h2 class="section-title"><span>' . esc_html( $section_title ) . '</span></h2>';
            if( $section_content ) echo '<div class="section-content">' . wpautop( wp_kses_post( $section_content ) ) . '</div>';
            
            if( $products && $qry->have_posts() ){ ?> 
                <div class="shop-wrap">
                <?php
                    while( $qry->have_posts() ){
                        $qry->the_post(); ?>
                        <div class="item">
                        <?php
                            $stock = get_post_meta( get_the_ID(), '_stock_status', true );
                            
                            if( $stock == 'outofstock' ){
                                echo '<span class="outofstock">' . esc_html__( 'Sold Out', 'blossom-consulting' ) . '</span>';
                            }else{
                                woocommerce_show_product_sale_flash();    
                            }
                            ?>                            
                            <div class="product-image">
                                <a href="<?php the_permalink(); ?>" rel="bookmark">
                                    <?php 
                                    if( has_post_thumbnail() ){
                                        the_post_thumbnail( 'blossom-coach-shop', array( 'itemprop' => 'image' ) );    
                                    }else{ 
                                        blossom_coach_get_fallback_svg( 'blossom-coach-shop' ); 
                                    }
                                    ?>
                                </a>                                
                            </div>                            
                            <?php
                            woocommerce_template_single_rating(); //rating    
                            the_title( '<h3><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );                             
                            woocommerce_template_single_price(); //price                                                        
                        ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
            wp_reset_postdata();
        ?>
	</div><!-- .wrapper -->
</section> <!-- .shop-section -->
<?php
}