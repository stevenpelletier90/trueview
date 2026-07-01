# TrueView Watchtower — Dark Redesign (Design Spec)

- **Date:** 2026-07-01
- **Status:** Implemented
- **Direction:** Concept B "Command" (dark navy, image-led) applied site-wide, with deliberate white elements for contrast so the page is not monotone.

## Client feedback driving this

1. Go with Concept B (dark) as the whole direction.
2. Keep **some white** so key elements pop — avoid a monotone dark page.
3. Market a **Northeast + New England** focus, **available nationwide** (drop "2 states served").
4. Make **"True View Watchtower"** more prevalent.
5. Add a **Watchtower-vs-traditional comparison chart**.

## Palette (existing tokens — no new colors)

| Token              | Hex       | Use                       |
| ------------------ | --------- | ------------------------- |
| `--tv-navy-dark`   | `#0e2740` | page background           |
| `--tv-navy`        | `#15314f` | alt bands, raised cards   |
| `--tv-cyan-bright` | `#18c6ed` | eyebrows, checks, accents |
| white              | `#fff`    | pop cards, headings       |

## White-pop system (the signature)

White objects on the dark base so the important content stands out:

- Why Watchtower cards, Home + Contact location cards — white cards with soft shadow
- Trust pills and the hero "Live & recording" badge — white
- Logo on a white chip in header + footer (also fixes navy-logo-on-navy)
- Contact form inputs — white
- Comparison: the winning "Watchtower" column is highlighted (cyan-bright header + subtle band)

## Copy changes (geography + brand)

| Where                  | New copy                                                    |
| ---------------------- | ----------------------------------------------------------- |
| Hero eyebrow           | Mobile Surveillance Units · Northeast & New England         |
| Stats tile 2           | Nationwide / Deployment & Shipping                          |
| Home locations H2      | Based in the Northeast & New England — available nationwide |
| Contact "Visit Us" H2  | Two Northeast locations                                     |
| Footer tagline         | …across the Northeast & New England, available nationwide.  |
| Nav link + Why heading | Why Watchtower                                              |
| Hero + Unit copy       | "True View Watchtower" woven in                             |

## New section — comparison chart

"True View Watchtower vs. traditional security", placed after Why, before The Unit. Semantic table with 6 rows (setup time, off-grid, 24/7 monitoring, relocatable, radar + multi-camera, NDAA). Yes/No conveyed by text plus a decorative check — never color or icon alone.

## Files changed

| File                        | Change                                                                                                                           |
| --------------------------- | -------------------------------------------------------------------------------------------------------------------------------- |
| `assets/css/base.css`       | dark body, white headings, cyan-bright eyebrows/links, dark-ready ghost button, `section--dark`, visually-hidden, eyebrow accent |
| `assets/css/header.css`     | dark sticky header, white logo chip, light nav                                                                                   |
| `assets/css/home.css`       | white Why/Location cards, dark hero/unit text, comparison + dark stats                                                           |
| `assets/css/contact.css`    | dark form section, white inputs, dark Visit band, white place cards                                                              |
| `front-page.php`            | geo/brand copy, comparison section, Nationwide stat                                                                              |
| `header.php` / `footer.php` | Why Watchtower nav, footer tagline                                                                                               |
| `page-contact.php`          | "Two Northeast locations"                                                                                                        |

## Accessibility

- Comparison table uses semantic `th`/`scope`; Yes/No carried by text, not color or icon alone.
- Contrast: cyan-bright `#18c6ed` on navy passes AA; white cards keep dark ink text.
- Existing focus states preserved.

## Not done

- `unit.png` (transparent cut-out) — pending Photoshop; the one broken image on Home.
