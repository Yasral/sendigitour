<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Blossom_Coach
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php
		while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content', 'single' );

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
        
        <?php
        /**
         * @hooked blossom_coach_author           - 15
         * @hooked blossom_coach_newsletter_block - 20
         * @hooked blossom_coach_navigation       - 25
         * @hooked blossom_coach_related_posts    - 30
         * @hooked blossom_coach_comment          - 35
        */
        do_action( 'blossom_coach_after_post_content' );
        ?>
        
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
