import { Controller } from '@hotwired/stimulus';

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
class SystemThemeListener {
    constructor(callback) {
        this.mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        this.callback = callback;
        this.isListening = false;

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

export default class ThemeController extends Controller {
    static THEMES = ['light', 'dark']; // Add: 'high-contrast', 'sepia', etc.
    static VALID_MODES = [...ThemeController.THEMES, 'system'];
    static DEFAULT_THEME = 'light';

    static targets = ['toggle', 'radio', 'select', 'lightIcon', 'darkIcon'];

    static values = {
        storageKey: { type: String, default: 'theme' },
    };

    connect() {
        this.systemListener = new SystemThemeListener((prefersDark) => {
            this.onSystemThemeChange(prefersDark);
        });

        this.onExternalChange = () => this.syncFromExternal();
        window.addEventListener('theme:changed', this.onExternalChange);

        this.applyTheme();
        this.updateListenerState();
        this.syncControls();
    }

    disconnect() {
        if (this.systemListener) {
            this.systemListener.destroy();
        }

        window.removeEventListener('theme:changed', this.onExternalChange);
    }

    toggle() {
        const isDark = this.isDarkActive();
        const newMode = isDark ? 'light' : 'dark';
        this.setMode(newMode);
    }

    set(event) {
        const value = event.target.value;
        if (ThemeController.VALID_MODES.includes(value)) {
            this.setMode(value);
        }
    }

    get currentTheme() {
        return this.getActiveTheme();
    }

    get currentMode() {
        return this.getStoredMode();
    }

    getStoredMode() {
        return localStorage.getItem(this.storageKeyValue) || 'system';
    }

    setMode(mode) {
        localStorage.setItem(this.storageKeyValue, mode);

        this.applyTheme();
        this.updateListenerState();
        this.syncControls();
        this.dispatchThemeChange();

        window.dispatchEvent(new CustomEvent('theme:changed'));
    }

    syncFromExternal() {
        this.applyTheme();
        this.updateListenerState();
        this.syncControls();
    }

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

    isDarkActive() {
        return document.documentElement.classList.contains('dark');
    }

    getActiveTheme() {
        const html = document.documentElement;
        for (const theme of ThemeController.THEMES) {
            if (theme !== ThemeController.DEFAULT_THEME && html.classList.contains(theme)) {
                return theme;
            }
        }
        return ThemeController.DEFAULT_THEME;
    }

    setThemeClass(theme) {
        const html = document.documentElement;
        // Reset theme classes
        ThemeController.THEMES.forEach((t) => {
            if (t !== ThemeController.DEFAULT_THEME) {
                html.classList.remove(t);
            }
        });

        if (theme !== ThemeController.DEFAULT_THEME) {
            html.classList.add(theme);
        }
    }

    /**
     * Enable/disable system listener based on mode
     */
    updateListenerState() {
        const mode = this.getStoredMode();

        if (mode === 'system') {
            this.systemListener.start();
        } else {
            this.systemListener.stop();
        }
    }

    onSystemThemeChange(prefersDark) {
        const theme = prefersDark ? 'dark' : ThemeController.DEFAULT_THEME;
        this.setThemeClass(theme);
        this.syncControls();
        this.dispatchThemeChange();
    }

    syncControls() {
        const mode = this.getStoredMode();
        const isDark = this.isDarkActive();

        // Toggles
        if (this.hasToggleTarget) {
            this.toggleTargets.forEach((el) => {
                el.checked = isDark;
            });
        }

        // Radio
        if (this.hasRadioTarget) {
            this.radioTargets.forEach((el) => {
                el.checked = el.value === mode;
            });
        }

        // Select
        if (this.hasSelectTarget) {
            this.selectTargets.forEach((el) => {
                el.value = mode;
            });
        }

        // Icons
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
