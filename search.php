<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package GeneratePress
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header(); ?>

	<section id="primary" <?php generate_content_class(); ?>>
		<main id="main" <?php generate_main_class(); ?>>
		<?php
		do_action( 'generate_before_main_content' );
		if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'generatepress' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
			</header><!-- .page-header -->

			<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post();

				get_template_part( 'content', 'search' );

			endwhile;

			generate_content_nav( 'nav-below' );

		else :

			get_template_part( 'no-results', 'search' );

		endif;

		do_action( 'generate_after_main_content' ); ?>
		</main><!-- #main -->
	</section><!-- #primary -->

<?php
do_action( 'generate_sidebars' );
get_footer();
