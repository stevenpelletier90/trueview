<?php
/**
 * True View Watchtower — theme functions.
 *
 * @package trueview-watchtower
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // No direct access.
}

/**
 * Theme setup.
 */
function trueview_setup(): void {
	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
}
add_action( 'after_setup_theme', 'trueview_setup' );

/**
 * Enqueue fonts and stylesheets.
 *
 * Base/header/footer styles load everywhere; home and contact load only on
 * their respective pages. All live in assets/css/ — there are no inline styles.
 */
function trueview_assets(): void {
	$version = wp_get_theme()->get( 'Version' );
	$css     = get_template_directory_uri() . '/assets/css/';

	wp_enqueue_style(
		'trueview-fonts',
		'https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap',
		array(),
		$version
	);

	wp_enqueue_style( 'trueview-base', $css . 'base.css', array( 'trueview-fonts' ), $version );
	wp_enqueue_style( 'trueview-header', $css . 'header.css', array( 'trueview-base' ), $version );
	wp_enqueue_style( 'trueview-footer', $css . 'footer.css', array( 'trueview-base' ), $version );

	if ( is_front_page() ) {
		wp_enqueue_style( 'trueview-home', $css . 'home.css', array( 'trueview-base' ), $version );
	}

	if ( is_page_template( 'page-contact.php' ) || is_page( 'contact' ) ) {
		wp_enqueue_style( 'trueview-contact', $css . 'contact.css', array( 'trueview-base' ), $version );
	}
}
add_action( 'wp_enqueue_scripts', 'trueview_assets' );

/**
 * Full URL to a file in the theme's /assets folder.
 *
 * @param string $path Filename, e.g. "logo.png".
 * @return string
 */
function trueview_asset( string $path ): string {
	return get_template_directory_uri() . '/assets/' . ltrim( $path, '/' );
}

/**
 * Resolve the Contact page URL so header & CTA buttons always have a target.
 *
 * Order of preference:
 *   1) A page using the page-contact.php template (selected in the editor).
 *   2) A page with the slug "contact" (page-contact.php binds to it by name).
 *   3) /contact/ as a final fallback.
 *
 * @return string
 */
function trueview_contact_url(): string {
	$pages = get_pages(
		array(
			'meta_key'   => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value' => 'page-contact.php',  // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			'number'     => 1,
		)
	);
	if ( is_array( $pages ) && isset( $pages[0] ) ) {
		$url = get_permalink( $pages[0]->ID );
		if ( false !== $url ) {
			return $url;
		}
	}

	$contact = get_page_by_path( 'contact' );
	if ( $contact instanceof WP_Post ) {
		$url = get_permalink( $contact->ID );
		if ( false !== $url ) {
			return $url;
		}
	}

	return home_url( '/contact/' );
}
