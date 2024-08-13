import adapter from '@sveltejs/adapter-node';
import { vitePreprocess } from '@sveltejs/vite-plugin-svelte';
import path from 'path';

/** @type {import('@sveltejs/kit').Config} */
const config = {
  preprocess: [
    vitePreprocess({
      scss: {
        prependData: '@import "src/styles/global.scss";',
        includePaths: [path.resolve('./src/styles')]
      }
    })
  ],

  kit: {
    adapter: adapter()
  }
};

export default config;