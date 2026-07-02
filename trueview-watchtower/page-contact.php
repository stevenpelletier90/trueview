<?php
/**
 * Template Name: Contact
 *
 * Contact page — Concept A · Clarity.
 *
 * Bound automatically to a Page with the slug "contact"; also selectable
 * from the page-template dropdown via the "Contact" template name above.
 *
 * The consultation form is rendered by Gravity Forms (form ID 1) via
 * gravity_form(). If Gravity Forms is inactive, a phone/email fallback shows
 * so the page never breaks.
 *
 * @package trueview-watchtower
 */

get_header();
?>

<section class="tv-chero">
	<div class="tv-container">
		<div class="tv-chero__content">
			<div class="tv-eyebrow tv-eyebrow--lg tv-eyebrow--bright tv-chero__eyebrow">24-Hour Service Available</div>
			<h1 class="tv-chero__title">Let's talk about your site.</h1>
			<p class="tv-chero__lead">Reach out for a free security consultation. Call or text for the fastest response — or send the form below and we'll get right back to you.</p>
		</div>
	</div>
</section>

<section class="tv-contact">
	<div class="tv-contact__inner">

		<div class="tv-contact__main">
			<div class="tv-eyebrow">Free Consultation</div>
			<h2 class="tv-form__title">Request a security assessment</h2>
			<div class="tv-form tv-form--gravity">
					<?php if ( function_exists( 'gravity_form' ) ) : ?>
						<?php gravity_form( 1, false, false, false, null, true ); ?>
					<?php else : ?>
						<p class="tv-form__note">Our consultation form is temporarily unavailable. Please call or text <a href="tel:6076008065">607-600-8065</a> or email <a href="mailto:info@trueviewny.com">info@trueviewny.com</a> and we'll respond within one business day.</p>
					<?php endif; ?>
				</div>
		</div>

		<div class="tv-contact__rail">
			<div class="tv-rail">
				<div class="tv-rail__title">Prefer to talk now?</div>
				<p class="tv-rail__lead">Call or text — 24-hour service available.</p>
				<a class="tv-rail__phone" href="tel:6076008065">607-600-8065</a>
				<a class="tv-rail__email" href="mailto:info@trueviewny.com">info@trueviewny.com</a>
				<div class="tv-rail__hours">
					<div class="tv-rail__hour"><span class="tv-dot tv-dot--green"></span>Mon–Fri · 8AM–5PM</div>
					<div class="tv-rail__hour"><span class="tv-dot tv-dot--cyan"></span>24/7 Emergency Service</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="tv-visit">
	<div class="tv-container">
		<div class="tv-visit__head">
			<div class="tv-eyebrow">Visit Us</div>
			<h2 class="tv-h2">Two Northeast locations</h2>
		</div>
		<div class="tv-visit__grid">
			<div class="tv-place">
				<div class="tv-place__map">
					<span class="tv-place__pin"><span class="tv-place__pin-dot"></span>NEW YORK</span>
				</div>
				<div class="tv-place__body">
					<div class="tv-place__city">Binghamton, NY</div>
					<div class="tv-place__addr">221 Wilson Hill Road<br>Binghamton, NY 13905</div>
					<div class="tv-place__hours"><span>Mon–Fri · 8AM–5PM</span><span class="tv-place__emerg">24/7 Emergency</span></div>
				</div>
			</div>
			<div class="tv-place">
				<div class="tv-place__map">
					<span class="tv-place__pin"><span class="tv-place__pin-dot"></span>PENNSYLVANIA</span>
				</div>
				<div class="tv-place__body">
					<div class="tv-place__city">Susquehanna, PA</div>
					<div class="tv-place__addr">31596 PA-171<br>Susquehanna, PA 18847</div>
					<div class="tv-place__hours"><span>Mon–Fri · 8AM–5PM</span><span class="tv-place__emerg">24/7 Emergency</span></div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
