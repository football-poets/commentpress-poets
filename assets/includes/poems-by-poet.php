<?php
/**
 * Template part for embedding a display of poems that a poet has written.
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
	'posts_per_page' => 100,
];

// Do the query.
$query = new WP_Query( $query_args );

// Get Poet name.
$poet = get_queried_object()->post_title;

if ( $query->have_posts() ) :

	?>

	<section class="content-area poems-by-poet clear">
		<div class="poems-by-poet-inner">

			<header class="poems-by-poet-header">
				<h2 class="poems-by-poet-title"><?php echo sprintf( __( 'Poems by %s', 'commentpress-poets' ), $poet ); ?></h2>
			</header><!-- .poems-by-poet-header -->

			<ul class="poems-by-poet-list">
			<?php

			// Start the loop.
			while ( $query->have_posts() ) :
				$query->the_post();

				?>

				<li <?php post_class(); ?>>
					<?php
					the_title(
						'<span class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">',
						'</a></span>'
					);
					?>
				</li><!-- #post-## -->

				<?php

			endwhile;

			?>

			<?php if ( 100 < (int) $query->found_posts ) : ?>
				<li class="load-more"><a href="<?php echo esc_url( add_query_arg( 'poemspage', '2', get_permalink( get_queried_object()->ID ) ) ); ?>" data-poet="<?php echo esc_attr( get_queried_object()->ID ); ?>" data-page="2" data-security="<?php echo esc_attr( wp_create_nonce( 'poems_load_more' ) ); ?>"><?php esc_html_e( 'Load more', 'commentpress-poets' ); ?></a></li>
			<?php endif; ?>

			</ul><!-- .poems-by-poet-list -->

		</div><!-- .poems-by-poet-inner -->
	</section><!-- .poems-by-poet -->

	<?php

endif;

// Prevent weirdness.
wp_reset_postdata();
