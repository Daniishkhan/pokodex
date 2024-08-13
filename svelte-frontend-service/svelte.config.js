import adapter from '@sveltejs/adapter-node';
import { vitePreprocess } from '@sveltejs/vite-plugin-svelte';
import sveltePreprocess from 'svelte-preprocess';
import path from 'path';

/** @type {import('@sveltejs/kit').Config} */
const config = {
  preprocess: [
    vitePreprocess(),
    sveltePreprocess({
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