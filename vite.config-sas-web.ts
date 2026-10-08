import type { UserConfig } from 'vite';
import embeddedConfig from './vite.config-embedded';

export default {
    ...embeddedConfig,
    base: './',
    build: {
        ...embeddedConfig.build,
        outDir: './dist-sas-web',
        rollupOptions: {
            ...embeddedConfig.build.rollupOptions,
            input: {
                demo: './sas-book-demo/index.html',
                runner: './sas-book-demo/runner.html',
                licenses: './sas-book-demo/lizenzen.html'
            },
            output: {
                ...embeddedConfig.build.rollupOptions.output,
                entryFileNames: 'assets/[name]-[hash].js',
                manualChunks: undefined
            }
        }
    }
} satisfies UserConfig;
