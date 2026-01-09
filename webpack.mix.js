const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps()
    .copyDirectory('node_modules/tinymce', 'public/js/tinymce');

// Disable WASM hashing to prevent memory issues on servers with limited resources
mix.webpackConfig({
    output: {
        hashFunction: 'xxhash64'
    }
});

//mix.setPublicPath('public_html');

//mix.js('resources/js/app.js', 'js')
//    .sass('resources/sass/app.scss', 'css')
//    .sourceMaps();
