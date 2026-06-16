/**
 * KISS backend behaviours (vanilla, no build step).
 *
 * Column-ratio ruler for element groups.
 * ---------------------------------------
 * A "kissGridRatio" widget renders a hidden input plus an empty bar. This script
 * builds an interactive ruler on top of it:
 *   - It reads the sibling gridColumns <select> (id from data-cols-field).
 *   - 2 columns  -> 1 draggable handle, 3 columns -> 2 handles.
 *   - 1 or 4-12 columns -> even distribution (ruler disabled).
 *   - Handles snap to 10% steps, every segment keeps at least 10%.
 * The value is stored as fr integers summing to 10, joined by "-" (e.g. "3-7").
 * An empty value means "even" and is left untouched until the editor drags.
 */
(function () {
    'use strict';

    var STEPS = 10; // 100% in 10% units

    // The gridColumns <select> stores the Column enum case NAMES (one..twelve),
    // not "cols_N". Map them to the actual column count.
    var WORDS = {
        one: 1, two: 2, three: 3, four: 4, five: 5, six: 6,
        seven: 7, eight: 8, nine: 9, ten: 10, eleven: 11, twelve: 12
    };

    function initRatio(container) {
        if (container.dataset.kissRatioReady) {
            return;
        }
        container.dataset.kissRatioReady = '1';

        var input = container.querySelector('input[type="hidden"]');
        var bar = container.querySelector('.kiss-grid-ratio__bar');
        var hint = container.querySelector('.kiss-grid-ratio__hint');
        var colsField = container.getAttribute('data-cols-field') || 'ctrl_gridColumns';
        var fieldName = colsField.replace(/^ctrl_/, '');

        // Resolve the gridColumns <select> robustly: by control id, by field name
        // (virtual fields keep their plain name) or, as a last resort, by the
        // closest select whose options use the "cols_N" value scheme.
        function findSelect() {
            var byOption = document.querySelector('select option[value^="cols_"]');
            return document.getElementById(colsField)
                || document.querySelector('select[name="' + fieldName + '"]')
                || document.querySelector('select[id$="' + fieldName + '"]')
                || (byOption ? byOption.closest('select') : null);
        }

        var select = findSelect();

        var hintEven = container.getAttribute('data-hint-even') || 'Even - drag to adjust';
        var hintDisabled = container.getAttribute('data-hint-disabled') || 'Only available for 2 or 3 columns';

        // State for the current column count.
        var state = { n: 0, pristine: true, positions: [], segs: [], handles: [], labels: [] };

        function colCount() {
            if (!select) {
                select = findSelect();
            }
            if (!select) {
                return 0;
            }
            var val = (select.value || '').trim();
            if (!val) {
                return 0;
            }
            if (WORDS[val]) {
                return WORDS[val];
            }
            // Fallback: "cols_2"/"2" style values, then the option position
            // (blank option sits at index 0, so index === column count).
            var m = /(\d+)/.exec(val);
            if (m) {
                return parseInt(m[1], 10);
            }
            return select.selectedIndex > 0 ? select.selectedIndex : 0;
        }

        function parseUnits(n) {
            var raw = (input.value || '').trim();
            if (!raw) {
                return null;
            }
            var parts = raw.split('-').map(function (x) { return parseInt(x, 10); });
            if (parts.length !== n) {
                return null;
            }
            var sum = 0;
            var ok = true;
            parts.forEach(function (p) {
                if (!(p >= 1)) { ok = false; }
                sum += p;
            });
            return (ok && sum === STEPS) ? parts : null;
        }

        function positionsToUnits(positions) {
            var units = [];
            var prev = 0;
            positions.forEach(function (p) {
                units.push(p - prev);
                prev = p;
            });
            units.push(STEPS - prev);
            return units;
        }

        function unitsToPositions(units) {
            var positions = [];
            var acc = 0;
            for (var i = 0; i < units.length - 1; i++) {
                acc += units[i];
                positions.push(acc);
            }
            return positions;
        }

        // Even split rounded onto the 10% grid (used when the editor first drags).
        function evenPositions(n) {
            var positions = [];
            for (var i = 1; i < n; i++) {
                positions.push(Math.round((i * STEPS) / n));
            }
            return positions;
        }

        function writeValue() {
            input.value = positionsToUnits(state.positions).join('-');
        }

        // Fractions (0..1) per segment for the current state.
        function fractions() {
            if (state.pristine) {
                var even = [];
                for (var i = 0; i < state.n; i++) {
                    even.push(1 / state.n);
                }
                return even;
            }
            return positionsToUnits(state.positions).map(function (u) { return u / STEPS; });
        }

        function paint() {
            var fracs = fractions();
            var cum = 0;
            for (var i = 0; i < fracs.length; i++) {
                state.segs[i].style.flexGrow = String(fracs[i]);
                state.labels[i].textContent = Math.round(fracs[i] * 100) + '%';
                if (i < state.handles.length) {
                    cum += fracs[i];
                    state.handles[i].style.left = (cum * 100) + '%';
                }
            }
            hint.textContent = state.pristine ? hintEven : '';
        }

        function startDrag(handle, handleIndex) {
            // Promote an even/pristine ruler to a concrete grid ratio on first drag.
            if (state.pristine) {
                state.positions = evenPositions(state.n);
                state.pristine = false;
                writeValue();
            }

            function onMove(e) {
                var rect = bar.getBoundingClientRect();
                if (!rect.width) {
                    return;
                }
                var frac = (e.clientX - rect.left) / rect.width;
                var u = Math.round(frac * STEPS);

                var positions = state.positions;
                var lower = handleIndex === 0 ? 1 : positions[handleIndex - 1] + 1;
                var upper = handleIndex === positions.length - 1 ? STEPS - 1 : positions[handleIndex + 1] - 1;
                // Keep room (>=1 unit) for every segment on either side.
                lower = Math.max(lower, handleIndex + 1);
                upper = Math.min(upper, STEPS - (positions.length - handleIndex));

                positions[handleIndex] = Math.max(lower, Math.min(upper, u));
                writeValue();
                paint();
            }

            // Listeners live on the handle (not document): with pointer capture they
            // still fire while dragging outside it, and they are torn down with the
            // element on a Turbo navigation -> no lingering document-level handlers.
            function onEnd() {
                handle.removeEventListener('pointermove', onMove);
                handle.removeEventListener('pointerup', onEnd);
                handle.removeEventListener('pointercancel', onEnd);
            }

            handle.addEventListener('pointermove', onMove);
            handle.addEventListener('pointerup', onEnd);
            handle.addEventListener('pointercancel', onEnd);
        }

        function build() {
            var n = colCount();
            state.n = n;
            state.segs = [];
            state.handles = [];
            state.labels = [];
            bar.innerHTML = '';
            container.classList.remove('kiss-grid-ratio--disabled', 'kiss-grid-ratio--active');

            if (n !== 2 && n !== 3) {
                container.classList.add('kiss-grid-ratio--disabled');
                hint.textContent = hintDisabled;
                return;
            }

            container.classList.add('kiss-grid-ratio--active');

            var units = parseUnits(n);
            if (units) {
                state.pristine = false;
                state.positions = unitsToPositions(units);
            } else {
                state.pristine = true;
                state.positions = [];
            }

            // Segments (with a % label inside each).
            for (var i = 0; i < n; i++) {
                var seg = document.createElement('div');
                seg.className = 'kiss-grid-ratio__seg';
                var label = document.createElement('span');
                label.className = 'kiss-grid-ratio__label';
                seg.appendChild(label);
                bar.appendChild(seg);
                state.segs.push(seg);
                state.labels.push(label);
            }

            // Handles (n - 1).
            for (var h = 0; h < n - 1; h++) {
                (function (index) {
                    var handle = document.createElement('div');
                    handle.className = 'kiss-grid-ratio__handle';
                    handle.addEventListener('pointerdown', function (e) {
                        e.preventDefault();
                        if (handle.setPointerCapture) {
                            try { handle.setPointerCapture(e.pointerId); } catch (err) { /* noop */ }
                        }
                        startDrag(handle, index);
                    });
                    bar.appendChild(handle);
                    state.handles.push(handle);
                })(h);
            }

            paint();
        }

        var changeBound = false;
        function bindChange() {
            if (!changeBound && select) {
                select.addEventListener('change', build);
                changeBound = true;
            }
        }

        bindChange();
        build();
        // Re-resolve once more after the backend has fully wired up (e.g. chosen).
        bindChange();
    }

    function initAll() {
        document.querySelectorAll('.kiss-grid-ratio').forEach(initRatio);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAll);
    } else {
        initAll();
    }

    // Contao's backend runs Turbo Drive: DOMContentLoaded does NOT fire on Turbo
    // visits (e.g. after pressing "Save"), so re-initialise on turbo:load too.
    // The per-element data-kiss-ratio-ready guard keeps this idempotent, and a
    // freshly swapped-in widget gets initialised exactly once.
    document.addEventListener('turbo:load', initAll);
})();
