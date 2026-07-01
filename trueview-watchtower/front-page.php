<?php
/**
 * Front page (Home) — Concept A · Clarity.
 *
 * @package trueview-watchtower
 */

get_header();

$trueview_contact = trueview_contact_url();
?>

<section class="tv-hero">
	<div class="tv-hero__inner">
		<div class="tv-hero__content">
			<div class="tv-eyebrow tv-eyebrow--lg tv-hero__eyebrow">Mobile Surveillance Units · Northeast &amp; New England</div>
			<h1 class="tv-hero__title">Site security that sets up in&nbsp;minutes.</h1>
			<p class="tv-hero__lead">True View Watchtower deploys solar-powered, Starlink-connected surveillance units that deter theft, detect motion, and keep your projects moving — monitored 24/7, on grid or off.</p>
			<div class="tv-hero__actions">
				<a class="tv-btn tv-btn--primary" href="<?php echo esc_url( $trueview_contact ); ?>">Free Security Consultation</a>
				<a class="tv-btn tv-btn--ghost" href="tel:6076008065">Call or Text · 607-600-8065</a>
			</div>
		</div>
		<div class="tv-hero__media">
			<div class="tv-hero__frame">
				<img class="tv-hero__img" src="<?php echo esc_url( trueview_asset( 'tw-24.jpg' ) ); ?>" alt="True View Watchtower deployed on site">
				<div class="tv-hero__scrim"></div>
			</div>
			<div class="tv-hero__badge">
				<span class="tv-hero__pulse"></span>
				<div class="tv-hero__badge-text">
					<div class="tv-hero__badge-title">Live &amp; recording</div>
					<div class="tv-hero__badge-sub">Motion detection active</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="tv-trust">
	<div class="tv-trust__inner">
		<span class="tv-pill"><svg class="tv-pill__check" width="15" height="15" viewBox="0 0 24 24"><polyline points="5 12 10 17 19 7"></polyline></svg>Locally Owned &amp; Serviced</span>
		<span class="tv-pill"><svg class="tv-pill__check" width="15" height="15" viewBox="0 0 24 24"><polyline points="5 12 10 17 19 7"></polyline></svg>NDAA Compliant</span>
		<span class="tv-pill"><svg class="tv-pill__check" width="15" height="15" viewBox="0 0 24 24"><polyline points="5 12 10 17 19 7"></polyline></svg>Solar &amp; Generator Powered</span>
		<span class="tv-pill"><svg class="tv-pill__check" width="15" height="15" viewBox="0 0 24 24"><polyline points="5 12 10 17 19 7"></polyline></svg>24/7 Surveillance</span>
		<span class="tv-pill"><svg class="tv-pill__check" width="15" height="15" viewBox="0 0 24 24"><polyline points="5 12 10 17 19 7"></polyline></svg>Customized Solutions</span>
	</div>
</section>

<section class="tv-section" id="why">
	<div class="tv-container">
		<div class="tv-why__head">
			<div class="tv-eyebrow">Why Watchtower</div>
			<h2 class="tv-h2">Built for sites that traditional security leaves exposed.</h2>
		</div>
		<div class="tv-why__grid">
			<div class="tv-why__card">
				<div class="tv-why__num">01</div>
				<div class="tv-why__title">Starlink Satellite Connected</div>
				<div class="tv-why__desc">A high-speed satellite link keeps every unit online 24/7 — plus long-range outdoor Wi-Fi where cell towers can't reach.</div>
			</div>
			<div class="tv-why__card">
				<div class="tv-why__num">02</div>
				<div class="tv-why__title">Off-Grid Operation</div>
				<div class="tv-why__desc">Solar panels, battery backup, and a smart generator keep units running with no utility power and secure remote access.</div>
			</div>
			<div class="tv-why__card">
				<div class="tv-why__num">03</div>
				<div class="tv-why__title">Rapid Deployment</div>
				<div class="tv-why__desc">Locally owned and operated — fast deployment, responsive support, and 24-hour service when you need it.</div>
			</div>
			<div class="tv-why__card">
				<div class="tv-why__num">04</div>
				<div class="tv-why__title">Custom Solutions</div>
				<div class="tv-why__desc">PTZ cameras, license-plate recognition, AI detection, two-way audio, Wi-Fi hotspot — configured for your site.</div>
			</div>
			<div class="tv-why__card">
				<div class="tv-why__num">05</div>
				<div class="tv-why__title">Custom Appearance</div>
				<div class="tv-why__desc">Branded wraps or a visible law-enforcement-style deterrent to fit your site and stop trouble before it starts.</div>
			</div>
			<div class="tv-why__card">
				<div class="tv-why__num">06</div>
				<div class="tv-why__title">Wide-Area Coverage</div>
				<div class="tv-why__desc">Radar and multi-camera configurations secure entry points, perimeters, and large jobsite areas.</div>
			</div>
		</div>
	</div>
</section>

<section class="tv-section tv-section--dark tv-compare" id="compare">
	<div class="tv-container">
		<div class="tv-compare__head">
			<div class="tv-eyebrow tv-eyebrow--bright tv-eyebrow--accent">The Difference</div>
			<h2 class="tv-h2 tv-compare__title">True View Watchtower vs. traditional security.</h2>
			<p class="tv-compare__lead">One rapid-deploy unit does the work of guards and fixed cameras &mdash; without the gaps.</p>
		</div>
		<div class="tv-compare__scroll">
			<table class="tv-compare__table">
				<caption class="tv-visually-hidden">How True View Watchtower compares to traditional security approaches.</caption>
				<thead>
					<tr>
						<th scope="col">Capability</th>
						<th scope="col" class="tv-compare__col-us">True View Watchtower</th>
						<th scope="col">Traditional guards &amp; fixed cameras</th>
					</tr>
				</thead>
				<tbody>
					<tr><th scope="row">Setup time</th><td class="tv-compare__us">Minutes</td><td class="tv-compare__them">Days&ndash;weeks</td></tr>
					<tr><th scope="row">Off-grid &amp; solar powered</th><td class="tv-compare__us">Yes</td><td class="tv-compare__them">No</td></tr>
					<tr><th scope="row">24/7 live monitoring</th><td class="tv-compare__us">Yes</td><td class="tv-compare__them">Limited</td></tr>
					<tr><th scope="row">Relocatable on demand</th><td class="tv-compare__us">Yes</td><td class="tv-compare__them">No</td></tr>
					<tr><th scope="row">Wide-area radar &amp; multi-camera</th><td class="tv-compare__us">Yes</td><td class="tv-compare__them">Single view</td></tr>
					<tr><th scope="row">NDAA-compliant equipment</th><td class="tv-compare__us">Yes</td><td class="tv-compare__them">Varies</td></tr>
				</tbody>
			</table>
		</div>
	</div>
</section>

<section class="tv-section tv-section--alt" id="unit">
	<div class="tv-unit__inner">
		<div class="tv-unit__media">
			<img class="tv-unit__img" src="<?php echo esc_url( trueview_asset( 'unit.png' ) ); ?>" alt="True View Watchtower mobile surveillance unit">
		</div>
		<div class="tv-unit__content">
			<div class="tv-eyebrow">The Unit</div>
			<h2 class="tv-h2 tv-unit__title">One trailer. Total coverage.</h2>
			<p class="tv-unit__lead">Each True View Watchtower unit is configured for your specific site and ready to deter from the moment it arrives.</p>
			<div class="tv-unit__list">
				<div class="tv-unit__item"><span class="tv-unit__bullet"></span><span class="tv-unit__text">Pan-tilt-zoom &amp; multi-camera radar coverage</span></div>
				<div class="tv-unit__item"><span class="tv-unit__bullet"></span><span class="tv-unit__text">AI detection, license-plate recognition &amp; two-way audio</span></div>
				<div class="tv-unit__item"><span class="tv-unit__bullet"></span><span class="tv-unit__text">Custom wraps or law-enforcement-style deterrent</span></div>
				<div class="tv-unit__item"><span class="tv-unit__bullet"></span><span class="tv-unit__text">Secure remote access from anywhere</span></div>
			</div>
		</div>
	</div>
</section>

<section class="tv-coverage" id="coverage">
	<img class="tv-coverage__img" src="<?php echo esc_url( trueview_asset( 'tw-28.jpg' ) ); ?>" alt="True View Watchtower on an active job site">
	<div class="tv-coverage__scrim"></div>
	<div class="tv-coverage__overlay">
		<div class="tv-coverage__inner">
			<div class="tv-coverage__content">
				<div class="tv-eyebrow tv-eyebrow--bright tv-coverage__eyebrow">Wide-Area Coverage</div>
				<h2 class="tv-coverage__title">Deployed across active job sites.</h2>
				<p class="tv-coverage__lead">From single remote locations to sprawling multi-acre sites, one unit safeguards equipment, entry points, and perimeters — day and night.</p>
			</div>
		</div>
	</div>
</section>

<section class="tv-stats tv-stats--dark">
	<div class="tv-stats__grid">
		<div class="tv-stats__item">
			<div class="tv-stats__num">24/7</div>
			<div class="tv-stats__label">Real-Time Monitoring</div>
		</div>
		<div class="tv-stats__item">
			<div class="tv-stats__num tv-stats__num--word">Nationwide</div>
			<div class="tv-stats__label">Deployment &amp; Shipping</div>
		</div>
		<div class="tv-stats__item">
			<div class="tv-stats__num">100%</div>
			<div class="tv-stats__label">Off-Grid Capable</div>
		</div>
		<div class="tv-stats__item">
			<div class="tv-stats__num">NDAA</div>
			<div class="tv-stats__label">Compliant Equipment</div>
		</div>
	</div>
</section>

<section class="tv-section tv-section--alt">
	<div class="tv-container">
		<h2 class="tv-h2 tv-locations__title">Based in the Northeast &amp; New England &mdash; available nationwide</h2>
		<div class="tv-locations__grid">
			<div class="tv-loc-card">
				<div class="tv-loc-card__region">NEW YORK</div>
				<div class="tv-loc-card__city">Binghamton, NY</div>
				<div class="tv-loc-card__addr">221 Wilson Hill Road, Binghamton, NY 13905</div>
				<div class="tv-loc-card__hours"><span>Mon–Fri · 8AM–5PM</span><span class="tv-loc-card__emerg">24/7 Emergency</span></div>
			</div>
			<div class="tv-loc-card">
				<div class="tv-loc-card__region">PENNSYLVANIA</div>
				<div class="tv-loc-card__city">Susquehanna, PA</div>
				<div class="tv-loc-card__addr">31596 PA-171, Susquehanna, PA 18847</div>
				<div class="tv-loc-card__hours"><span>Mon–Fri · 8AM–5PM</span><span class="tv-loc-card__emerg">24/7 Emergency</span></div>
			</div>
		</div>
	</div>
</section>

<section class="tv-cta">
	<div class="tv-cta__inner">
		<h2 class="tv-cta__title">Get a free security consultation</h2>
		<p class="tv-cta__lead">24-hour service available. We'll assess your site and recommend the right configuration.</p>
		<div class="tv-cta__actions">
			<a class="tv-btn tv-btn--primary" href="<?php echo esc_url( $trueview_contact ); ?>">Request Consultation</a>
			<a class="tv-cta__phone" href="tel:6076008065">607-600-8065</a>
		</div>
	</div>
</section>

<?php get_footer(); ?>
