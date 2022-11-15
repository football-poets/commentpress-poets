<?php
/**
 * Template part for embedding a link to the poet who wrote the poem.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package CommentPress_Poets
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Define query args.
$query_args = [
	'connected_type' => 'poets_to_poems',
	'connected_items' => get_queried_object(),
	'nopaging' => true,
	'no_found_rows' => true,
];

// Do the query.
$query = new WP_Query( $query_args );

// Show poet name.
if ( $query->have_posts() ) :
	while ( $query->have_posts() ) :
		$query->the_post();
		?>
		<cite class="fn"><?php the_title( '<a href="' . esc_url( get_permalink() ) . '">', '</a>' ); ?></cite>
		<?php
	endwhile;
endif;

// Prevent weirdness.
wp_reset_postdata();
