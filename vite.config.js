import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    // da commentare nel caso
    // server: {
    //     proxy: {
    //         '/api': {
    //             target: 'https://italoinviaggio.italotreno.com/api/TreniInCircolazioneService',
    //             changeOrigin: true,
    //             secure: false,
    //             // rewrite: path => path.replace(/^\/api/, '')
    //         }
    //     }
    // }
});
