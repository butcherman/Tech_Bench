import { defineConfig, loadEnv } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import fs from "fs";

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
        ],
        server: {
            https: {
                key: fs.readFileSync("./keystore/private/server.key"),
                cert: fs.readFileSync("./keystore/server.crt"),
            },
            host: "0.0.0.0",
            hmr: {
                protocol: "wss",
                host: wsHost,
                https: {
                    key: fs.readFileSync("./keystore/private/server.key"),
                    cert: fs.readFileSync("./keystore/server.crt"),
                },
            },
        },
    };
});
