<?php
/**
 * CommentPress Football Poets Theme Functions
 *
 * Theme amendments and overrides.
 *
 * This file is loaded before the CommentPress Flat Theme's functions.php file,
 * so changes and updates can be made here. Most theme-related functions are
 * pluggable, so if they are defined here, they will override the ones defined in
 * the CommentPress Flat Theme or common theme functions file.
 *
 * @package CommentPress_Poets
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Set our version here.
define( 'COMMENTPRESS_POETS_VERSION', '1.4' );

/**
 * Augment the CommentPress Modern Theme setup function.
 *
 * @since 1.0
 */
function commentpress_poets_setup() {

	/**
	 * Make theme available for translation.
	 *
	 * Translations can be added to the /languages directory of the child theme.
	 */
	load_child_theme_textdomain(
		'commentpress-poets',
		get_stylesheet_directory() . '/languages'
	);

	// Create custom filters that mirror 'the_content'.
	add_filter( 'commentpress_poets_richtext_content', 'wptexturize' );
	add_filter( 'commentpress_poets_richtext_content', 'convert_smilies' );
	add_filter( 'commentpress_poets_richtext_content', 'convert_chars' );
	add_filter( 'commentpress_poets_richtext_content', 'wpautop' );
	add_filter( 'commentpress_poets_richtext_content', 'shortcode_unautop' );

}

// Add action for the above.
add_action( 'after_setup_theme', 'commentpress_poets_setup' );

/**
 * Enqueue child theme styles.
 *
 * Styles can be overridden because the child theme is:
 * 1. enqueueing later than the CommentPress Modern Theme
 * 2. making the file dependent on the CommentPress Modern Theme's stylesheet
 *
 * @since 1.0
 */
function commentpress_poets_enqueue_styles() {

	/*
	// Dequeue parent theme colour styles.
	wp_dequeue_style( 'cp_webfont_lato_css' );
	wp_dequeue_style( 'cp_colours_css' );
	*/

	// Add child theme's CSS file.
	wp_enqueue_style(
		'commentpress_poets_css',
		get_stylesheet_directory_uri() . '/assets/css/commentpress-poets.css',
		[ 'cp_screen_css' ],
		COMMENTPRESS_POETS_VERSION, // Version.
		'all' // Media.
	);

}

// Add action for the above.
add_action( 'wp_enqueue_scripts', 'commentpress_poets_enqueue_styles', 998 );


/**
 * Enqueue child theme scripts.
 *
 * @since 1.0
 */
function commentpress_poets_enqueue_scripts() {

	// Only load on Poet pages.
	if ( ! is_singular( 'poet' ) ) {
		return;
	}

	// Add our Poem Loader javascript.
	wp_enqueue_script(
		'commentpress_poets_poems_js',
		get_stylesheet_directory_uri() . '/assets/js/commentpress-poets-poems.js',
		[ 'cp_common_js' ],
		COMMENTPRESS_POETS_VERSION,
		true
	);

	// Define local vars.
	$vars = [
		'settings' => [
			'ajax_url' => admin_url( 'admin-ajax.php' ),
		],
		'localisation' => [
			'loading' => __( 'Loading', 'commentpress-poets' ),
		],
	];

	// Localise.
	wp_localize_script( 'commentpress_poets_poems_js', 'Poets_Display_Vars', $vars );

}

// Add action for the above.
add_action( 'wp_enqueue_scripts', 'commentpress_poets_enqueue_scripts', 998 );

/**
 * Load more Poems on Poet Profile Page.
 *
 * @since 1.4
 */
function commentpress_poets_poems_ajax_load() {

	// Init AJAX return.
	$data = [
		'error' => '',
		'poems' => '',
	];

	// Since this is an AJAX request, check security.
	$result = check_ajax_referer( 'poems_load_more', false, false );
	if ( $result === false ) {
		$data['error'] = __( 'Authentication failed.', 'commentpress-poets' );
		wp_send_json( $data );
	}

	// Get Poet ID.
	$poet_id = isset( $_POST['poet'] ) ? (int) sanitize_text_field( wp_unslash( $_POST['poet'] ) ) : null;
	if ( is_null( $poet_id ) || ! is_int( $poet_id ) ) {
		$data['error'] = __( 'Oh dear, something went wrong. No Poet ID was received.', 'commentpress-poets' );
		wp_send_json( $data );
	}

	// Get Page Number - fall back to first Page.
	$page_num = isset( $_POST['page'] ) ? (int) sanitize_text_field( wp_unslash( $_POST['page'] ) ) : 1;

	// Start buffering.
	ob_start();

	// Template output ends up in buffer.
	include get_stylesheet_directory() . '/assets/includes/poems-by-poet-ajax.php';

	// Save the output and flush the buffer.
	$data['content'] = ob_get_clean();

	// Send to browser.
	wp_send_json( $data );

}

// Add AJAX functionality for "Load more" Poems.
add_action( 'wp_ajax_poets_poems_load', 'commentpress_poets_poems_ajax_load' );
add_action( 'wp_ajax_nopriv_poets_poems_load', 'commentpress_poets_poems_ajax_load' );

/**
 * Do not show the "Special Pages" menu.
 *
 * @since 1.0
 *
 * @return bool Always false - do not show menu.
 */
add_filter( 'cp_content_tab_special_pages_visible', '__return_false' );

/**
 * Show meta for a poem.
 *
 * @since 1.0
 */
function commentpress_poets_poem_meta() {

	// Copyright key.
	$key = '_poets_poems_author_copyright';

	// Get value if the custom field has one.
	$copyright = '';
	$existing = get_post_meta( get_the_ID(), $key, true );
	if ( false !== $existing ) {
		$copyright = get_post_meta( get_the_ID(), $key, true );
	}

	// Notes key.
	$key = '_poets_poems_content_notes';

	// Get value if the custom field has one.
	$notes = '';
	$existing = get_post_meta( get_the_ID(), $key, true );
	if ( false !== $existing ) {
		$notes = get_post_meta( get_the_ID(), $key, true );
	}

	// Open wrapper if we have some meta.
	if ( ! empty( $copyright ) || ! empty( $notes ) ) {
		echo '<div class="poem_meta">';
	}

	// Maybe show copyright.
	if ( ! empty( $copyright ) ) {
		echo '<div class="poem_author_copyright">';
		echo apply_filters( 'commentpress_poets_richtext_content', '&copy; ' . $copyright );
		echo '</div>';
	}

	// Maybe show content.
	if ( ! empty( $notes ) ) {
		echo '<h4>' . __( 'Notes', 'commentpress-poets' ) . '</h4>' . "\n";
		echo '<div class="poem_content_notes">';
		echo apply_filters( 'commentpress_poets_richtext_content', $notes );
		echo '</div>';
	}

	// Close wrapper.
	if ( ! empty( $copyright ) || ! empty( $notes ) ) {
		echo '</div>';
	}

}

/**
 * Register widget areas for this theme.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 *
 * @since 1.0
 */
function commentpress_poets_register_widget_areas() {

	// Define an area where a widget may be placed.
	register_sidebar( [
		'name' => __( 'Homepage Left', 'commentpress-poets' ),
		'id' => 'cp-homepage-left',
		'description' => __( 'An optional widget area on the left of the Homepage', 'commentpress-poets' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	] );

	// Define an area where a widget may be placed.
	register_sidebar( [
		'name' => __( 'Homepage Right', 'commentpress-poets' ),
		'id' => 'cp-homepage-right',
		'description' => __( 'An optional widget area on the right of the Homepage', 'commentpress-poets' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	] );

	// Define an area where a widget may be placed.
	register_sidebar( [
		'name' => __( 'Homepage Lower', 'commentpress-poets' ),
		'id' => 'cp-homepage-below',
		'description' => __( 'An optional widget area below the left and right widgets on the Homepage', 'commentpress-poets' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	] );

}

add_action( 'widgets_init', 'commentpress_poets_register_widget_areas' );

/**
 * Enqueue styles to theme the login page.
 *
 * @since 1.0
 */
function commentpress_poets_enqueue_login_styles() {

	?>
	<style type="text/css">

		/* Page */
		html,
		html body
		{
			background: #f7f7ef;
			background-color: #f7f7ef !important;
		}

		/* Logo */
		#login h1 a,
		.login h1 a
		{
			background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo/football-poets-logo-200.png);
			background-size: 200px;
			width: 200px;
			height: 66px;
			padding-bottom: 10px;
		}

		/* Form
		body.login form
		{
			background: #fcfcf8;
		} */

		body.login form .input,
		body.login input[type="text"]
		{
			background: #EAE9DB;
		}

		body.login #nav,
		body.login #backtoblog
		{
			text-align: center;
		}

		body.login .message
		{
			border-left: 4px solid #757d12;
		}

		body.login #nav a:hover,
		body.login #backtoblog a:hover,
		body.login h1 a:hover
		{
			color: #555d66;
		}

	</style>
	<?php

	/*
	// Some residual code.
		body.login input[type="text"]:focus,
		body.login input[type="password"]:focus
		{
			border-color: #C1C3A9;
			-webkit-box-shadow: 0 0 2px rgba( 116, 125, 31, 0.8 );
			box-shadow: 0 0 2px rgba( 116, 125, 31, 0.8 );
		}
	*/

}

// Add action for the above.
add_action( 'login_enqueue_scripts', 'commentpress_poets_enqueue_login_styles', 20 );

/**
 * Override auth panel background.
 *
 * @since 1.1
 */
function commentpress_poets_admin_head() {

	// Match auth panel background to theme.
	echo '<style>
		body #wp-auth-check-wrap #wp-auth-check
		{
			background: #f7f7ef;
			background-color: #f7f7ef;
		}
	</style>';

}

// Add action for the above.
add_action( 'admin_head', 'commentpress_poets_admin_head' );

/**
 * Get default image for Open Graph sharing.
 *
 * @since 1.3
 *
 * @param array $media The array of image data.
 * @param int $post_id The Id of the WordPress post - sometimes missing.
 * @param array $args Additional arguments - sometimes missing.
 */
function commentpress_poets_custom_og_image( $media, $post_id = null, $args = [] ) {

	/*
	// Logging.
	$e = new \Exception();
	$trace = $e->getTraceAsString();
	error_log( print_r( [
		'method' => __METHOD__,
		'media' => $media,
		'post_id' => $post_id,
		'args' => $args,
		//'backtrace' => $trace,
	], true ) );
	*/

	/*
	// Bail if media is set.
	if ( $media ) {
		return $media;
	}
	*/

	// Bail if no Post ID.
	if ( is_null( $post_id ) || ! is_numeric( $post_id ) ) {
		return $media;
	}

	// Get permalink of Post.
	$permalink = get_permalink( $post_id );

	// Get URL of image.
	$url = apply_filters( 'jetpack_photon_url', commentpress_poets_default_og_image() );

	// --<
	return [
		[
			'type'  => 'image',
			'from'  => 'custom_fallback',
			'src'   => esc_url( $url ),
			'src_width' => 200,
			'src_height' => 200,
			'href'  => $permalink,
		],
	];

}

// Add filter for the above.
add_filter( 'jetpack_images_get_images', 'commentpress_poets_custom_og_image', 10, 3 );

/**
 * Set default image for Open Graph sharing.
 *
 * @since 1.3
 *
 * @param str $src The existing Open Graph image URL.
 * @return str $src The modified Open Graph image URL.
 */
function commentpress_poets_default_og_image( $src = '' ) {

	// --<
	return 'http://footballpoets.org/wp-content/themes/commentpress-poets/assets/images/logo/football-poets-icon.jpg';

}

// Add filter for the above.
add_filter( 'jetpack_open_graph_image_default', 'commentpress_poets_custom_og_image', 10, 3 );

/**
 * Improve the meta description.
 *
 * @since 1.3.4
 *
 * @param str $description The existing meta description.
 * @return str $description The modified meta description.
 */
function commentpress_poets_header_meta_description( $description ) {

	// Leave anything but single items alone.
	if ( ! is_singular() ) {
		return $description;
	}

	// Get item.
	$queried_post = get_queried_object();

	// Sanity checks.
	if ( ! ( $queried_post instanceof WP_Post ) ) {
		return $description;
	}
	if ( $queried_post->post_type != 'poet' ) {
		return $description;
	}

	// Use content, if we have some.
	if ( ! empty( $queried_post->post_content ) ) {

		// Truncate Poet for description.
		$description = wp_trim_words( $queried_post->post_content, 30 );

	} else {

		// Truncate Poet for description.
		$description = sprintf(
			/* translators: %s: The name of the Poet. */
			__( 'This is the Football Poets profile page for %s. All the poems they have submitted to this website are listed here.', 'commentpress-poets' ),
			single_post_title( '', false )
		);

	}

	/*
	$e = new Exception();
	$trace = $e->getTraceAsString();
	error_log( print_r( array(
		'method' => __METHOD__,
		'queried_post' => $queried_post,
		'description' => $description,
		'old-description' => $old_description,
		'backtrace' => $trace,
	), true ) );
	*/

	// --<
	return $description;

}

// Add filter for the above.
add_filter( 'commentpress_header_meta_description', 'commentpress_poets_header_meta_description', 10, 1 );

/**
 * Filter the conditions for showing feature image switcher button.
 *
 * @since 1.3.4
 *
 * @param bool $allowed True if button is to be shown, false otherwise.
 * @param WP_Post $post The WordPress Post object.
 * @return bool $allowed True if button is to be shown, false otherwise.
 */
function commentpress_poets_image_switcher_allow_button( $allowed, $post ) {

	// Disallow on Poet CPT.
	if ( $post->post_type == 'poet' ) {
		$allowed = false;
	}

	// --<
	return $allowed;

}

// Add filter for the above.
add_filter( 'feature_image_switcher_allow_button', 'commentpress_poets_image_switcher_allow_button', 10, 2 );

/**
 * Filters the content markup of the Jetpack sharing links.
 *
 * @since 1.3.5
 *
 * @param string $sharing_content Content markup of the Jetpack sharing links.
 * @param array $enabled Array of Sharing Services currently enabled.
 * @return string $sharing_content Modified content markup of the Jetpack sharing links.
 */
function commentpress_poets_sharing_display_markup( $sharing_content, $enabled ) {

	// Bail if not a Poem.
	if ( get_post_type() !== 'poem' ) {
		return $sharing_content;
	}

	// Add a class.
	$sharing_content = str_replace( '<ul>', '<ul class="jetpack-sharer-list">', $sharing_content );

	// --<
	return $sharing_content;

}

// Add filter for the above.
add_filter( 'jetpack_sharing_display_markup', 'commentpress_poets_sharing_display_markup', 10, 2 );

/**
 * Filters the default post types that can have like buttons on them.
 *
 * @since 1.3.5
 *
 * @param array $types The default post types array.
 * @return array $types The modified post types array
 */
function commentpress_poets_wpls_get_post_types( $types ) {

	// Only poems allowed.
	return [ 'poem' => 'poem' ];

}

// Add filter for the above.
add_filter( 'wpls_get_post_types', 'commentpress_poets_wpls_get_post_types', 10 );

/**
 * Adds our styles to the TinyMCE editor.
 *
 * @since 1.3.7
 *
 * @param str $mce_css The default TinyMCE stylesheets as set by WordPress.
 * @return str $mce_css The list of stylesheets with ours added.
 */
function commentpress_poets_add_tinymce_styles( $mce_css ) {

	// Only on front-end.
	if ( is_admin() ) {
		return $mce_css;
	}

	// Add comma if not empty.
	if ( ! empty( $mce_css ) ) {
		$mce_css .= ',';
	}

	// Add our editor styles.
	$mce_css .= get_stylesheet_directory_uri() . '/assets/css/comment-form.css';

	// --<
	return $mce_css;

}

// Add filter for the above.
add_filter( 'mce_css', 'commentpress_poets_add_tinymce_styles', 20 );

/**
 * Hides a Member's Poems and Poets menu items until that Member is approved.
 *
 * @since 1.3.7
 *
 * @param string $value Markup for the tab list item including link.
 * @param array $user_nav_item Array holding parts used to construct tab list item. Passed by reference.
 * @return string $value Modified markup for the tab list item including link.
 */
function commentpress_poets_nav_item_moderation_check( $value, $user_nav_item ) {

	$user_id = get_current_user_id();
	$moderate = get_option( 'bprwg_moderate' );

	// Is User still held in moderation? And is this the User's profile?
	if ( $moderate && bp_registration_get_moderation_status( $user_id ) && bp_is_my_profile() ) {
		return '';
	}

	// --<
	return $value;

}

// Add filters for the above.
add_filter( 'bp_get_displayed_user_nav_my-poems', 'commentpress_poets_nav_item_moderation_check', 20, 2 );
add_filter( 'bp_get_displayed_user_nav_my-poets', 'commentpress_poets_nav_item_moderation_check', 20, 2 );
