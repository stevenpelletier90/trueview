<?php
/**
 * Template Name: Contact
 *
 * Contact page — Concept A · Clarity.
 *
 * Bound automatically to a Page with the slug "contact"; also selectable
 * from the page-template dropdown via the "Contact" template name above.
 *
 * The form is intentionally static for now — the submit button does nothing.
 * Field `name` attributes are in place so it can be wired to a handler or a
 * form plugin (Contact Form 7 / WPForms / Fluent Forms) later.
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
			<form class="tv-form" action="" method="post" novalidate>
				<div class="tv-form__row">
					<div class="tv-form__field">
						<label class="tv-form__label" for="tv-name">Full name</label>
						<input class="tv-form__control tv-form__input" id="tv-name" name="name" type="text" placeholder="Your name">
					</div>
					<div class="tv-form__field">
						<label class="tv-form__label" for="tv-company">Company</label>
						<input class="tv-form__control tv-form__input" id="tv-company" name="company" type="text" placeholder="Company name">
					</div>
				</div>
				<div class="tv-form__row">
					<div class="tv-form__field">
						<label class="tv-form__label" for="tv-phone">Phone</label>
						<input class="tv-form__control tv-form__input" id="tv-phone" name="phone" type="tel" placeholder="(607) 000-0000">
					</div>
					<div class="tv-form__field">
						<label class="tv-form__label" for="tv-email">Email</label>
						<input class="tv-form__control tv-form__input" id="tv-email" name="email" type="email" placeholder="you@company.com">
					</div>
				</div>
				<div class="tv-form__field">
					<label class="tv-form__label" for="tv-location">Site location</label>
					<input class="tv-form__control tv-form__input" id="tv-location" name="location" type="text" placeholder="City, State">
				</div>
				<div class="tv-form__field">
					<label class="tv-form__label" for="tv-message">How can we help?</label>
					<textarea class="tv-form__control tv-form__textarea" id="tv-message" name="message" rows="5" placeholder="Tell us about your site and what you'd like to protect…"></textarea>
				</div>
				<button class="tv-form__submit" type="button">Request Free Consultation</button>
				<div class="tv-form__note">We typically respond within one business day.</div>
			</form>
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
