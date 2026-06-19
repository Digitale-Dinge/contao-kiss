/**
 * KISS backend behaviours.
 *
 * Grid-ratio integration: KISS pairs its own "gridColumns" field with the
 * digitaledinge/contao-grid-ratio-widget bundle. When the bundle's
 * "gridRatioActive" toggle is on, the even-columns field is irrelevant, so we
 * hide it.
 */
{
    // Contao renders the checkbox inside a "ctrl_<field>" container with an
    // "opt_<field>_0" input, so target the real checkbox by name/type.
    const getToggle = () => document.querySelector('input[type="checkbox"][name="gridRatioActive"]');

    const syncGridColumns = () => {
        const toggle = getToggle();
        const columns = document.getElementById('ctrl_gridColumns');
        if (!toggle || !columns) {
            return;
        }
        const wrapper = columns.closest('.widget') ?? columns.parentElement;
        if (wrapper) {
            wrapper.style.display = toggle.checked ? 'none' : '';
        }
    };

    const bind = () => {
        const toggle = getToggle();
        if (toggle && !toggle.dataset.kissGridColumnsBound) {
            toggle.dataset.kissGridColumnsBound = '1';
            toggle.addEventListener('change', syncGridColumns);
        }
        syncGridColumns();
    };

    // Observe <html> (not <body>): Contao's backend uses Turbo, which swaps the
    // <body> on save/navigation. documentElement persists, so the binding keeps
    // working afterwards.
    bind();
    new MutationObserver(bind).observe(document.documentElement, { childList: true, subtree: true });
}
