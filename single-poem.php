<?php
/**
 * Single Poet template.
 *
 * @package CommentPress_Poets
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Get core plugin reference.
$core = commentpress_core();

get_header();

?>
<!-- single-poem.php -->
<div id="wrapper">

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

								<div class="search_meta">
									<?php $poem_author = locate_template( 'assets/includes/poem-author.php' ); ?>
									<?php if ( $poem_author ) : ?>
										<?php load_template( $poem_author ); ?>
									<?php endif; ?>

									<p><a href="<?php the_permalink(); ?>"><?php echo esc_html( get_the_date( __( 'l, F jS, Y', 'commentpress-poets' ) ) ); ?></a></p>

								</div>
							<?php endif; ?>

							<?php commentpress_get_post_version_info( $post ); ?>

							<?php the_content(); ?>

							<?php commentpress_poets_poem_meta(); ?>

							<?php echo commentpress_multipager(); ?>

							<?php the_tags( '<div class="entry-meta"><p class="postmetadata">' . __( 'Tags: ', 'commentpress-poets' ), '<span class="tag-divider">,</span> ', '</p></div>' ); ?>

							<?php if ( ! empty( get_the_category_list( ', ' ) ) ) : ?>
								<div class="entry-category-meta clearfix">
									<p class="category-meta"><?php esc_html_e( 'Categories:', 'commentpress-poets' ); ?></p>
									<?php echo get_the_category_list( ', ' ); ?>
								</div>
							<?php endif; ?>

						</div><!-- /post -->
					</div><!-- /content -->

					<div class="page_nav_lower">
						<?php commentpress_page_navigation_template(); ?>
					</div><!-- /page_nav_lower -->

				</div><!-- /page_wrapper -->
			</div><!-- /main_wrapper -->

		<?php endwhile; ?>

	<?php else : ?>

		<div id="main_wrapper" class="clearfix">
			<div id="page_wrapper">
				<div id="content">
					<div class="post">

						<h2 class="post_title"><?php esc_html_e( 'Poem Not Found', 'commentpress-poets' ); ?></h2>
						<p><?php esc_html_e( 'Sorry, no poems matched your criteria.', 'commentpress-poets' ); ?></p>
						<?php get_search_form(); ?>

					</div><!-- /post -->
				</div><!-- /content -->
			</div><!-- /page_wrapper -->
		</div><!-- /main_wrapper -->

	<?php endif; ?>

</div><!-- /wrapper -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
