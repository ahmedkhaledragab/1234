# Insectra translations

This folder ships with:
- `insectra.pot` — translation template for translators / Loco Translate.
- `ar.po` — Arabic translation source.

## Generating .mo files

WordPress requires compiled `.mo` files. Run:

```bash
msgfmt ar.po -o ar.mo        # generic Arabic
msgfmt ar.po -o ar_SA.mo     # Saudi Arabic
msgfmt ar.po -o ar_AR.mo     # alternate Arabic locale
```

Or use the free [Loco Translate](https://wordpress.org/plugins/loco-translate/) plugin to compile/edit
inside WP admin. After compiling, place the `.mo` files in this folder.

## Switching language

- **WordPress core only:** Settings → General → Site Language → العربية. RTL is loaded automatically (rtl.css).
- **Polylang:** create the languages, assign translations per page/post, the topbar `lang-switcher` reflects them automatically.
- **WPML:** add languages in WPML → Languages, the topbar switcher will appear automatically.

## RTL

`rtl.css` is loaded automatically when WordPress detects an RTL locale (`is_rtl()`).
