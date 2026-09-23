# Best practices

Recommended setup for a project built on contao-kiss.

## Linting

```json
{
    "devDependencies": {
        "@biomejs/biome": "^2.3",
        "@dreamsicle.io/stylelint-config-tailwindcss": "^1.2",
        "npm-run-all": "^4.1",
        "stylelint": "^16.23",
        "stylelint-config-standard": "^39.0"
    },
    "scripts": {
        "lint:js": "biome check --write --unsafe layout",
        "lint:css": "stylelint --fix \"layout/**/*.{css,pcss}\"",
        "lint": "run-s lint:js lint:css"
    }
}
```

`stylelint.config.js`:

```js
const stylelintConfig = {
    extends: [
        "stylelint-config-standard",
        "@dreamsicle.io/stylelint-config-tailwindcss",
    ],
    rules: {
        "at-rule-prelude-no-invalid": [true, { ignoreAtRules: ["source"] }],
        "selector-class-pattern": null,
        "selector-id-pattern": null,
    },
};

module.exports = stylelintConfig;
```

`biome.json`:

```json
{
    "$schema": "https://biomejs.dev/schemas/2.3.8/schema.json",
    "files": {
        "ignoreUnknown": true,
        "includes": ["layout/**", "!**/*.min.js", "!**/*.min.css"]
    },
    "formatter": {
        "indentStyle": "space",
        "indentWidth": 4,
        "lineWidth": 120
    },
    "javascript": {
        "formatter": { "quoteStyle": "single" }
    },
    "linter": {
        "includes": ["layout/**"],
        "rules": {
            "suspicious": { "noAssignInExpressions": "off" }
        }
    },
    "css": {
        "formatter": { "enabled": true, "indentWidth": 4 },
        "parser": { "tailwindDirectives": true }
    }
}
```

Biome skips `.pcss`, stylelint however checks most. Name stylesheets `.css` to have it check them.
