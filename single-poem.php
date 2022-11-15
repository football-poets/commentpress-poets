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
<!-- single-poem.php -->
<div id="wrapper">

	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>

			<?php

			// Access Post.
			global $post;

			// Init class values.
			$tabs_class = '';
			$tabs_classes = '';

			// Init workflow items.
			$original = '';
			$literal = '';

			// Do we have workflow?
			if ( is_object( $commentpress_core ) ) {

				// Get workflow.
				$_workflow = $commentpress_core->db->option_get( 'cp_blog_workflow' );

				// Is it enabled?
				if ( $_workflow == '1' ) {

					// Okay, let's add our tabs.

					// Set key.
					$key = '_cp_original_text';

					// If the custom field already has a value.
					if ( get_post_meta( $post->ID, $key, true ) != '' ) {
						$original = get_post_meta( $post->ID, $key, true );
					}

					// Set key.
					$key = '_cp_literal_translation';

					// If the custom field already has a value.
					if ( get_post_meta( $post->ID, $key, true ) != '' ) {
						$literal = get_post_meta( $post->ID, $key, true );
					}

					// Did we get either type of workflow content?
					if ( $literal != '' || $original != '' ) {

						// Override tabs class.
						$tabs_class = 'with-content-tabs';

						// Override tabs classes.
						$tabs_classes = ' class="' . $tabs_class . '"';

						// Prefix with space.
						$tabs_class = ' ' . $tabs_class;

					}

				}

			}

			?>

			<div id="main_wrapper" class="clearfix<?php echo $tabs_class; ?>">

				<?php

				// Did we get tabs?
				if ( $tabs_class != '' ) {

					// Did we get either type of workflow content?
					if ( $literal != '' || $original != '' ) {

						?>
						<ul id="content-tabs">
							<li id="content_header" class="default-content-tab"><h2><a href="#content">
								<?php
								echo apply_filters(
									'commentpress_content_tab_content',
									__( 'Content', 'commentpress-poets' )
								);
								?>
							</a></h2></li>
								<?php if ( $literal != '' ) { ?>
							<li id="literal_header"><h2><a href="#literal">
							<?php
								echo apply_filters(
									'commentpress_content_tab_literal',
									__( 'Literal', 'commentpress-poets' )
								);
							?>
							</a></h2></li>
							<?php } ?>
							<?php if ( $original != '' ) { ?>
							<li id="original_header"><h2><a href="#original">
							<?php
								echo apply_filters(
									'commentpress_content_tab_original',
									__( 'Original', 'commentpress-poets' )
								);
							?>
							</a></h2></li>
							<?php } ?>
						</ul>
						<?php

					}

				}

				?>

				<div id="page_wrapper"<?php echo $tabs_classes; ?>>

					<?php commentpress_get_feature_image(); ?>

					<?php

					// First try to locate using WP method.
					$cp_page_navigation = apply_filters(
						'cp_template_page_navigation',
						locate_template( 'assets/templates/page_navigation.php' )
					);

					// Do we have a featured image?
					if ( ! commentpress_has_feature_image() ) {

						// Load it if we find it.
						if ( $cp_page_navigation != '' ) {
							load_template( $cp_page_navigation, false );
						}

					}

					?>

					<div id="content" class="workflow-wrapper">

						<div class="<?php echo join( ' ', get_post_class( 'post' ) ); ?><?php echo commentpress_get_post_css_override( get_the_ID() ); ?>" id="post-<?php the_ID(); ?>">

							<?php

							// Do we have a featured image?
							if ( ! commentpress_has_feature_image() ) {

								?>
								<h2 class="post_title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

								<div class="search_meta">
									<?php $poem_author = locate_template( 'assets/includes/poem-author.php' ); ?>
									<?php if ( $poem_author ) : ?>
										<?php load_template( $poem_author ); ?>
									<?php endif; ?>

									<p><a href="<?php the_permalink(); ?>"><?php echo esc_html( get_the_date( __( 'l, F jS, Y', 'commentpress-poets' ) ) ); ?></a></p>

								</div>
								<?php

							}

							?>

							<?php commentpress_get_post_version_info( $post ); ?>

							<?php the_content( '' ); ?>

							<?php commentpress_poets_poem_meta(); ?>

							<?php

							/*
							 * NOTE: Comment permalinks are filtered if the comment is not on the first page
							 * in a multipage post... see: commentpress_multipage_comment_link in functions.php
							 */
							echo commentpress_multipager();

							?>

							<?php the_tags( '<div class="entry-meta"><p class="postmetadata">' . __( 'Tags: ', 'commentpress-poets' ), '<span class="tag-divider">,</span> ', '</p></div>' ); ?>

							<?php if ( ! empty( get_the_category_list( ', ' ) ) ) : ?>
								<div class="entry-category-meta clearfix">
									<p class="category-meta"><?php esc_html_e( 'Categories:', 'commentpress-poets' ); ?></p>
									<?php echo get_the_category_list( ', ' ); ?>
								</div>
							<?php endif; ?>

						</div><!-- /post -->

					</div><!-- /content -->

					<?php

					// Did we get tabs?
					if ( $tabs_class != '' ) {

						// Did we get either type of workflow content?
						if ( $literal != '' || $original != '' ) {

							// Did we get literal?
							if ( $literal != '' ) {

								?>
								<div id="literal" class="workflow-wrapper">
									<div class="post">
										<h2 class="post_title"><?php echo apply_filters( 'commentpress_literal_title', __( 'Literal Translation', 'commentpress-poets' ) ); ?></h2>
										<?php echo apply_filters( 'cp_workflow_richtext_content', $literal ); ?>
									</div><!-- /post -->
								</div><!-- /literal -->
								<?php

							}

							// Did we get original?
							if ( $original != '' ) {

								?>
								<div id="original" class="workflow-wrapper">
									<div class="post">
										<h2 class="post_title"><?php echo apply_filters( 'commentpress_original_title', __( 'Original Text', 'commentpress-poets' ) ); ?></h2>
										<?php echo apply_filters( 'cp_workflow_richtext_content', $original ); ?>
									</div><!-- /post -->
								</div><!-- /original -->
								<?php

							}

						}

					}

					?>

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
