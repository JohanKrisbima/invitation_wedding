import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';
import fs from 'fs';
import path from 'path';

// Fungsi pembaca file otomatis hingga ke dalam subfolder (rekursif)
const getFilesRecursively = (dirPath, extension) => {
    if (!fs.existsSync(dirPath)) return [];

    return fs
        .readdirSync(dirPath, { recursive: true })
        .filter((file) => typeof file === 'string' && file.endsWith(extension))
        // Abaikan file parsial/komponen yang diawali garis bawah misal: _variable.css
        .filter((file) => !path.basename(file).startsWith('_'))
        .map((file) => path.join(dirPath, file).replace(/\\/g, '/'));
};

const jsFiles = getFilesRecursively('resources/js', '.js');
const cssFiles = getFilesRecursively('resources/css', '.css');

export default defineConfig({
    plugins: [
        laravel({
            input: [...new Set([...jsFiles, ...cssFiles])],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
                bunny('Cormorant Garamond', {
                    weights: [400, 500, 600, 700],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
