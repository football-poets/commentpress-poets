<?php
/**
 * Single Poet template.
 *
 * @package CommentPress_Poets
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

?>
<!-- single-poet.php -->
<div id="wrapper">

	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>

			<div id="main_wrapper" class="clearfix">
				<div id="page_wrapper">
					<?php commentpress_get_feature_image(); ?>

					<?php

					// First try to locate using WP method.
					$cp_page_navigation = apply_filters(
						'cp_template_page_navigation',
						locate_template( 'assets/templates/page_navigation.php' )
					);

					// Load featured image if we find it.
					if ( ! commentpress_has_feature_image() ) {
						if ( $cp_page_navigation != '' ) {
							load_template( $cp_page_navigation, false );
						}
					}

					?>

					<div id="content" class="workflow-wrapper">
						<div class="<?php echo join( ' ', get_post_class( 'post' ) ); ?><?php echo commentpress_get_post_css_override( get_the_ID() ); ?>" id="post-<?php the_ID(); ?>">

							<?php if ( ! commentpress_has_feature_image() ) : ?>
								<h2 class="post_title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<?php endif; ?>

							<?php do_action( 'poets_connections_claim_form' ); ?>

							<?php the_content( '' ); ?>

						</div><!-- /post -->

						<?php $poems_by_poet = locate_template( 'assets/includes/poems-by-poet.php' ); ?>
						<?php if ( $poems_by_poet ) : ?>
							<?php load_template( $poems_by_poet ); ?>
						<?php endif; ?>

					</div><!-- /content -->

					<div class="page_nav_lower">
						<?php if ( $cp_page_navigation != '' ) : ?>
							<?php load_template( $cp_page_navigation, false ); ?>
						<?php endif; ?>
					</div><!-- /page_nav_lower -->

				</div><!-- /page_wrapper -->

			</div><!-- /main_wrapper -->

		<?php endwhile; ?>

	<?php else : ?>

		<div id="main_wrapper" class="clearfix">

			<div id="page_wrapper">

				<div id="content">

					<div class="post">

						<h2 class="post_title"><?php esc_html_e( 'Poet Not Found', 'commentpress-poets' ); ?></h2>

						<p><?php esc_html_e( 'Sorry, no poets matched your criteria.', 'commentpress-poets' ); ?></p>

						<?php get_search_form(); ?>

					</div><!-- /post -->

				</div><!-- /content -->

			</div><!-- /page_wrapper -->

		</div><!-- /main_wrapper -->

	<?php endif; ?>

</div><!-- /wrapper -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
