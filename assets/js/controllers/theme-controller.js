/**
 * KISS Theme Controller
 * 
 * A Stimulus controller for theme switching with localStorage persistence,
 * system preference detection, and cross-controller synchronization.
 * 
 * ─────────────────────────────────────────────────────────────────────────────
 * CONFIGURATION
 * ─────────────────────────────────────────────────────────────────────────────
 * 
 * To add new themes, edit the static THEMES array:
 * 
 *   static THEMES = ['light', 'dark', 'high-contrast', 'sepia'];
 * 
 * Also update fe_page.html.twig to match.
 * 
 * ─────────────────────────────────────────────────────────────────────────────
 * MODES vs THEMES
 * ─────────────────────────────────────────────────────────────────────────────
 * 
 * Mode (stored in localStorage):
 *   - 'light'   → Apply light theme (no class on <html>)
 *   - 'dark'    → Apply dark theme (adds .dark class)
 *   - 'system'  → Follow OS preference, resolves to light or dark
 *   - (any theme name) → Apply that theme class
 * 
 * Theme (applied to <html>):
 *   - 'light' = default, no class added
 *   - Any other theme adds its name as a class (e.g. .dark, .sepia)
 * 
 * ─────────────────────────────────────────────────────────────────────────────
 * ACTIONS
 * ─────────────────────────────────────────────────────────────────────────────
 * 
 * toggle()  - Switches between 'light' and 'dark' only.
 *             Use for simple on/off switches and checkboxes.
 *             Note: Clicking exits 'system' mode into explicit light/dark.
 * 
 * set()     - Sets any valid mode from input value.
 *             Use for radios, selects, or buttons with value attribute.
 * 
 * ─────────────────────────────────────────────────────────────────────────────
 * TARGETS
 * ─────────────────────────────────────────────────────────────────────────────
 * 
 * toggle     - Checkbox/switch, syncs checked state with dark mode active
 * radio      - Radio buttons, syncs checked state with current mode
 * select     - Dropdown, syncs value with current mode
 * lightIcon  - Hidden when dark, shown when light
 * darkIcon   - Shown when dark, hidden when light
 * 
 * ─────────────────────────────────────────────────────────────────────────────
 * USAGE EXAMPLES
 * ─────────────────────────────────────────────────────────────────────────────
 * 
 * Simple Toggle (checkbox/switch):
 * Toggles between light ↔ dark. Reflects system state when mode is 'system'.
 * 
 *   <label data-controller="theme">
 *     <input type="checkbox" 
 *            data-theme-target="toggle"
 *            data-action="change->theme#toggle">
 *     <span>Dark Mode</span>
 *   </label>
 * 
 * ─────────────────────────────────────────────────────────────────────────────
 * 
 * Radio Buttons (light / dark / system):
 * Full control with explicit system option.
 * 
 *   <div data-controller="theme">
 *     <label>
 *       <input type="radio" name="theme" value="light" 
 *              data-theme-target="radio" data-action="change->theme#set">
 *       Light
 *     </label>
 *     <label>
 *       <input type="radio" name="theme" value="dark" 
 *              data-theme-target="radio" data-action="change->theme#set">
 *       Dark
 *     </label>
 *     <label>
 *       <input type="radio" name="theme" value="system" 
 *              data-theme-target="radio" data-action="change->theme#set">
 *       System
 *     </label>
 *   </div>
 * 
 * ─────────────────────────────────────────────────────────────────────────────
 * 
 * Dropdown Select:
 * Compact option for settings panels.
 * 
 *   <select data-controller="theme" 
 *           data-theme-target="select" 
 *           data-action="change->theme#set">
 *     <option value="light">☀️ Light</option>
 *     <option value="dark">🌙 Dark</option>
 *     <option value="system">💻 System</option>
 *   </select>
 * 
 * ─────────────────────────────────────────────────────────────────────────────
 * 
 * Icon Toggle (shows sun/moon):
 * 
 *   <button data-controller="theme" data-action="click->theme#toggle">
 *     <svg data-theme-target="lightIcon"><!-- sun icon --></svg>
 *     <svg data-theme-target="darkIcon"><!-- moon icon --></svg>
 *   </button>
 * 
 * ─────────────────────────────────────────────────────────────────────────────
 * EVENTS
 * ─────────────────────────────────────────────────────────────────────────────
 * 
 * 'theme:change' - Dispatched on element and document when theme changes.
 *                  detail: { mode, theme, isDark }
 * 
 * 'theme:changed' - Internal event for cross-controller sync (on window).
 * 
 * ─────────────────────────────────────────────────────────────────────────────
 * FEATURES
 * ─────────────────────────────────────────────────────────────────────────────
 * 
 * ✓ localStorage persistence
 * ✓ System preference detection (prefers-color-scheme)
 * ✓ Real-time OS theme change listener (when mode is 'system')
 * ✓ Cross-controller sync (multiple toggles stay in sync)
 * ✓ Extensible theme system (add themes in one place)
 * ✓ Icon visibility sync
 * 
 */

import { Controller } from '@hotwired/stimulus';

// ─────────────────────────────────────────────────────────────
// System Theme Listener (extracted for clarity)
// ─────────────────────────────────────────────────────────────

class SystemThemeListener {
  constructor(callback) {
    this.mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
    this.callback = callback;
    this.isListening = false;
    // Arrow function preserves 'this' and creates stable reference
    this.handleChange = (event) => {
      this.callback(event.matches);
    };
  }

  start() {
    if (this.isListening) return;
    this.mediaQuery.addEventListener('change', this.handleChange);
    this.isListening = true;
  }

  stop() {
    if (!this.isListening) return;
    this.mediaQuery.removeEventListener('change', this.handleChange);
    this.isListening = false;
  }

  prefersDark() {
    return this.mediaQuery.matches;
  }

  destroy() {
    this.stop();
  }
}

// ─────────────────────────────────────────────────────────────
// Theme Controller
// ─────────────────────────────────────────────────────────────

export default class ThemeController extends Controller {
  // ─────────────────────────────────────────────────────────────
  // Configuration - Add new themes here
  // ─────────────────────────────────────────────────────────────
  
  static THEMES = ['light', 'dark']; // Add: 'high-contrast', 'sepia', etc.
  static VALID_MODES = [...ThemeController.THEMES, 'system'];
  static DEFAULT_THEME = 'light';
  
  static targets = ['toggle', 'radio', 'select', 'lightIcon', 'darkIcon'];
  
  static values = {
    storageKey: { type: String, default: 'theme' },
  };

  connect() {
    // Create system theme listener
    this.systemListener = new SystemThemeListener((prefersDark) => {
      this.onSystemThemeChange(prefersDark);
    });

    // Listen for theme changes from other controllers
    this.onExternalChange = () => this.syncFromExternal();
    window.addEventListener('theme:changed', this.onExternalChange);

    // Apply theme on connect
    this.applyTheme();
    
    // Update listener state (enable if mode is 'system')
    this.updateListenerState();
    
    // Sync UI controls
    this.syncControls();
  }

  disconnect() {
    // Clean up listeners
    if (this.systemListener) {
      this.systemListener.destroy();
    }
    window.removeEventListener('theme:changed', this.onExternalChange);
  }

  // ─────────────────────────────────────────────────────────────
  // Public Actions
  // ─────────────────────────────────────────────────────────────

  /** Toggle between light and dark */
  toggle() {
    const isDark = this.isDarkActive();
    const newMode = isDark ? 'light' : 'dark';
    this.setMode(newMode);
  }

  /** Set a specific mode (from radio/select) */
  set(event) {
    const value = event.target.value;
    if (ThemeController.VALID_MODES.includes(value)) {
      this.setMode(value);
    }
  }

  // ─────────────────────────────────────────────────────────────
  // Getters
  // ─────────────────────────────────────────────────────────────

  get currentTheme() {
    return this.getActiveTheme();
  }

  get currentMode() {
    return this.getStoredMode();
  }

  // ─────────────────────────────────────────────────────────────
  // Core Logic
  // ─────────────────────────────────────────────────────────────

  /** Get stored mode from localStorage */
  getStoredMode() {
    return localStorage.getItem(this.storageKeyValue) || 'system';
  }

  /** Set mode and apply it */
  setMode(mode) {
    // Store preference
    localStorage.setItem(this.storageKeyValue, mode);
    
    // Apply theme
    this.applyTheme();
    
    // Update listener (enable/disable based on mode)
    this.updateListenerState();
    
    // Sync UI
    this.syncControls();
    
    // Dispatch event for other components
    this.dispatchThemeChange();
    
    // Notify other controllers
    window.dispatchEvent(new CustomEvent('theme:changed'));
  }

  /** Sync from external change (another controller) */
  syncFromExternal() {
    this.applyTheme();
    this.updateListenerState();
    this.syncControls();
  }

  /** Apply theme based on current mode */
  applyTheme() {
    const mode = this.getStoredMode();
    let theme;

    if (mode === 'system') {
      // System mode - check OS preference
      theme = this.systemListener.prefersDark() ? 'dark' : ThemeController.DEFAULT_THEME;
    } else if (ThemeController.THEMES.includes(mode)) {
      theme = mode;
    } else {
      theme = ThemeController.DEFAULT_THEME;
    }

    this.setThemeClass(theme);
  }

  /** Check if dark mode is currently active */
  isDarkActive() {
    return document.documentElement.classList.contains('dark');
  }

  /** Get current active theme */
  getActiveTheme() {
    const html = document.documentElement;
    for (const theme of ThemeController.THEMES) {
      if (theme !== ThemeController.DEFAULT_THEME && html.classList.contains(theme)) {
        return theme;
      }
    }
    return ThemeController.DEFAULT_THEME;
  }

  /** Apply theme class to <html> */
  setThemeClass(theme) {
    const html = document.documentElement;
    // Remove all theme classes
    ThemeController.THEMES.forEach(t => {
      if (t !== ThemeController.DEFAULT_THEME) {
        html.classList.remove(t);
      }
    });
    // Add new theme (unless it's the default)
    if (theme !== ThemeController.DEFAULT_THEME) {
      html.classList.add(theme);
    }
  }

  // ─────────────────────────────────────────────────────────────
  // System Listener Management
  // ─────────────────────────────────────────────────────────────

  /** Enable/disable system listener based on mode */
  updateListenerState() {
    const mode = this.getStoredMode();
    
    if (mode === 'system') {
      this.systemListener.start();
    } else {
      this.systemListener.stop();
    }
  }

  /** Called when OS theme changes (only when mode is 'system') */
  onSystemThemeChange(prefersDark) {
    const theme = prefersDark ? 'dark' : ThemeController.DEFAULT_THEME;
    this.setThemeClass(theme);
    this.syncControls();
    this.dispatchThemeChange();
  }

  // ─────────────────────────────────────────────────────────────
  // UI Sync
  // ─────────────────────────────────────────────────────────────

  /** Sync all UI controls to reflect current state */
  syncControls() {
    const mode = this.getStoredMode();
    const isDark = this.isDarkActive();

    // Sync toggle checkboxes
    if (this.hasToggleTarget) {
      this.toggleTargets.forEach((el) => {
        el.checked = isDark;
      });
    }

    // Sync radio buttons
    if (this.hasRadioTarget) {
      this.radioTargets.forEach((el) => {
        el.checked = el.value === mode;
      });
    }

    // Sync select dropdowns
    if (this.hasSelectTarget) {
      this.selectTargets.forEach((el) => {
        el.value = mode;
      });
    }

    // Sync icons (show sun when dark, moon when light)
    if (this.hasLightIconTarget) {
      this.lightIconTargets.forEach((el) => {
        el.style.display = isDark ? 'none' : '';
      });
    }

    if (this.hasDarkIconTarget) {
      this.darkIconTargets.forEach((el) => {
        el.style.display = isDark ? '' : 'none';
      });
    }
  }

  // ─────────────────────────────────────────────────────────────
  // Events
  // ─────────────────────────────────────────────────────────────

  /** Dispatch custom event when theme changes */
  dispatchThemeChange() {
    const event = new CustomEvent('theme:change', {
      bubbles: true,
      detail: {
        mode: this.getStoredMode(),
        theme: this.currentTheme,
        isDark: this.isDarkActive(),
      },
    });
    
    this.element.dispatchEvent(event);
    document.dispatchEvent(event);
  }
}
