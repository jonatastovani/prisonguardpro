import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    // resolve: {
    //     alias: {
    //         "~bootstrap": path.resolve(__dirname, "node_modules/bootstrap"),
    //         // "~select2": path.resolve(__dirname, "node_modules/select2/dist"),
    //     }
    // },
    // server: {
    //     proxy: {
    //       '/api': 'http://172.14.239.101', // Substitua isso pela URL do seu servidor Laravel
    //     },
    //   },
});
