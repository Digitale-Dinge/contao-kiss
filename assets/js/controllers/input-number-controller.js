import { Controller } from '@hotwired/stimulus';

/**
 * Input Number Controller
 * 
 * Provides increment/decrement functionality for number inputs.
 * Respects min, max, and step attributes on the input.
 * 
 * Default layout (number left, buttons right):
 *   <div class="input-number" data-controller="input-number">
 *     <input type="number" class="input" data-input-number-target="input" value="5" min="0" max="100">
 *     <button type="button" class="input-number-btn" data-action="click->input-number#decrement">−</button>
 *     <button type="button" class="input-number-btn" data-action="click->input-number#increment">+</button>
 *   </div>
 * 
 * Center layout (buttons on edges):
 *   <div class="input-number input-number-center" data-controller="input-number">
 *     <button type="button" class="input-number-btn" data-action="click->input-number#decrement">−</button>
 *     <input type="number" class="input" data-input-number-target="input" value="5" min="0" max="100">
 *     <button type="button" class="input-number-btn" data-action="click->input-number#increment">+</button>
 *   </div>
 */
export default class extends Controller {
    static targets = ['input'];

    connect() {
        this.updateButtonStates();
    }

    increment() {
        this.changeValue(1);
    }

    decrement() {
        this.changeValue(-1);
    }

    changeValue(direction) {
        const input = this.inputTarget;
        const step = parseFloat(input.step) || 1;
        const min = parseFloat(input.min);
        const max = parseFloat(input.max);
        let value = parseFloat(input.value) || 0;

        value += step * direction;

        // Clamp to min/max
        if (!isNaN(min) && value < min) value = min;
        if (!isNaN(max) && value > max) value = max;

        // Handle floating point precision
        const decimals = this.getDecimals(step);
        input.value = value.toFixed(decimals);

        // Dispatch input event for form bindings
        input.dispatchEvent(new Event('input', { bubbles: true }));
        input.dispatchEvent(new Event('change', { bubbles: true }));

        this.updateButtonStates();
    }

    updateButtonStates() {
        const input = this.inputTarget;
        const value = parseFloat(input.value) || 0;
        const min = parseFloat(input.min);
        const max = parseFloat(input.max);

        // Find all buttons and determine which is decrement/increment by their action
        const buttons = this.element.querySelectorAll('.input-number-btn');
        
        buttons.forEach(btn => {
            const action = btn.getAttribute('data-action') || '';
            if (action.includes('decrement')) {
                btn.disabled = !isNaN(min) && value <= min;
            } else if (action.includes('increment')) {
                btn.disabled = !isNaN(max) && value >= max;
            }
        });
    }

    // Handle manual input changes
    inputChanged() {
        this.updateButtonStates();
    }

    // Helper: Get decimal places from step
    getDecimals(step) {
        const str = step.toString();
        const decimal = str.indexOf('.');
        return decimal === -1 ? 0 : str.length - decimal - 1;
    }
}
