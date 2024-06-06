import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { quasar, transformAssetUrls } from '@quasar/vite-plugin'

export default defineConfig({
    build: {
        chunkSizeWarningLimit: 1000
    },
    // Commented In Itemion Build
    server: {
        hmr: {
            protocol: 'ws',
            host: 'localhost'
        }
    },
    plugins: [
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        quasar({
            sassVariables: 'resources/sass/quasar-variables.sass'
        }),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    test: {
        testTimeout: 30_000,
        environment: 'happy-dom',
        name: 'unit',
        globals: true
    },
    // resolve: {
    //     alias: {
    //         vue: 'vue/dist/vue.esm-bundler.js',
    //     },
    // },
});