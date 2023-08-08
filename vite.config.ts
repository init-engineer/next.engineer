import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/scss/frontend/app.scss',
        'resources/js/frontend/app.ts',
        'resources/scss/backend/app.scss',
        'resources/js/backend/app.ts',
      ],
      refresh: true,
    }),
  ],
});
