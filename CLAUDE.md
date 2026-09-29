# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this
repository.

## What this repo is

A classic (non-block) WordPress **brochure theme** — two pages, Home + Contact — for True View
Security Solutions (solar-powered, Starlink-connected mobile surveillance units). Marketing copy,
hardcoded contact details, no CMS content.

The repo is **two things stacked in one folder**:

- **`trueview-watchtower/`** — the shippable theme (the only thing that installs into
  `wp-content/themes/`). Everything else is scaffolding around it.
- **repo root** — dev tooling (lint/static-analysis/format configs, render harnesses, Composer/npm).
  None of it ships.

This split drives everything: the phpcs ruleset, every npm lint glob, and Prettier all target
`trueview-watchtower/**` only. When adding theme code, put it under `trueview-watchtower/`; when
adding tooling, keep it at root and exclude it from the theme scope (see `phpcs.xml`'s `tools/`
exclusion for the pattern).

## Commands

Requires PHP 8+, Composer, Node 24+ (`.nvmrc`). Install: `composer install && npm install`.

```sh
npm run validate            # ALL checks concurrently: css, js, php(cs), php syntax, phpstan, prettier, md, composer-normalize
php tools/render-check.php   # render templates headless + assert structure and ZERO inline styles (the closest thing to a test)
npm run lint:php            # PHPCS (WordPress Coding Standards)
npm run lint:php:fix        # phpcbf auto-fix
npm run phpstan             # PHPStan level 8 + strict + WP stubs
npm run lint:css / :css:fix # Stylelint (recess-order)
npm run format              # Prettier --write .
```

Run `php tools/render-check.php` after any change to templates, `functions.php`, or asset filenames
— it stubs WordPress, executes the templates, and fails the build if a template emits `style=`, a
broken asset path, or a PHP warning. There is no PHPUnit suite; this harness is the test.

There are no git hooks — nothing gates a commit or push. Run `npm run validate` yourself before
pushing (`npm run lint:php:fix` / `lint:css:fix` to auto-fix).

This repo defines one Claude Code hook: a PostToolUse formatter (`scripts/claude-format-hook.js`,
wired in `.claude/settings.json`) that auto-fixes files Claude edits — stylelint/eslint/
phpcbf/markdownlint + prettier by extension. Files Claude edits never pass through an editor, so
format-on-save can't catch them; this does. It always exits 0 and never blocks. It formats only —
`validate` is still the real check.

**Navigating the code.** The harness `LSP` tool works here (`goToDefinition` / `findReferences` /
`workspaceSymbol` for the `trueview*` functions and hooks). The theme is only ~10 PHP files, though,
so grep is usually just as quick — reach for `LSP` mainly to trace a shared helper (e.g.
`trueview_contact_url()`) across templates. PHP LSP is the user-level `php-lsp-win` skill; if it
stops resolving, check that fnm's default alias still points at a `node.exe`.

## Architecture

**Styling is CSS-only, never inline — this is a hard invariant.** `style.css` carries the WordPress
theme header and nothing else. All rules live in `assets/css/`: `base.css` (design tokens as `:root`
custom properties + resets + shared `tv-` primitives), `header.css`, `footer.css`, `home.css`,
`contact.css`. `functions.php` → `trueview_assets()` enqueues base/header/footer everywhere and
conditionally loads `home.css` on the front page and `contact.css` on the contact page. To style
anything, add/extend a CSS file and (if a new sheet) enqueue it — do **not** add a `style=`
attribute; `render-check.php` asserts its absence and will fail.

**Page-aware header.** `header.php` renders one nav that adapts by context: on the front page the
section links (`#unit`, `#why`, `#coverage`) are same-page anchors and the CTA is "Free
Consultation"; elsewhere they become `home_url() . '#anchor'` and the CTA collapses to an active
"Contact" link. `trueview_contact_url()` resolves the Contact target through a 3-tier fallback (page
using `page-contact.php` template → page with slug `contact` → `/contact/`) so buttons always have a
destination even before the Contact page exists.

**Template map.** `front-page.php` (Home, auto-renders at site root), `page-contact.php` (binds to
the `contact` slug / Contact template), `page.php` (generic fallback), `index.php` (required
catch-all). `header.php`/`footer.php` are the shared parts.

**Naming conventions** (enforced by `phpcs.xml`): every PHP function, hook, and global is prefixed
`trueview` (`WordPress.NamingConventions.PrefixAllGlobals`); text domain is `trueview-watchtower`;
CSS classes are `tv-` BEM (`tv-btn`, `tv-nav__cta`, `tv-header__inner`). PHP compatibility is
checked against 7.4–8.5 even though local dev is PHP 8+.

## Design direction & source

Current implemented direction is **Concept B "Command" — dark, navy, image-led** (see
`docs/superpowers/specs/2026-07-01-home-revisions-design.md`, Status: Implemented, and the
`style.css` header). Palette + white-pop system are documented in that spec.

The design was derived from **`True View Watchtower - Concepts (standalone).html`** (a ~4.8 MB
standalone mockup holding all three concepts A/B/C). It's the design of record — re-deriving a
template's structure means reading the relevant concept in that file.

That file was exported from the Claude Design project "Website design for two pages" (projectId
`a9221f29-9d49-4135-82e8-50c4f262743a`, reachable through the DesignSync tool): overview
`True View Watchtower - Concepts.dc.html`, pages `Watchtower {A,B,C} - {Home,Contact}.dc.html`. The
`.dc.html` format wraps markup in `<x-dc><helmet>…</helmet>…</x-dc>` plus a `support.js` script.
Strip the wrapper; the usable HTML/CSS is inside.

## Two render harnesses (don't confuse them)

- `tools/render-check.php` — **tracked, CI-style assertions.** Stubs WP, renders Home + Contact,
  prints PASS/FAIL, exits non-zero on failure. Run it to verify.
- `_preview_render.php` + `_preview*.html` — **gitignored, dev-only.** Emits real HTML to a browser
  so the theme CSS can be screenshotted (used with Playwright/Chrome-DevTools). Not shipped, not
  asserted.

## Not part of the theme

- `trueview-watchtower.zip` — build artifact (gitignored); the theme folder zipped for upload.
- `trueview-mcp.php` — tracked at repo root but unrelated to the theme. It's a WordPress
  MCP-abilities plugin, hand-deployed to `mu-plugins`; not linted, not part of the theme build.
  Don't fold it into theme changes. Only this one file may sit in `mu-plugins` (its header names the
  older files that would double-register). After a deploy, reconnect the `trueview-wp` MCP so it
  re-discovers abilities. The MCP talks to the live install: `delete-media` is permanent (no
  `MEDIA_TRASH`). Check edits with `php -l trueview-mcp.php` only; `phpcbf` would reformat the
  file's compact style.
- Brand images (`logo.png`, `unit.png`, `tw-24.jpg`, `tw-28.jpg`, `coverage.jpg`, `hero.jpg`) are
  large binaries the design connector couldn't auto-bundle (256 KiB cap) — dropped in by hand; see
  `assets/_IMAGES-README.txt`.
