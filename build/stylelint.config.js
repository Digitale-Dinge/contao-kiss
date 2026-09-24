/**
 * @type {import("stylelint").Config}
 */
const stylelintConfig = {
    extends: [
        "stylelint-config-standard",
        "@dreamsicle.io/stylelint-config-tailwindcss",
    ],
    rules: {
        "at-rule-no-unknown": null,
        "at-rule-prelude-no-invalid": [true, { ignoreAtRules: ["source"] }],
        "color-no-invalid-hex": true,
        "import-notation": null,
        "no-invalid-double-slash-comments": null,
        "selector-class-pattern": null,
        "selector-id-pattern": null,
    },
};

export default stylelintConfig;
