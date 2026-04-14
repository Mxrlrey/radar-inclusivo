import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/pages/dashboard.js',
                'resources/js/components/photos.js',
                'resources/js/maps/barrier-map.js',
                'resources/js/maps/institution-map.js',
                'resources/js/maps/location-map.js',
                'resources/js/pages/image-uploader.js',
                'resources/js/pages/assistive-technologies.js',
                'resources/js/pages/accessible-educational-materials.js',
                'resources/js/pages/loans.js',
                'resources/js/components/dynamicFilters.js',
                'resources/js/pages/file-uploader.js',
                'resources/js/pages/waitlists.js',
                'resources/js/effects/timeline-animation.js'
            ],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        hmr: {
            host: 'localhost',
            protocol: 'ws'
        },
        watch: {
            usePolling: true,
            ignored: [
                '**/vendor/**',
                '**/storage/**',
                '**/node_modules/**',
                '**/public/build/**',
                '**/.git/**',
            ]
        }
    }
});
