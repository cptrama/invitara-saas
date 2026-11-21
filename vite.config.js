import { defineConfig } from 'vite';
import { resolve } from 'path';

export default defineConfig({
  base: '/build/',
  publicDir: false,
  build: {
    outDir: 'public/build',
    emptyOutDir: false,
    manifest: true,
    rollupOptions: {
      input: {
        app: resolve(__dirname, 'resources/js/app.js'),
      },
      output: {
        entryFileNames: 'assets/[name].js',
        chunkFileNames: 'assets/[name].js',
        assetFileNames: 'assets/[name].[ext]',
      },
    },
  },
});
