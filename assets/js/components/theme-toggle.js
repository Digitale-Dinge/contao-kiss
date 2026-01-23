/**
 * Animated Sun/Moon Theme Toggle
 * 
 * A beautiful animated SVG toggle inspired by Josh Comeau's theme switcher.
 * Uses CSS animations for smooth sun ↔ moon morphing.
 * 
 * ─────────────────────────────────────────────────────────────────────────────
 * USAGE
 * ─────────────────────────────────────────────────────────────────────────────
 * 
 * Basic button toggle:
 * 
 *   <button data-controller="theme" data-action="click->theme#toggle" 
 *           class="theme-toggle" aria-label="Toggle dark mode">
 *     <svg class="theme-toggle__icon" viewBox="0 0 24 24" width="24" height="24">
 *       <!-- Sun rays (hidden in dark mode) -->
 *       <g class="theme-toggle__rays">
 *         <line x1="12" y1="1" x2="12" y2="3"></line>
 *         <line x1="12" y1="21" x2="12" y2="23"></line>
 *         <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
 *         <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
 *         <line x1="1" y1="12" x2="3" y2="12"></line>
 *         <line x1="21" y1="12" x2="23" y2="12"></line>
 *         <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
 *         <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
 *       </g>
 *       <!-- Sun/Moon circle with mask for crescent -->
 *       <mask id="moon-mask">
 *         <rect x="0" y="0" width="100%" height="100%" fill="white"/>
 *         <circle class="theme-toggle__mask" cx="24" cy="10" r="6" fill="black"/>
 *       </mask>
 *       <circle class="theme-toggle__circle" cx="12" cy="12" r="5" mask="url(#moon-mask)"/>
 *     </svg>
 *   </button>
 * 
 * ─────────────────────────────────────────────────────────────────────────────
 * CSS (add to your stylesheet)
 * ─────────────────────────────────────────────────────────────────────────────
 * 
 * See the companion SCSS file: _theme-toggle.scss
 */

// This file documents the animated toggle - the actual implementation
// is in the SCSS file below and the HTML/SVG structure above.
// 
// The animation works by:
// 1. Scaling and rotating the entire icon on click
// 2. Fading out the sun rays when switching to dark
// 3. Moving the mask circle to create the moon crescent effect
// 4. All transitions are spring-like using cubic-bezier curves

export const THEME_TOGGLE_SVG = `
<svg class="theme-toggle__icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <g class="theme-toggle__rays">
    <line x1="12" y1="1" x2="12" y2="3"></line>
    <line x1="12" y1="21" x2="12" y2="23"></line>
    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
    <line x1="1" y1="12" x2="3" y2="12"></line>
    <line x1="21" y1="12" x2="23" y2="12"></line>
    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
  </g>
  <mask id="moon-mask">
    <rect x="0" y="0" width="100%" height="100%" fill="white"/>
    <circle class="theme-toggle__mask" cx="24" cy="10" r="6" fill="black"/>
  </mask>
  <circle class="theme-toggle__circle" cx="12" cy="12" r="5" fill="currentColor" stroke="none" mask="url(#moon-mask)"/>
</svg>
`;
