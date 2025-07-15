import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0', // Ouve em todas as interfaces de rede
        port: 5173,
        hmr: {
            host: 'localhost', // O navegador se conecta a localhost
        },
        // Adicione esta opção para melhorar a detecção de arquivos no Docker
        watch: {
            usePolling: true,
        }
    },
});