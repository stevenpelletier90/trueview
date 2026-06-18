# True View Watchtower — WordPress theme

A simple two-page brochure theme for **True View Security Solutions** (solar-powered,
Starlink-connected mobile surveillance units serving NY & PA). Built from design
**Concept A · Clarity** — light, airy, trust-first.

The theme itself lives in [`trueview-watchtower/`](trueview-watchtower/) — that's the
folder you install into WordPress. The linting/PHP tooling sits at the repo root.

```text
trueview-watchtower/
├── style.css            WordPress theme header (no rules — styles live in assets/css/)
├── functions.php        Enqueues, theme supports, Contact-URL helper
├── header.php           <head> + sticky header (nav adapts per page)
├── footer.php           Footer + wp_footer()
├── front-page.php       Home page (renders at the site root automatically)
├── page-contact.php     Contact page (template "Contact"; binds to slug "contact")
├── page.php             Generic page fallback
├── index.php            Required catch-all fallback
└── assets/
    ├── css/             base · header · footer · home · contact (tokenized; no inline styles)
    └── (your 4 brand images — see assets/_IMAGES-README.txt)
```

## Install

1. **Copy the theme** — put the `trueview-watchtower/` folder into
   `wp-content/themes/` on your site (zip it and upload via
   _Appearance → Themes → Add New → Upload Theme_, or copy it over SFTP).
2. **Activate** it under _Appearance → Themes_.
3. **Add your images** — copy these four files into
   `trueview-watchtower/assets/` (they were too large to bundle automatically;
   see [`assets/_IMAGES-README.txt`](trueview-watchtower/assets/_IMAGES-README.txt)):
   `logo.png`, `unit.png`, `tw-24.jpg`, `tw-28.jpg`.
4. **Create the Contact page** — _Pages → Add New_, title it **Contact**, make
   sure its slug is `contact`, then **Publish**. (The page-template is picked up
   automatically from the slug; you can also set _Page Attributes → Template →
   Contact_ to be explicit.)

That's it. The **Home** page is driven by `front-page.php` and shows at your
site root automatically — no _Settings → Reading_ change is required. The header
"Free Consultation" / "Locations" buttons and all the call-to-action buttons
resolve to the Contact page you created.

## Styling

There are **no inline styles**. All CSS lives in `trueview-watchtower/assets/css/`:

- `base.css` — design tokens (`:root` custom properties for the colour palette,
  fonts, layout) + resets + shared primitives (`.tv-btn`, `.tv-pill`, `.tv-eyebrow`,
  `.tv-h2`, …).
- `header.css`, `footer.css` — the site header and footer.
- `home.css`, `contact.css` — the two page layouts.

Stylesheets are enqueued from `functions.php` (`trueview_assets()`): base/header/footer
load everywhere; `home.css` only on the Home page and `contact.css` only on the Contact
page. `style.css` carries just the WordPress theme header. Markup uses semantic `tv-`
BEM-style classes.

## Notes

- **Contact form is static for now.** The fields render exactly as designed, but
  the submit button does nothing yet. Field `name` attributes are already in
  place, so wiring it up later is easy — either:
  - drop a form plugin's shortcode (Contact Form 7 / WPForms / Fluent Forms)
    into the form container in `page-contact.php`, or
  - post the form to `admin-post.php` and send it with `wp_mail()`.
- **Fonts** (Space Grotesk, IBM Plex Sans, IBM Plex Mono) load from Google Fonts
  via `functions.php`. If you'd rather self-host them for privacy/performance,
  download them into `assets/fonts/` and swap the `wp_enqueue_style` URL.
- **Phone / email / addresses** are hardcoded in the templates and footer
  (607-600-8065 · `info@trueviewny.com` · the Binghamton, NY and Susquehanna, PA
  locations). Edit them directly in `header.php`, `footer.php`, `front-page.php`,
  and `page-contact.php`.

## Development

Dev tooling lives at the repo root and operates on `trueview-watchtower/`.
Requires PHP 8+, Composer, and Node 24+.

```sh
composer install   # PHPCS + WordPress Coding Standards + PHPStan
npm install        # ESLint, Prettier, Stylelint, markdownlint, Husky hooks
```

Common commands:

```sh
npm run validate          # every check: CSS, JS, PHPCS, syntax, PHPStan, Prettier, MD, normalize
npm run lint:php          # PHPCS (WordPress standard)
npm run lint:php:fix      # phpcbf auto-fix
npm run phpstan           # static analysis (level 8 + strict rules)
npm run lint:css          # Stylelint
npm run lint:css:fix      # Stylelint auto-fix
npm run format            # Prettier --write
php tools/render-check.php # render templates headless; assert structure + zero inline styles
```

A Husky `pre-commit` hook runs **lint-staged** (phpcbf + phpcs, stylelint, prettier,
markdownlint) against staged files.

## Other concepts

The source design also includes Concept **B · Command** (bold/navy/image-led) and
**C · Monitor** (tech/surveillance/live). This theme implements **A · Clarity**.
Switching to B or C would mean re-deriving the templates from those pages.
