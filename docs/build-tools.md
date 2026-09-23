# KISS Build Tools

contao-kiss ships the framework stylesheets, the Stimulus controllers and a Vite
config factory. The stack is [Vite](https://vite.dev) +
[Reprise](https://github.com/symfony/reprise) + [Tailwind 4](https://tailwindcss.com).
For linting and CI, see [best-practices.md](best-practices.md).

The controllers and their names are exported in
`vendor/digitaledinge/contao-kiss/build/assets/js/index.js`.

## Setup

contao-kiss must be installed. Your own package.json within your project needs to look like this:

```json
{
    "dependencies": {
        "@hotwired/stimulus": "^3.2"
    },
    "devDependencies": {
        "@digitaledinge/contao-kiss": "file:vendor/digitaledinge/contao-kiss/build"
    },
    "scripts": {
        "dev-server": "vite",
        "dev": "vite build --mode development",
        "watch": "vite build --mode development --watch",
        "build": "vite build"
    },
    "browserslist": ["defaults"]
}
```

`vite.config.mjs`:

```js
import { buildVite } from '@digitaledinge/contao-kiss/vite';

export default buildVite();
```

| Script               | Description                                          |
|----------------------|------------------------------------------------------|
| `npm run build`      | production build                                     |
| `npm run dev`        | development build                                    |
| `npm run watch`      | development build, rebuilt on change                 |
| `npm run dev-server` | dev server on port 8080, with hot module replacement |

The dev server needs a certificate, see [Dev server](#dev-server) for more information.

## Project structure

```
layout/
├── app.js                  every .js file here becomes an entry
├── css/
│   ├── index.css           the theme stylesheet
│   ├── assets/             images, referenced as @asset/…
│   └── components/         partials, imported by index.css
├── fonts/                  referenced as @font/…
└── scripts/
    └── controllers/        your Stimulus controllers

public/layout/              build output
```

`layout/app.js`, `layout/css/index.css`, `layout/fonts/` and
`layout/css/assets/` are mandatory for your build and have to sit at those paths.

The build writes into `public/layout/`, which has to be deployed to the server.

`layout/app.js` (example):

```js
import { Application } from '@hotwired/stimulus';
import { ThemeController, PopoverController } from '@digitaledinge/contao-kiss';
import GlightboxController from './scripts/controllers/glightbox-controller';

const application = Application.start();
application.register('theme', ThemeController);
application.register('popover', PopoverController);
application.register('glightbox', GlightboxController);
```

`layout/css/index.css`:

```css
@layer theme, base, typography, components, utilities;

@import "tailwindcss";
@import "./tailwind.theme.css"; /* Needs to be created - colors and @theme */

/* KISS framework */
@import "@digitaledinge/contao-kiss/css/index";

/* Vendor stylesheets belong here, never in JavaScript */
@import "glightbox/dist/css/glightbox.min.css" layer(components); /* layer into components for easier overrides */

/* Your components */
@import "./components/header.css";
@import "./components/footer.css";

/* Tailwind scans these for class names */
@source "../../vendor/digitaledinge/*/contao/templates";
@source "../css/";
```

There are two aliases, `@font` for `layout/fonts` and `@asset` for
`layout/css/assets`:

```css
@font-face {
    font-family: "My Font";
    src: url("@font/my_font/my-font-regular.woff2") format("woff2");
}

.hero {
    background-image: url("@asset/hero.jpg");
}
```

Files referenced through them are hashed and land in `public/layout/fonts/` and
`public/layout/images/`.

## Loading the assets

The `app` entry is loaded by default. A page template `page/layout.html.twig` or `page/layout/foobar.html.twig` template
can set another one, as long as a matching `layout/<name>.js` exists:

```twig
{% set kiss_theme_entry = 'app.campaign' %}
```

Own tags go in the same block:

```twig
{% block kiss_theme_assets %}
    {{ parent() }}
    <script type="module" src="{{ asset('extra.js', 'kiss_theme') }}"></script>
{% endblock %}
```

## Extending and overriding Vite options

In `vite.config.mjs`:

```js
buildVite({
    layoutDir: 'layout',        // source and output directory name
    themeCss: 'css/index.css',  // theme stylesheet, relative to layoutDir
    input: undefined,           // JS entries, replaces auto-detection
                                // a <name>.css entry is derived for each
    copy: [],                   // reprise copy rules
    alias: {},                  // extra resolve.alias entries
    plugins: [],                // extra vite plugins
    server: {},                 // dev server overrides
});
```

```js
import { buildVite } from '@digitaledinge/contao-kiss/vite';

export default async (env) => {
    const config = await buildVite()(env);

    config.build.chunkSizeWarningLimit = 1000;

    return config;
};
```

## Dev server with HMR

`npm run dev-server` runs on port 8080 with hot module replacement: CSS is
swapped without a reload, JavaScript and Twig changes reload the page.

It serves over HTTPS and needs the same certificate as the site. With the
Symfony CLI, run `symfony server:ca:install` once, then point `PFX_PATH` at
`certs/default.p12` in its configuration directory:

| Setup | Variable | Example |
| --- | --- | --- |
| Symfony CLI, macOS | `PFX_PATH` | `Library/Application Support/symfony-cli/certs/default.p12` |
| Symfony CLI, Linux | `PFX_PATH` | `.config/symfony-cli/certs/default.p12` |
| Symfony CLI before 5.17.0 | `PFX_PATH` | `.symfony5/certs/default.p12` |
| MAMP | `CERT_FILE` | `/Applications/MAMP/Library/OpenSSL/certs/foobar.crt` |
| | `CERT_KEY` | `/Applications/MAMP/Library/OpenSSL/certs/foobar.key` |

`PFX_PATH` resolves against `$HOME`, `CERT_KEY` and `CERT_FILE` against the
project root. Absolute paths are used as given.

Moving a pre-5.17.0 installation:

```bash
symfony server:stop --all
symfony proxy:stop
mv ~/.symfony5 ~/Library/Application\ Support/symfony-cli   # macOS
mv ~/.symfony5 ~/.config/symfony-cli                        # Linux
```

`.env.local`:

```dotenv
PFX_PATH="Library/Application Support/symfony-cli/certs/default.p12"

#CERT_FILE=/Applications/MAMP/Library/OpenSSL/certs/foobar.crt
#CERT_KEY=/Applications/MAMP/Library/OpenSSL/certs/foobar.key

DEV_SERVER_PORT=8080
DEV_SERVER_HOST=0.0.0.0

# when the certificate is issued for a hostname rather than 127.0.0.1
#DEV_SERVER_ORIGIN=https://project.wip:8080
```

With a custom domain from `symfony proxy:domain:attach`, set
`DEV_SERVER_ORIGIN` to that host — otherwise the asset URLs point at
`127.0.0.1` and fail the certificate check.

Run `npm run build` after stopping the dev server.

## Stylesheets

Tailwind 4 breaks with preprocessors, see the
[Tailwind 4 compatibility docs](https://tailwindcss.com/docs/compatibility#sass-less-and-stylus).
Nesting is native, and the whole `@import` graph is watched, including files
inside `vendor/`.

- The entry must be `index.css`.
- Partials are `.css` or `.pcss`, and imports spell out the extension.
- Import vendor CSS in `index.css`, never in JavaScript, with a layer:
  `@import "x.css" layer(components)`.
- Keep `@source` globs narrow for faster compilation time.

## Browser support

`browserslist` in `package.json` drives the JavaScript target and the CSS Vite
processes:

```json
{
    "browserslist": ["defaults"]
}
```

```json
["last 2 versions", "not dead"]          // narrow, modern output
["> 1%", "last 2 versions", "not dead"]  // slightly wider
["defaults", "not op_mini all"]
```

Tailwind's own output has a fixed floor, see the
[Tailwind 4 compatibility docs](https://tailwindcss.com/docs/compatibility) for more information about it.

## Troubleshooting

**`Cannot find package '@symfony/reprise'`**
With the bundle symlinked into `vendor/` by a Composer path repository,
run `npm install` in the bundle checkout as well.

**`Could not find the entrypoints file`**
Run `npm run build`, start the dev server or deploy your changes.

**Styles missing in the built site**
Move vendor CSS imports out of JavaScript into `index.css`.
