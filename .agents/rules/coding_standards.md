# Wedding Invitation Project - Coding Standards

1. **No Inline CSS & Scripts**: All scripts and styles must be external files in `resources/js` and `resources/css`. Never put `<style>` or `<script>` inside Blade templates. Each template has matching `.blade.php`, `.css`, and `.js` files.
2. **Glassmorphism UI (Anti-AI Slop)**: Sophisticated, tasteful frosted glass aesthetics (`backdrop-blur`, subtle borders, warm boutique palettes like terracotta/cream/earth-tone). Avoid generic AI slop styles.
3. **Clean Code & Maintainability**: Clean separation of concerns, reusable Blade components (`<x-admin...>`, `<x-ui...>`), modular controller & service structure.
4. **Local Libraries Only (Strict NO CDN)**: All CSS/JS packages, fonts, and icons must be installed locally via npm/composer/Vite. Never use unpkg/cdnjs/Google CDN URLs in HTML.
5. **Multi-Template Architecture**: Support multiple invitation design templates driven dynamically by the database catalog, with isolated CSS and JS assets per template.
