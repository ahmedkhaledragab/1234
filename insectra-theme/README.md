# Insectra — Pest Control & Disinfection WordPress Theme

A modern, conversion-focused WordPress theme inspired by the Insectra design (kodesolution.com). Built for **Elementor**, fully editable, and supports **Arabic / English** with RTL.

## Features

- **Elementor-ready** with `add_theme_support('elementor')`, full Theme Builder location support (header / footer / archive / single / before-content / after-content) for Elementor Pro.
- **Bilingual & RTL** out of the box — auto-loads `rtl.css` for Arabic; ships with `.pot` template and Arabic `.po`. Compatible with **Polylang** and **WPML**.
- **Customizer-driven** — colors, contact info, hero content, social links, header CTA, footer text — no code edits required.
- **Custom Post Types**: Services, Team, Testimonials, Pricing Plans (with simple meta fields).
- **Pre-built homepage sections**: Hero, Features, About, Services, Counter, Pricing, Team, Testimonials, Blog, Contact.
- **Performance**: lightweight CSS/JS, no jQuery dependency, intersection-observer counters, no bloat.
- **Accessible**: skip link, focus styles, semantic landmarks.

## Installation

1. Zip the `insectra-theme/` folder → `insectra.zip`.
2. WordPress → Appearance → Themes → Add New → Upload Theme → choose `insectra.zip`.
3. Activate.
4. Recommended plugins (the theme will prompt you):
    - **Elementor** (free) — page building.
    - **Contact Form 7** — production contact form.
    - **Polylang** *or* **WPML** — multilingual.

## Configure (no code)

Appearance → Customize:

- **Insectra: Brand Colors** — primary, dark, accent.
- **Insectra: Contact Info** — phone, email, address, hours.
- **Insectra: Header CTA** — label and target URL.
- **Insectra: Homepage Hero** — eyebrow, title, subtitle, image.
- **Insectra: Social Links** — Facebook, Twitter, Instagram, LinkedIn, YouTube, WhatsApp.
- **Insectra: Footer** — about text & footer CTA headline.

Menus → assign menus to **Primary**, **Footer**, **Mobile**.

## Arabic / RTL

- WordPress → Settings → General → Site Language → **العربية**.
- The theme automatically loads `rtl.css` and switches typography to **Cairo / Tajawal**.
- For per-string translation use the bundled `languages/ar.po` (compile to `ar.mo` via `msgfmt` or Loco Translate).
- For multilingual sites use **Polylang** or **WPML** — the topbar language switcher renders automatically.

## Editing the homepage with Elementor

1. Pages → Add New → "Home" → Template: **Elementor Canvas** or **Elementor Header/Footer** → Edit with Elementor.
2. Settings → Reading → Front page → select "Home".
3. With Elementor Pro you can also override the global header/footer via Templates → Theme Builder.

If no Elementor edits exist, the theme falls back to `front-page.php` which renders all 10 prebuilt sections from `template-parts/sections/`.

## Custom post types

| Post Type        | Slug              | Fields                                         |
|------------------|-------------------|------------------------------------------------|
| Services         | `ins_service`     | Icon (FA class), short description, content    |
| Team             | `ins_team`        | Position, social links                         |
| Testimonials     | `ins_testimonial` | Author role, rating 1-5                        |
| Pricing Plans    | `ins_pricing`     | Price, currency, period, features, CTA, popular|

All CPTs are Elementor-enabled and exposed to the REST API for headless use.

## File structure

```
insectra-theme/
├── style.css                # Theme metadata
├── rtl.css                  # RTL overrides
├── theme.json               # Editor color palette / typography
├── functions.php            # Bootstrap, supports, enqueue
├── header.php / footer.php
├── front-page.php           # Homepage with prebuilt sections
├── index.php / page.php / single.php / archive.php / search.php / 404.php
├── comments.php / sidebar.php
├── inc/
│   ├── customizer.php
│   ├── cpt.php
│   ├── elementor.php
│   ├── walker-nav.php
│   ├── template-tags.php
│   └── tgm/tgm-init.php
├── template-parts/
│   ├── content-card.php
│   └── sections/
│       ├── hero.php  features.php  about.php  services.php
│       ├── counter.php  pricing.php  team.php
│       ├── testimonials.php  blog.php  contact.php
├── languages/
│   ├── insectra.pot
│   └── ar.po
└── assets/
    ├── css/main.css
    └── js/main.js
```

## License

GPLv2 or later.
