
import fs from 'node:fs'
import laravel from "laravel-vite-plugin";
import path from 'node:path'
import tailwindcss from '@tailwindcss/vite';
import vue from "@vitejs/plugin-vue";
import { fileURLToPath } from 'node:url'
import { defineConfig, loadEnv } from "vite";

const appRoot = path.dirname(fileURLToPath(import.meta.url))

export default defineConfig(({ command, mode }) => {
    const certPath = path.resolve(appRoot, 'keystore/server.crt')
    const keyPath = path.resolve(appRoot, 'keystore/private/server.key')

    // Only configure HTTPS when running the development server
    // and both certificate files are available.
    const useHttps =
        command === 'serve' &&
        fs.existsSync(certPath) &&
        fs.existsSync(keyPath)

    const https = useHttps
        ? {
            cert: fs.readFileSync(certPath),
            key: fs.readFileSync(keyPath),
        }
        : undefined

    // Load variables from the .env file
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
        ],

        server: {
            ...(https ? { https } : {}),
            host: "0.0.0.0",
            hmr: {
                protocol: useHttps ? "wss" : "ws",
                host: wsHost,
                ...(useHttps ? {
                    https: {
                        key: https.key,
                        cert: https.cert,
                    },
                } : {}),
            },
            cors: {
                origin: [
                    `${useHttps ? "https" : "http"}://${wsHost}`,
                ],
            }
        },
    }
})
