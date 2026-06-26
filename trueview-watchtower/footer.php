<?php
/**
 * Footer — renders the site footer and closes the document.
 *
 * @package trueview-watchtower
 */

?>
</main>

<footer class="tv-footer">
	<div class="tv-container">
		<div class="tv-footer__top">
			<div class="tv-footer__brand">
				<div class="tv-footer__logo"><img class="tv-footer__logo-img" src="<?php echo esc_url( trueview_asset( 'logo.png' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"></div>
				<p class="tv-footer__desc">Mobile surveillance units for construction sites and remote locations across New York &amp; Pennsylvania.</p>
			</div>
			<div class="tv-footer__col">
				<div class="tv-footer__label">CONTACT</div>
				<div class="tv-footer__list">
					<a class="tv-footer__phone" href="tel:6076008065">607-600-8065</a>
					<a class="tv-footer__email" href="mailto:info@trueviewny.com">info@trueviewny.com</a>
					<span class="tv-footer__muted">trueviewwatchtower.com</span>
				</div>
			</div>
			<div class="tv-footer__col">
				<div class="tv-footer__label">LOCATIONS</div>
				<div class="tv-footer__list tv-footer__list--addr">
					<span>221 Wilson Hill Road<br>Binghamton, NY 13905</span>
					<span>31596 PA-171<br>Susquehanna, PA 18847</span>
				</div>
			</div>
		</div>
		<div class="tv-footer__copy">&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>. All rights reserved.</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
