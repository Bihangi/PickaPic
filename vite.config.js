import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/choose-role.js'
            ],
            refresh: true,
            buildDirectory: 'build', 
        }),
    ], build: {
        outDir: 'public/build',
        manifest: 'manifest.json', 
        rollupOptions: {
            output: {
                manualChunks: undefined,
            }
        }
    },
    server: {
        hmr: {
            host: 'localhost',
        },
    },
});