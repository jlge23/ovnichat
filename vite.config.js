import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import react from "@vitejs/plugin-react";
import tailwindcss from "@tailwindcss/vite";
import path from "path";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/sass/app.scss",
                "resources/js/app.js",
                "resources/ts/app.tsx",
                "resources/js/whatsapp.js",
                "resources/js/combos.js",
                'resources/js/welcome.js',
            ],
            refresh: true,
        }),
        react(),
        tailwindcss(),
    ],
    resolve: {
        alias: {
            "@": path.resolve(__dirname, "resources/ts"),
        },
    },
    /* server : {
        host: "http://www.debatev3.lan",
        port: 5173,
        proxy: {
        '/resources': {
            target: 'https://localhost:5173',
            changeOrigin: true,
            rewrite: (path) => path.replace(/^\/resources/, '')
            }
        },
        cors: {
            origin: [
                'http://www.debatev3.lan',
            ]
        }
    } */
    /* server: {
        host: "https://b157-186-68-150-135.ngrok-free.app",
        port: 5173,
        strictPort: true,
        https: true,
        https: {
            key: import.meta.env.SSL_CERTIFICATE_KEY,
            cert: import.meta.env.SSL_CERTIFICATE
        },
        proxy: {
        '/resources': {
            target: 'https://localhost:5173',
            changeOrigin: true,
            rewrite: (path) => path.replace(/^\/resources/, '')
            }
        },
        cors: {
            origin: [
                'https://b157-186-68-150-135.ngrok-free.app',
            ]
        }
    } */
});
