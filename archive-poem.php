<?php
/**
 * Poem Archive template.
 *
 * @package CommentPress_Poets
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

?>
<!-- archive-poem.php -->
<div id="wrapper">
	<div id="main_wrapper" class="clearfix">
		<div id="page_wrapper">

			<?php commentpress_page_navigation_template(); ?>

			<div id="content" class="clearfix">
				<div class="post">

					<?php if ( have_posts() ) : ?>

						<h3 class="post_title"><?php esc_html_e( 'Poetry Archives', 'commentpress-poets' ); ?></h3>

						<?php global $paged; ?>
						<?php if ( isset( $paged ) && 1 === (int) $paged ) : ?>
							<p><?php esc_html_e( 'This archive contains every poem that has been published on Football Poets. They are listed ten-per-page in reverse chronological order so the most recent poems appear first. Click or tap the arrows in the corners of the page to navigate between pages. It\'s easier to use the search form below to find a specific poem.', 'commentpress-poets' ); ?></p>
						<?php endif; ?>

						<div class="poet-search">
							<h3><?php esc_html_e( 'Search Poems', 'commentpress-poets' ); ?></h3>
							<form role="search" action="<?php echo esc_url( get_post_type_archive_link( 'poem' ) ); ?>" method="get" id="searchform">
								<?php /* phpcs:ignore WordPress.Security.NonceVerification.Recommended */ ?>
								<input type="text" name="s" placeholder="<?php esc_attr_e( 'Search Poems', 'commentpress-poets' ); ?>" value="<?php echo esc_attr( isset( $_REQUEST['s'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ) : '' ); ?>" />
								<input type="hidden" name="post_type" value="poem" />
								<input type="submit" alt="<?php esc_attr_e( 'Search', 'commentpress-poets' ); ?>" value="<?php esc_attr_e( 'Search', 'commentpress-poets' ); ?>" />
							</form>
						</div>

						<?php

						// Access main query.
						global $wp_query;

						// Find connected poets for all poems.
						p2p_type( 'poets_to_poems' )->each_connected( $wp_query );

						?>

						<?php while ( have_posts() ) : ?>

							<?php the_post(); ?>

							<div class="<?php echo esc_attr( join( ' ', get_post_class( 'post' ) ) ); ?> search_result">

								<h3 id="post-<?php the_ID(); ?>" class="post_title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php esc_attr_e( 'Permanent Link to', 'commentpress-poets' ); ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>

								<div class="search_meta">
									<?php if ( ! empty( $post->connected ) ) : ?>
										<?php foreach ( $post->connected as $poet ) : ?>
											<cite class="fn"><a href="<?php echo esc_url( get_permalink( $poet->ID ) ); ?>"><?php echo esc_html( get_the_title( $poet->ID ) ); ?></a></cite>
										<?php endforeach; ?>
									<?php endif; ?>
									<p><a href="<?php the_permalink(); ?>"><?php echo esc_html( get_the_date( __( 'l, F jS, Y', 'commentpress-poets' ) ) ); ?></a></p>
								</div>

								<?php the_content(); ?>

								<p class="search_meta"><?php comments_popup_link( __( 'Be the first to leave a comment &#187;', 'commentpress-poets' ), __( '1 Comment &#187;', 'commentpress-poets' ), __( '% Comments &#187;', 'commentpress-poets' ) ); ?></p>

							</div><!-- /archive_item -->

						<?php endwhile; ?>

					<?php else : ?>

						<h2 class="post_title"><?php esc_html_e( 'Not Found', 'commentpress-poets' ); ?></h2>

						<p><?php esc_html_e( 'Sorry, but you are looking for something that isn\'t here.', 'commentpress-poets' ); ?></p>

						<?php get_search_form(); ?>

					<?php endif; ?>

				</div><!-- /post -->
			</div><!-- /content -->

			<div class="page_nav_lower">
				<?php commentpress_page_navigation_template(); ?>
			</div><!-- /page_nav_lower -->

		</div><!-- /page_wrapper -->
	</div><!-- /main_wrapper -->
</div><!-- /wrapper -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
