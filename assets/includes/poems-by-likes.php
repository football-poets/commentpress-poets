<?php
/**
 * Template part for embedding a display of the 50 most-liked Poems.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package CommentPress_Poets
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Access DB wrapper.
global $wpdb;

// Get the Poems sorted by number of likes.
$sql = 'SELECT p.ID, pm.meta_value ' .
	"FROM {$wpdb->prefix}posts p " .
	"INNER JOIN {$wpdb->prefix}postmeta pm ON pm.post_id = p.ID " .
	"WHERE p.post_type = 'poem' " .
	"AND p.post_status = 'publish' " .
	"AND pm.meta_key = '_post_like_count' " .
	"AND pm.meta_value != '' " .
	'ORDER BY CAST(pm.meta_value as unsigned) DESC ' .
	'LIMIT 50';

// phpcs:ignore: WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared
$poems = $wpdb->get_results( $sql, ARRAY_A );

// Get Poem IDs for filtering the actual query.
$poem_ids = wp_list_pluck( $poems, 'ID' );

// Build array to show number of likes.
$poem_likes = [];
foreach ( $poems as $correspondence ) {
	$poem_likes[ $correspondence['ID'] ] = $correspondence['meta_value'];
}

// Define query args.
$query_args = [
	'post_type' => 'poem',
	'post__in' => $poem_ids,
	'posts_per_page' => 50,
	'orderby' => 'post__in',
];

// Do the query.
$query = new WP_Query( $query_args );

if ( $query->have_posts() ) :

	// Find connected Poets for all Poems.
	p2p_type( 'poets_to_poems' )->each_connected( $query );

	?>

	<?php while ( $query->have_posts() ) : ?>

		<?php $query->the_post(); ?>

		<div class="<?php echo join( ' ', get_post_class( 'post' ) ); ?> search_result">

			<h3 id="post-<?php the_ID(); ?>" class="poem_title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php esc_attr_e( 'Permanent Link to', 'commentpress-poets' ); ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a> (<?php echo esc_html( $poem_likes[ get_the_ID() ] ); ?>)</h3>

			<div class="search_meta">
				<?php if ( ! empty( $post->connected ) ) : ?>
					<?php foreach ( $post->connected as $poet ) : ?>
						<cite class="fn"><a href="<?php echo esc_url( get_permalink( $poet->ID ) ); ?>"><?php echo get_the_title( $poet->ID ); ?></a></cite>
					<?php endforeach; ?>
				<?php endif; ?>
				<p><a href="<?php the_permalink(); ?>"><?php echo esc_html( get_the_date( __( 'l, F jS, Y', 'commentpress-poets' ) ) ); ?></a></p>
			</div>

			<p class="search_meta"><?php comments_popup_link( __( 'Be the first to leave a comment &#187;', 'commentpress-poets' ), __( '1 Comment &#187;', 'commentpress-poets' ), __( '% Comments &#187;', 'commentpress-poets' ) ); ?></p>

		</div><!-- /archive_item -->

	<?php endwhile; ?>

	<?php

endif;

// Prevent weirdness.
wp_reset_postdata();
