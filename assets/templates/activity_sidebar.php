<?php
/**
 * Activity Sidebar Template.
 *
 * @package CommentPress_Poets
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Access globals.
global $post, $blog_id;

// Get core plugin reference.
$core = commentpress_core();

// Init output.
$_page_comments_output = '';

// Is it commentable?
$is_commentable = commentpress_is_commentable();

// If a commentable Post.
if ( $is_commentable && ! post_password_required() ) {

	// Get singular Post Type label.
	$current_type  = get_post_type();
	$post_type_obj = get_post_type_object( $current_type );

	/**
	 * Filters the name of the Post Type.
	 *
	 * @since 3.8.10
	 *
	 * @param str $singular_name The singular label for this Post Type.
	 * @param str $current_type The Post Type identifier.
	 * @return str $singular_name The modified label for this Post Type.
	 */
	$post_type_name = apply_filters( 'commentpress_lexia_post_type_name', $post_type_obj->labels->singular_name, $current_type );

	// Construct "Recent Comments" phrase.
	$_paragraph_text = sprintf(
		/* translators: %s: The name of the Post Type. */
		__( 'Recent Comments on this %s', 'commentpress-poets' ),
		$post_type_name
	);

	/**
	 * Filters the "Recent Comments" phrase.
	 *
	 * @since 3.4
	 *
	 * @param str $_paragraph_text The default "Recent Comments" phrase.
	 */
	$page_comments_title = apply_filters( 'cp_activity_tab_recent_title_page', $_paragraph_text );

	// Get Page Comments.
	$_page_comments_output = commentpress_get_comment_activity( 'post' );

}

// Set default.
$_all_comments_title = __( 'Latest Comments', 'commentpress-poets' );

// Get all Comments.
$_all_comments_output = commentpress_get_comment_activity( 'all' );

// Set maximum number to show.
// TODO: Make this an option?
$_max_members = 10;

?>
<!-- activity_sidebar.php -->
<div id="activity_sidebar" class="sidebar_container">

	<div class="sidebar_header">
		<h2><?php esc_html_e( 'Activity', 'commentpress-poets' ); ?></h2>
	</div>

	<div class="sidebar_minimiser">
		<div class="sidebar_contents_wrapper">

			<div class="comments_container">

				<?php

				// Allow Widgets to be placed above Activity.
				dynamic_sidebar( 'cp-activity-top' );

				/**
				 * Allow plugins to add their own Activity Headings here.
				 *
				 * @since 3.4.8
				 */
				do_action( 'commentpress_bp_activity_sidebar_before_activity' );

				?>

				<?php if ( $is_commentable && '' !== $_page_comments_output ) : ?>

					<?php

					// Show Page Comments if we can.

					/**
					 * Allow plugins to add their own Activity Heading here.
					 *
					 * @since 3.4.8
					 */
					do_action( 'commentpress_bp_activity_sidebar_before_page_comments' );

					?>

					<h3 class="activity_heading"><?php echo esc_html( $page_comments_title ); ?></h3>

					<div class="paragraph_wrapper page_comments_output">
						<?php /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */ ?>
						<?php echo $_page_comments_output; ?>
					</div>

					<?php

					/**
					 * Allow plugins to add their own Activity Heading here.
					 *
					 * @since 3.4.8
					 */
					do_action( 'commentpress_bp_activity_sidebar_after_page_comments' );

					?>

				<?php endif; /* End commentable Post/Page check. */ ?>

				<?php if ( '' !== $_all_comments_output ) : ?>

					<?php

					// Show all Comments from Site if we can.

					/**
					 * Allow plugins to add their own Activity Heading here.
					 *
					 * @since 3.4.8
					 */
					do_action( 'commentpress_bp_activity_sidebar_before_all_comments' );

					?>

					<h3 class="activity_heading"><?php echo esc_html( $_all_comments_title ); ?></h3>

					<div class="paragraph_wrapper all_comments_output start_open">
						<?php /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */ ?>
						<?php echo $_all_comments_output; ?>
					</div>

					<?php

					/**
					 * Allow plugins to add their own Activity Heading here.
					 *
					 * @since 3.4.8
					 */
					do_action( 'commentpress_bp_activity_sidebar_after_all_comments' );

					?>

				<?php endif; /* End comments from site check. */ ?>

				<?php

				/**
				 * Allow plugins to add their own Activity Headings here.
				 *
				 * @since 3.4.8
				 */
				do_action( 'commentpress_bp_activity_sidebar_after_activity' );

				// Allow Widgets to be placed below Activity.
				dynamic_sidebar( 'cp-activity-bottom' );

				?>

			</div><!-- /comments_container -->
		</div><!-- /sidebar_contents_wrapper -->

	</div><!-- /sidebar_minimiser -->
</div><!-- /activity_sidebar -->
