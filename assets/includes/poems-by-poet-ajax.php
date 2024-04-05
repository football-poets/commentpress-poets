<?php
/**
 * Template part for loading a display of Poems that a Poet has written.
 *
 * This template is loaded during AJAX calls to load more Poems. It's used because
 * the Football Poets server runs out of memory when loading prolific Poets.
 *
 * @package CommentPress_Poets
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Define query args.
$query_args = [
	'connected_type'  => 'poets_to_poems',
	'connected_items' => $poet_id,
	'posts_per_page'  => 100,
	'paged'           => $page_num,
];

// Do the query.
$query = new WP_Query( $query_args );

if ( $query->have_posts() ) :

	// Start the loop.
	while ( $query->have_posts() ) :
		$query->the_post();

		?>

		<li <?php post_class( 'poem' ); ?>>
			<?php
			the_title(
				'<span class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">',
				'</a></span>'
			);
			?>
		</li><!-- #post-## -->

	<?php endwhile; ?>

	<?php if ( $page_num < (int) $query->max_num_pages ) : ?>
		<li class="load-more"><a href="<?php echo esc_url( add_query_arg( 'poemspage', $page_num + 1, get_permalink( $poet_id ) ) ); ?>" data-poet="<?php echo esc_attr( $poet_id ); ?>" data-page="<?php echo esc_attr( $page_num + 1 ); ?>" data-security="<?php echo esc_attr( wp_create_nonce( 'poems_load_more' ) ); ?>"><?php esc_html_e( 'Load more', 'commentpress-poets' ); ?></a></li>
	<?php endif; ?>

	<?php

endif;

// Prevent weirdness.
wp_reset_postdata();
