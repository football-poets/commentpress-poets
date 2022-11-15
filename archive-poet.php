<?php
/**
 * Poet Archive template.
 *
 * @package CommentPress_Poets
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

?>
<!-- archive-poet.php -->
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

					<h3 class="post_title"><?php esc_html_e( 'The Football Poets', 'commentpress-poets' ); ?></h3>

					<?php global $paged; if ( isset( $paged ) && $paged == 1 ) : ?>
						<p><?php esc_html_e( 'This archive lists all the poets who have published one or more poems on this site. Poets are listed fifty-per-page in alphabetical order. Click or tap the arrows in the corners of the page to navigate between pages. It\'s easier to use the search form below if you are looking for a specific poet.', 'commentpress-poets' ); ?></p>
					<?php endif; ?>

					<div class="poet-search">
						<h3><?php esc_html_e( 'Search Poets', 'commentpress-poets' ); ?></h3>
						<form role="search" action="<?php echo get_post_type_archive_link( 'poet' ); ?>" method="get" id="searchform">
							<input type="text" name="s" placeholder="<?php esc_attr_e( 'Search Poets', 'commentpress-poets' ); ?>" value="<?php echo ( isset( $_GET['s'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_GET['s'] ) ) ) : '' ); ?>" />
							<input type="hidden" name="post_type" value="poet" />
							<input type="submit" alt="<?php esc_attr_e( 'Search', 'commentpress-poets' ); ?>" value="<?php esc_attr_e( 'Search', 'commentpress-poets' ); ?>" />
						</form>
					</div>

					<div class="poets-list">

					<?php
					while ( have_posts() ) :
						the_post();
						?>

						<div class="<?php echo join( ' ', get_post_class( 'post' ) ); ?> search_result">

							<h3 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php esc_attr_e( 'Permanent Link to', 'commentpress-poets' ); ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>

						</div><!-- /search_result -->

					<?php endwhile; ?>

						</div><!-- /poets-list -->

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
