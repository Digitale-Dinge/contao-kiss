import { Controller } from '@hotwired/stimulus';

/**
 * Range Controller
 * 
 * Provides:
 * - Cross-browser fill/progress track
 * - Optional value display sync
 * 
 * Usage:
 *   <div data-controller="range">
 *     <input type="range" class="range range-primary" 
 *            data-range-target="input"
 *            data-action="input->range#update"
 *            min="0" max="100" value="50">
 *     <span data-range-target="value"></span>
 *   </div>
 * 
 * With suffix (e.g., "%"):
 *   <div data-controller="range" data-range-suffix-value="%">
 *     ...
 *   </div>
 */
export default class extends Controller {
    static targets = ['input', 'value'];
    static values = {
        suffix: { type: String, default: '' },
    };

    connect() {
        this.update();
    }

    update() {
        const input = this.inputTarget;
        const percent = ((input.value - input.min) / (input.max - input.min)) * 100;

        // Set CSS custom property for fill percentage (used by SCSS)
        input.style.setProperty('--range-fill-percent', `${percent}%`);

        // Update value display if target exists
        if (this.hasValueTarget) {
            this.valueTarget.textContent = input.value + this.suffixValue;
        }
    }
}
