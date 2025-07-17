import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/js/whatsapp.js',
                'resources/js/combos.js',
                'resources/js/welcome.js',
            ],
            refresh: true,
        }),
    ],
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
