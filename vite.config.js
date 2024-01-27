import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    // server: {
    //     proxy: {
    //       '/api': 'http://172.14.239.101', // Substitua isso pela URL do seu servidor Laravel
    //     },
    //   },
});
