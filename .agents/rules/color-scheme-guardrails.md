# Frontend Color Scheme & Dark Mode Safety Guardrails

When building, styling, or auditing web pages:

1. **Explicit Color Scheme Declaration**:
   - For single-theme / light-brand sites, always include `<meta name="color-scheme" content="light">` in the `<head>` of every HTML page.
   - Always declare `color-scheme: light;` on `:root`, `html`, `body`, and base form controls (`input, textarea, select, button`) in CSS.

2. **Form Control & Interactive Element Theming**:
   - Never rely on browser/OS defaults for form controls.
   - Explicitly define `background-color`, `color`, and `border` on `input`, `textarea`, `select`, `option`, and `button` selectors.
   - Ensure placeholder text has explicit styling (`::placeholder { color: ...; opacity: 1; }`).

3. **CSS Variable Integrity**:
   - Audit all `var(--...)` usages to ensure every referenced variable exists in `:root`.
   - Never leave CSS variables undefined, as browsers fall back to user-agent default colors which break in OS Dark Mode.
