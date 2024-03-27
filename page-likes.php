<?php
/**
 * Template Name: Likes
 *
 * The template for displaying the list of most liked Poems.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @since 1.4
 * @package CommentPress_Poets
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

?>
<!-- page-likes.php -->
<div id="wrapper" class="page-likes">

	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : ?>

			<?php the_post(); ?>

			<div id="main_wrapper" class="clearfix">
				<div id="page_wrapper">

					<?php commentpress_get_feature_image(); ?>

					<?php if ( ! commentpress_has_feature_image() ) : ?>
						<?php commentpress_page_navigation_template(); ?>
					<?php endif; ?>

					<div id="content" class="content-wrapper">
						<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>">

							<?php if ( ! commentpress_has_feature_image() ) : ?>
								<h2 class="post_title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<?php endif; ?>

							<?php the_content(); ?>

						</div><!-- /post -->

						<?php $poems_by_likes = locate_template( 'assets/includes/poems-by-likes.php' ); ?>
						<?php if ( $poems_by_likes ) : ?>
							<?php load_template( $poems_by_likes ); ?>
						<?php endif; ?>

					</div><!-- /content -->

					<div class="page_nav_lower">
						<?php commentpress_page_navigation_template(); ?>
					</div><!-- /page_nav_lower -->

				</div><!-- /page_wrapper -->
			</div><!-- /main_wrapper -->

		<?php endwhile; ?>

	<?php else : ?>

		<div id="main_wrapper" class="clearfix">
			<div id="page_wrapper" class="page_wrapper">
				<div id="content" class="content">
					<div class="post">

						<h2 class="post_title"><?php esc_html_e( 'Page Not Found', 'commentpress-poets' ); ?></h2>
						<p><?php esc_html_e( 'Sorry, but you are looking for something that isn’t here.', 'commentpress-poets' ); ?></p>
						<?php get_search_form(); ?>

					</div><!-- /post -->
				</div><!-- /content -->
			</div><!-- /page_wrapper -->
		</div><!-- /main_wrapper -->

	<?php endif; ?>

</div><!-- /wrapper -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
