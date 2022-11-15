<?php
/**
 * Activity Sidebar template.
 *
 * @package CommentPress_Poets
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Access globals.
global $post, $commentpress_core;

// Init output.
$_page_comments_output = '';

// Is it commentable?
$_is_commentable = commentpress_is_commentable();

// If a commentable Post.
if ( $_is_commentable && ! post_password_required() ) {

	// Get singular Post Type label.
	$current_type = get_post_type();
	$current_post_type = get_post_type_object( $current_type );

	/**
	 * Assign name of post type.
	 *
	 * @since 3.8.10
	 *
	 * @param str $singular_name The singular label for this post type
	 * @param str $current_type The post type identifier
	 * @return str $singular_name The modified label for this post type
	 */
	$post_type_name = apply_filters( 'commentpress_lexia_post_type_name', $current_post_type->labels->singular_name, $current_type );

	// Construct recent comments phrase.
	$_paragraph_text = sprintf(
		/* translators: %s: The name of the Post Type. */
		__( 'Recent Comments on this %s', 'commentpress-poets' ),
		$post_type_name
	);

	// Set default.
	$page_comments_title = apply_filters(
		'cp_activity_tab_recent_title_page',
		$_paragraph_text
	);

	// Get page comments.
	$_page_comments_output = commentpress_get_comment_activity( 'post' );

}

// Set default.
$_all_comments_title = __( 'Latest Comments', 'commentpress-poets' );

// Get all comments.
$_all_comments_output = commentpress_get_comment_activity( 'all' );

// Set maximum number to show - put into option?
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

				// Allow widgets to be placed above activity.
				dynamic_sidebar( 'cp-activity-top' );

				// Allow plugins to add their own activity headings here.
				do_action( 'commentpress_bp_activity_sidebar_before_activity' );

				// Show page comments if we can.
				if ( $_is_commentable && $_page_comments_output != '' ) {

					// Allow plugins to add their own activity heading here.
					do_action( 'commentpress_bp_activity_sidebar_before_page_comments' );

					?>
					<h3 class="activity_heading"><?php echo $page_comments_title; ?></h3>

					<div class="paragraph_wrapper page_comments_output">
						<?php echo $_page_comments_output; ?>
					</div>

					<?php

					// Allow plugins to add their own activity heading here.
					do_action( 'commentpress_bp_activity_sidebar_after_page_comments' );

				} // End commentable post/page check.

				// Show all comments from site if we can.
				if ( $_all_comments_output != '' ) {

					// Allow plugins to add their own activity heading here.
					do_action( 'commentpress_bp_activity_sidebar_before_all_comments' );

					?>
					<h3 class="activity_heading"><?php echo $_all_comments_title; ?></h3>

					<div class="paragraph_wrapper all_comments_output start_open">
						<?php echo $_all_comments_output; ?>
					</div>

					<?php

					// Allow plugins to add their own activity heading here.
					do_action( 'commentpress_bp_activity_sidebar_after_all_comments' );

				} // End comments from site check.

				// Allow plugins to add their own activity headings here.
				do_action( 'commentpress_bp_activity_sidebar_after_activity' );

				// Allow widgets to be placed below activity.
				dynamic_sidebar( 'cp-activity-bottom' );

				?>

			</div><!-- /comments_container -->

		</div><!-- /sidebar_contents_wrapper -->

	</div><!-- /sidebar_minimiser -->

</div><!-- /activity_sidebar -->
