import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '0.0.0.0',   // Allows external access
        port: 5173,        // Default Vite port
        strictPort: true,  // Ensures Vite sticks to this port
        hmr: {
          host: '192.168.1.13', // Replace with your actual local IP
        },
        cors: true,
      },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/jquery.js'],
            refresh: true,
        }),
    ],
});
