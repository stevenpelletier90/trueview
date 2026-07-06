<?php
/**
 * Header — opens the document and renders the sticky site header.
 *
 * Nav adapts: on the Home page the section links are same-page anchors;
 * elsewhere (Contact) they point back to the Home page anchors.
 *
 * @package trueview-watchtower
 */

$trueview_home    = home_url( '/' );
$trueview_contact = trueview_contact_url();

if ( is_front_page() ) {
	$trueview_unit = '#unit';
	$trueview_why  = '#why';
	$trueview_cov  = '#coverage';
} else {
	$trueview_unit = $trueview_home . '#unit';
	$trueview_why  = $trueview_home . '#why';
	$trueview_cov  = $trueview_home . '#coverage';
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="tv-header">
	<div class="tv-header__inner">
		<a class="tv-brand" href="<?php echo esc_url( $trueview_home ); ?>">
			<img class="tv-brand__logo" src="<?php echo esc_url( trueview_asset( 'logo.png' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
			<span class="tv-brand__divider"></span>
			<span class="tv-brand__tag">WATCHTOWER</span>
		</a>
		<nav class="tv-nav">
			<a class="tv-nav__link" href="<?php echo esc_url( $trueview_unit ); ?>">The Unit</a>
			<a class="tv-nav__link" href="<?php echo esc_url( $trueview_why ); ?>">Why Watchtower</a>
			<a class="tv-nav__link" href="<?php echo esc_url( $trueview_cov ); ?>">Coverage</a>
			<?php if ( is_front_page() ) : ?>
				<a class="tv-nav__link" href="<?php echo esc_url( $trueview_contact ); ?>">Locations</a>
				<a class="tv-nav__phone" href="tel:6076008065" aria-label="Call 607-600-8065"><svg class="tv-nav__phone-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg><span class="tv-nav__phone-num">607-600-8065</span></a>
				<a class="tv-nav__cta" href="<?php echo esc_url( $trueview_contact ); ?>"><span class="tv-nav__cta-full">Free Consultation</span><span class="tv-nav__cta-short">Free Quote</span></a>
			<?php else : ?>
				<a class="tv-nav__link tv-nav__link--active" href="<?php echo esc_url( $trueview_contact ); ?>">Contact</a>
				<a class="tv-nav__phone" href="tel:6076008065" aria-label="Call 607-600-8065"><svg class="tv-nav__phone-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg><span class="tv-nav__phone-num">607-600-8065</span></a>
			<?php endif; ?>
		</nav>
	</div>
</header>

<main id="main" class="tv-main">
