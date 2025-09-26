import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/choose-role.js',
                'resources/images/admin-bg.png',
                'resources/images/admin-logo.png',
                'resources/images/bg1.jpg',
                'resources/images/bg2.jpg',
                'resources/images/bg3.jpg',
                'resources/images/bg4.jpg',
                'resources/images/birthday.jpeg',
                'resources/images/camera-guy.jpg',
                'resources/images/client-bg1.png',
                'resources/images/client-bg2.jpg',
                'resources/images/client-logo.png',
                'resources/images/default-avatar.jpg',
                'resources/images/facebook-icon.svg',
                'resources/images/fashion.jpeg',
                'resources/images/hero.jpeg',
                'resources/images/image.png',
                'resources/images/instagram-icon.svg',
                'resources/images/login-bg.jpg',
                'resources/images/logo.png',
                'resources/images/mission.jpg',
                'resources/images/outdoor.jpeg',
                'resources/images/photographer-logo.png',
                'resources/images/profile.png',
                'resources/images/register-bg.jpg',
                'resources/images/register-bg1.png',
                'resources/images/studio.jpg',
                'resources/images/twitter-icon.svg',
                'resources/images/vision.jpg',
                'resources/images/wedding.jpg'
            ],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                assetFileNames: (assetInfo) => {
                    if (assetInfo.name && (assetInfo.name.endsWith('.png') || assetInfo.name.endsWith('.jpg') || assetInfo.name.endsWith('.jpeg') || assetInfo.name.endsWith('.svg'))) {
                        return 'assets/images/[name][extname]';
                    }
                    return 'assets/[name]-[hash][extname]';
                }
            }
        }
    }
});