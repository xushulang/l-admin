import vue from '@vitejs/plugin-vue'
import laravel from 'laravel-vite-plugin'
import i18n from 'laravel-vue-i18n/vite'
import Icons from 'unplugin-icons/vite'
import { defineConfig } from 'vite'

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.ts',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        i18n(),
        Icons(),
    ],
})
