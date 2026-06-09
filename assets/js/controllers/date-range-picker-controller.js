import { Controller } from '@hotwired/stimulus';
import { Datepicker, DateRangePicker } from 'vanillajs-datepicker';
import de from 'vanillajs-datepicker/locales/de';
import 'vanillajs-datepicker/css/datepicker.css';

// Register bundled locales (English ships by default; add more as needed).
Object.assign(Datepicker.locales, de);

/**
 * Date Range Picker Controller
 *
 * Turns the first two date inputs inside the controller element into a linked
 * "from / to" range picker (vanillajs-datepicker). The element only needs to
 * contain two <input> fields (e.g. arrival / departure).
 *
 * Usage:
 *   <div data-controller="date-range-picker">
 *     <input class="date-from" ...>
 *     <input class="date-to" ...>
 *   </div>
 *
 * Options (optional):
 *   data-date-range-picker-format-value="yyyy-mm-dd"   // default
 *   data-date-range-picker-language-value="de"         // default: <html lang>, else "en"
 */
export default class extends Controller {
    static values = {
        format: { type: String, default: 'yyyy-mm-dd' },
        language: { type: String, default: '' },
    };

    connect() {
        const language = this.languageValue
            || document.documentElement.lang
            || 'en';

        this.picker = new DateRangePicker(this.element, {
            format: this.formatValue,
            language,
            minDate: new Date(),
            todayHighlight: true,
            todayButton: true,
            clearButton: true,
            orientation: 'bottom',
        });
    }

    disconnect() {
        this.picker?.destroy();
        this.picker = null;
    }
}
