<?php
/**
 * Poem Category Taxonomy template.
 *
 * @package CommentPress_Poets
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

?>
<!-- taxonomy-poemcat.php -->
<div id="wrapper">

	<div id="main_wrapper" class="clearfix">

		<div id="page_wrapper">

			<?php

			// First try to locate using WP method.
			$cp_page_navigation = apply_filters(
				'cp_template_page_navigation',
				locate_template( 'assets/templates/page_navigation.php' )
			);

			// Load it if we find it.
			if ( $cp_page_navigation != '' ) {
				load_template( $cp_page_navigation, false );
			}

			?>

			<div id="content" class="clearfix">

				<div class="post">

					<?php if ( have_posts() ) : ?>

						<h3 class="post_title"><?php esc_html_e( 'Poems filed under', 'commentpress-poets' ); ?> &#8216;<?php single_tag_title(); ?>&#8217;</h3>

						<?php

						// Access main query.
						global $wp_query;

						// Find connected Poets for all Poems.
						p2p_type( 'poets_to_poems' )->each_connected( $wp_query );

						// Now do The Loop.
						while ( have_posts() ) :
							the_post();
							?>

							<div class="<?php echo join( ' ', get_post_class( 'post' ) ); ?> search_result">

								<h3 id="post-<?php the_ID(); ?>" class="post_title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php esc_attr_e( 'Permanent Link to', 'commentpress-poets' ); ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>

								<div class="search_meta">
									<?php

									// Show Poet.
									if ( ! empty( $post->connected ) ) :
										foreach ( $post->connected as $poet ) :
											?>
											<cite class="fn"><a href="<?php echo esc_url( get_permalink( $poet->ID ) ); ?>"><?php echo get_the_title( $poet->ID ); ?></a></cite>
											<?php
										endforeach;
									endif;

									?>
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
				<?php

				// Include page_navigation again.
				if ( $cp_page_navigation != '' ) {
					load_template( $cp_page_navigation, false );
				}

				?>
			</div><!-- /page_nav_lower -->

		</div><!-- /page_wrapper -->

	</div><!-- /main_wrapper -->

</div><!-- /wrapper -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
