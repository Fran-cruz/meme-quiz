import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
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
    ],
    server: {
        host: true,         // allows access from network (0.0.0.0)
        port: 5173,         // optional, default dev port
        strictPort: true,
        hmr: {
            host: 'formulaically-hypoxic-carlita.ngrok-free.dev', // your ngrok URL
            protocol: 'wss', // use websocket secure
        },
    },
});
//
// import { defineConfig } from 'vite';
// import vue from '@vitejs/plugin-vue';
//
// export default defineConfig({
//     plugins: [vue()],
//     server: {
//         host: true,         // allows access from network (0.0.0.0)
//         port: 5173,         // optional, default dev port
//         strictPort: true,
//         hmr: {
//             host: 'formulaically-hypoxic-carlita.ngrok-free.dev', // your ngrok URL
//             protocol: 'wss', // use websocket secure
//         },
//     },
// });
