import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/dashboard.js', 'resources/js/inventory-count.js', 'resources/js/location-count.js', 'resources/js/no-tag.js', 'resources/js/admin-data.js', 'resources/js/manager-login.js', 'resources/js/employee-login.js', 'resources/js/users.js'],
            refresh: true,
        }),
    ],
});
