import fs from "fs";
import inertia from '@inertiajs/vite';
import laravel from "laravel-vite-plugin";
import tailwindcss from '@tailwindcss/vite';
import vue from "@vitejs/plugin-vue";
import { defineConfig, loadEnv } from "vite";
import { wayfinder } from '@laravel/vite-plugin-wayfinder';

export default defineConfig(({ mode }) => {
    process.env = { ...process.env, ...loadEnv(mode, process.cwd()) };
    const wsHost =
        process.env.VITE_WS_HOST !== undefined
            ? process.env.VITE_WS_HOST
            : "localhost";

    return {
        plugins: [
            laravel({
                input: "resources/js/app.ts",
                refresh: ["routes/**"],
            }),
            vue({
                template: {
                    transformAssetUrls: {
                        base: null,
                        includeAbsolute: false,
                    },
                },
            }),
            tailwindcss(),
            inertia(),
            ...(mode !== "test" ? [wayfinder({
                path: 'resources/js/wayfinder',
                skipDatabase: true,
            })] : [])
        ],
                server: {
            ...(fs.existsSync("/app/keystore/private/server.key") &&
                fs.existsSync("/app/keystore/server.crt")
                ? {
                    https: {
                        key: fs.readFileSync("/app/keystore/private/server.key"),
                        cert: fs.readFileSync("/app/keystore/server.crt"),
                    },
                }
                : {}),
            host: "0.0.0.0",
            hmr: {
                protocol: "wss",
                host: wsHost,
                ...(fs.existsSync("/app/keystore/private/server.key") &&
                    fs.existsSync("/app/keystore/server.crt")
                    ? {
                        https: {
                            key: fs.readFileSync("/app/keystore/private/server.key"),
                            cert: fs.readFileSync("/app/keystore/server.crt"),
                        },
                    }
                    : {}),
            },
            cors: {
                origin: [
                    `https://${wsHost}`,
                ],
            }
        },
        test: {
            globals: true,
            environment: 'happy-dom',
            coverage: {
                enabled: true,
                provider: 'v8',
                reportsDirectory: './tests/Vitest/_Report'
            }
        }
    };
});
