/**
 * @type {import("stylelint").Config}
 */
const stylelintConfig = {
    extends: [
        "stylelint-config-standard",
    ],
    rules: {
        "at-rule-no-unknown": null,
        "color-no-invalid-hex": true,
        "import-notation": null,
        "no-invalid-double-slash-comments": null,
        "selector-class-pattern": null,
        "selector-id-pattern": null,
    },
};

export default stylelintConfig;
