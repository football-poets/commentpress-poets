<?php
/**
 * Front Page template.
 *
 * @package CommentPress_Poets
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

?>
<!-- front-page.php -->
<div id="wrapper">

	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : ?>

			<?php the_post(); ?>

			<div id="main_wrapper" class="clearfix">

				<div id="page_wrapper" class="page_wrapper front_page">

					<?php commentpress_get_feature_image(); ?>

					<div id="content" class="content workflow-wrapper">
						<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>">

							<?php if ( ! commentpress_has_feature_image() ) : ?>
								<h2 class="post_title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<?php endif; ?>

							<?php the_content(); ?>

						</div><!-- /post -->
					</div><!-- /content -->

				</div><!-- /page_wrapper -->

				<div class="cp-homepage-widgets clear">

					<div class="cp-homepage-left">
						<?php if ( ! dynamic_sidebar( 'cp-homepage-left' ) ) : ?>
						<?php endif; ?>
					</div>

					<div class="cp-homepage-right">
						<?php if ( ! dynamic_sidebar( 'cp-homepage-right' ) ) : ?>
						<?php endif; ?>
					</div>

					<div class="cp-homepage-below">
						<?php if ( ! dynamic_sidebar( 'cp-homepage-below' ) ) : ?>
						<?php endif; ?>
					</div>

				</div><!-- /cp-homepage-widgets -->

			</div><!-- /main_wrapper -->

		<?php endwhile; ?>

	<?php else : ?>

		<div id="main_wrapper" class="clearfix">
			<div id="page_wrapper" class="page_wrapper">
				<div id="content" class="content">
					<div class="post">

						<h2 class="post_title"><?php esc_html_e( 'Page Not Found', 'commentpress-poets' ); ?></h2>
						<p><?php esc_html_e( "Sorry, but you are looking for something that isn't here.", 'commentpress-poets' ); ?></p>
						<?php get_search_form(); ?>

					</div><!-- /post -->
				</div><!-- /content -->
			</div><!-- /page_wrapper -->
		</div><!-- /main_wrapper -->

	<?php endif; ?>

</div><!-- /wrapper -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
