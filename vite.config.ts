import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  resolve: {
      alias: [
          {
              find: '@/', replacement: 'resources/js'
          }
      ],
  },
  plugins: [
      laravel({
          input: ['resources/css/app.css', 'resources/js/app.ts'],
          refresh: true,
      }),
      vue(),
  ],
  build: {
    target: ['es2022', 'edge89', 'firefox89', 'chrome89', 'safari15']
  }
});
