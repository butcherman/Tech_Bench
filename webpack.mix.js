const mix = require('laravel-mix');
const path = require('path');

mix.webpackConfig(
{
    resolve:
    {
        alias:
        {
            ziggy: path.resolve('vendor/tightenco/ziggy/dist/js/route.js'),
        },
    },
});

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/guest.scss', 'public/css')
    .copy('node_modules/tinymce/skins', 'public/js/skins');
