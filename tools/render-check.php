<?php
/**
 * Render harness — stubs the WordPress functions the theme uses, then actually
 * executes the templates to confirm they render without errors and that the
 * page-aware header behaves. Run:  php tools/render-check.php
 *
 * Dev tooling, intentionally outside the WPCS scope (phpcs.xml excludes tools/).
 */
error_reporting( E_ALL );
ini_set( 'display_errors', '1' );

define( 'ABSPATH', __DIR__ . '/' ); // satisfy functions.php direct-access guard
$THEME            = dirname( __DIR__ ) . '/trueview-watchtower';
$GLOBALS['THEME'] = $THEME;

// --- WP function stubs -------------------------------------------------------
$GLOBALS['__is_front'] = true;

function add_action( ...$a ) {}
function add_theme_support( ...$a ) {}
function is_front_page() { return $GLOBALS['__is_front']; }
function language_attributes() { echo 'lang="en-US"'; }
function bloginfo( $k ) { echo $k === 'charset' ? 'UTF-8' : ''; }
function get_bloginfo( $k = 'name' ) { return 'True View Security Solutions'; }
function wp_head() { echo "\n<!-- wp_head -->\n"; }
function wp_body_open() {}
function body_class( $c = '' ) { echo 'class="home page"'; }
function wp_footer() { echo "\n<!-- wp_footer -->\n"; }
function home_url( $p = '' ) { return 'http://localhost/' . ltrim( $p, '/' ); }
function get_stylesheet_uri() { return 'http://localhost/wp-content/themes/trueview-watchtower/style.css'; }
function get_template_directory_uri() { return 'http://localhost/wp-content/themes/trueview-watchtower'; }
function get_pages( $a = array() ) { return array(); }   // force slug/fallback path
function get_page_by_path( $p ) { return false; }        // force final fallback
function get_permalink( $id ) { return 'http://localhost/contact/'; }
function wp_get_theme() { return new class { public function get( $k ) { return '1.0.0'; } }; }
function esc_url( $u ) { return htmlspecialchars( $u, ENT_QUOTES ); }
function esc_attr( $s ) { return htmlspecialchars( $s, ENT_QUOTES ); }
function esc_html( $s ) { return htmlspecialchars( $s, ENT_QUOTES ); }

// get_header()/get_footer() load the part files, like WordPress does.
function get_header( $n = null ) { require $GLOBALS['THEME'] . '/header.php'; }
function get_footer( $n = null ) { require $GLOBALS['THEME'] . '/footer.php'; }

require $THEME . '/functions.php'; // defines trueview_asset(), trueview_contact_url()

// --- Render Home -------------------------------------------------------------
$GLOBALS['__is_front'] = true;
ob_start();
require $THEME . '/front-page.php';
$home = ob_get_clean();

// --- Render Contact ----------------------------------------------------------
$GLOBALS['__is_front'] = false;
ob_start();
require $THEME . '/page-contact.php';
$contact = ob_get_clean();

// --- Assertions --------------------------------------------------------------
function check( $label, $cond ) {
	echo ( $cond ? 'PASS  ' : 'FAIL  ' ) . $label . "\n";
	return $cond;
}

$ok = true;
echo "--- HOME ---\n";
$ok = check( 'doctype present', strpos( $home, '<!DOCTYPE html>' ) === 0 ) && $ok;
$ok = check( 'has hero headline', strpos( $home, 'sets up in' ) !== false ) && $ok;
$ok = check( 'home nav shows Free Consultation button', strpos( $home, '>Free Consultation<' ) !== false ) && $ok;
$ok = check( 'home nav shows Locations', strpos( $home, '>Locations<' ) !== false ) && $ok;
$ok = check( 'home anchors are same-page (#unit)', strpos( $home, 'href="#unit"' ) !== false ) && $ok;
$ok = check( 'home nav uses CTA class', strpos( $home, 'tv-nav__cta' ) !== false ) && $ok;
$ok = check( 'home has NO inline style= attributes', strpos( $home, 'style=' ) === false ) && $ok;
$ok = check( 'hero img -> assets/tw-24.jpg', strpos( $home, '/assets/tw-24.jpg' ) !== false ) && $ok;
$ok = check( 'unit img -> assets/unit.png', strpos( $home, '/assets/unit.png' ) !== false ) && $ok;
$ok = check( 'coverage img -> assets/tw-28.jpg', strpos( $home, '/assets/tw-28.jpg' ) !== false ) && $ok;
$ok = check( 'logo img -> assets/logo.png', strpos( $home, '/assets/logo.png' ) !== false ) && $ok;
$ok = check( 'CTA buttons -> contact url', strpos( $home, 'href="http://localhost/contact/"' ) !== false ) && $ok;
$ok = check( 'footer present', strpos( $home, 'All rights reserved' ) !== false ) && $ok;
$ok = check( 'no Fatal/Warning/Notice', strpos( $home, 'Fatal error' ) === false && strpos( $home, 'Warning:' ) === false && strpos( $home, 'Notice:' ) === false ) && $ok;

echo "--- CONTACT ---\n";
$ok = check( 'doctype present', strpos( $contact, '<!DOCTYPE html>' ) === 0 ) && $ok;
$ok = check( 'has contact headline', strpos( $contact, 'talk about your site' ) !== false ) && $ok;
$ok = check( 'contact nav shows Contact link', strpos( $contact, '>Contact<' ) !== false ) && $ok;
$ok = check( 'contact nav does NOT use the CTA class', strpos( $contact, 'tv-nav__cta' ) === false ) && $ok;
$ok = check( 'contact has NO inline style= attributes', strpos( $contact, 'style=' ) === false ) && $ok;
$ok = check( 'contact anchors point back to home', strpos( $contact, 'href="http://localhost/#unit"' ) !== false ) && $ok;
$ok = check( 'form has name fields', strpos( $contact, 'name="email"' ) !== false && strpos( $contact, 'name="message"' ) !== false ) && $ok;
$ok = check( 'submit button is inert (type=button)', strpos( $contact, 'type="button"' ) !== false ) && $ok;
$ok = check( 'no Fatal/Warning/Notice', strpos( $contact, 'Fatal error' ) === false && strpos( $contact, 'Warning:' ) === false && strpos( $contact, 'Notice:' ) === false ) && $ok;

echo "\n" . ( $ok ? '==> ALL CHECKS PASSED' : '==> SOME CHECKS FAILED' ) . "\n";
exit( $ok ? 0 : 1 );
