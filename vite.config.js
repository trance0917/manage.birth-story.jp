import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import fs from 'fs'

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: ['resources/scss/app.scss' ,'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: true,
        //ローカルのnpmでやりたい場合はこちら
        //host: 'local-admin.e-metals.net',
        port : 5201,
        https:{
            key: fs.readFileSync('/etc/letsencrypt/live/local.manage.birth-story.jp/privkey.pem'),
            cert: fs.readFileSync('/etc/letsencrypt/live/local.manage.birth-story.jp/cert.pem'),
        },
        hmr: {
            host: 'local.manage.birth-story.jp',
        },
        watch: {
            usePolling: true,
        },
    },
});

