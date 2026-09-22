import { existsSync, readdirSync, readFileSync } from 'node:fs';
import { createRequire } from 'node:module';
import { resolve } from 'node:path';
import { pathToFileURL } from 'node:url';

// Resolve from the project
const importFromProject = (specifier) =>
    import(pathToFileURL(createRequire(`${process.cwd()}/package.json`).resolve(specifier)).href);

// Automatically detect JavaScript files in the layout folder and add entries
const detectEntries = (root, layoutDir) => {
    const dir = `${root}/${layoutDir}`;

    if (!existsSync(dir)) {
        return {};
    }

    return Object.fromEntries(
        readdirSync(dir)
            .filter((file) => file.endsWith('.js'))
            .map((file) => [file.slice(0, -3), `./${layoutDir}/${file}`]),
    );
};

// The theme stylesheet is an entry of its own with URL in the entrypoints.json
const styleEntries = (root, layoutDir, entries, themeCss) => {
    const file = `${layoutDir}/${themeCss}`;

    if (!existsSync(`${root}/${file}`)) {
        return {};
    }

    return Object.fromEntries(Object.keys(entries).map((name) => [`${name}.css`, `./${file}`]));
};

// Injecting import.meta.hot tells vite to inject the HMR client, thus the entry no longer has to import CSS for live
// connection
const hmrClient = (root, entries) => {
    const files = new Set(Object.values(entries).map((file) => resolve(root, file)));

    return {
        name: 'kiss-hmr-client',
        apply: 'serve',
        transform(code, id) {
            return files.has(id.split('?')[0]) ? `${code}\nimport.meta.hot;\n` : null;
        },
    };
};

// Vite does not read browserlist itself
const browserslistTargets = async () => {
    try {
        const { default: browserslistToEsbuild } = await importFromProject('browserslist-to-esbuild');

        return browserslistToEsbuild();
    } catch {
        return undefined;
    }
};

const https = (env, root) => {
    if (env.PFX_PATH) {
        return { pfx: readFileSync(resolve(process.env.HOME, env.PFX_PATH)) };
    }

    if (env.CERT_KEY && env.CERT_FILE) {
        return { key: readFileSync(resolve(root, env.CERT_KEY)), cert: readFileSync(resolve(root, env.CERT_FILE)) };
    }

    return undefined;
};

export const buildVite = ({
    layoutDir = 'layout',
    input,
    themeCss = 'css/index.css',
    copy = [],
    plugins = [],
    server = {},
    alias = {},
} = {}) => {
    return async ({ mode }) => {
        const [{ default: Symfony }, { default: tailwindcss }, { loadEnv }] = await Promise.all([
            importFromProject('@symfony/reprise/vite'),
            importFromProject('@tailwindcss/vite'),
            importFromProject('vite'),
        ]);

        const root = process.cwd();
        const env = loadEnv(mode, root, '');
        const targets = await browserslistTargets();
        const outputPath = `public/${layoutDir}`;

        const entries = input ?? detectEntries(root, layoutDir);

        return {
            input: {
                ...entries,
                ...styleEntries(root, layoutDir, entries, themeCss),
            },
            resolve: {
                alias: {
                    '@font': resolve(root, `${layoutDir}/fonts`),
                    '@asset': resolve(root, `${layoutDir}/css/assets`),
                    ...alias,
                },
            },
            build: {
                emptyOutDir: true,
                sourcemap: 'production' !== mode,
                ...(targets ? { target: targets, cssTarget: targets } : {}),
                rollupOptions: {
                    output: {
                        assetFileNames: ({ names: [name] }) => {
                            if (/\.(woff2?|ttf|otf|eot)$/.test(name)) {
                                return 'fonts/[name].[hash:8][extname]';
                            }

                            if (/\.(svg|png|gif|jpe?g|webp|avif|ico)$/.test(name)) {
                                return 'images/[name].[hash:8][extname]';
                            }

                            return '[name].[hash:8][extname]';
                        },
                    },
                },
            },
            server: {
                host: env.DEV_SERVER_HOST || undefined,
                port: Number(env.DEV_SERVER_PORT || 8080),
                strictPort: true,
                https: https(env, root),
                allowedHosts: true,
                cors: true,
                watch: { ignored: [`**/${outputPath}/**`, '**/var/**'] },
                ...server,
            },
            plugins: [
                tailwindcss(),
                hmrClient(root, entries),
                Symfony({
                    outputPath,
                    publicPath: `/${layoutDir}`,
                    manifestKeyPrefix: '',
                    devServerOrigin: env.DEV_SERVER_ORIGIN || undefined,
                    copy,
                }),
                ...plugins,
            ],
        };
    };
};

export default buildVite;
