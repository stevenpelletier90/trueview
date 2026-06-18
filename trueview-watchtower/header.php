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
			<a class="tv-nav__link" href="<?php echo esc_url( $trueview_why ); ?>">Why True View</a>
			<a class="tv-nav__link" href="<?php echo esc_url( $trueview_cov ); ?>">Coverage</a>
			<?php if ( is_front_page() ) : ?>
				<a class="tv-nav__link" href="<?php echo esc_url( $trueview_contact ); ?>">Locations</a>
				<a class="tv-nav__phone" href="tel:6076008065">607-600-8065</a>
				<a class="tv-nav__cta" href="<?php echo esc_url( $trueview_contact ); ?>">Free Consultation</a>
			<?php else : ?>
				<a class="tv-nav__link tv-nav__link--active" href="<?php echo esc_url( $trueview_contact ); ?>">Contact</a>
				<a class="tv-nav__phone" href="tel:6076008065">607-600-8065</a>
			<?php endif; ?>
		</nav>
	</div>
</header>
