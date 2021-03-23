const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .js('resources/js/guest.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .sass('resources/sass/guest.scss', 'public/css');
//    .copyDirectory('resources/images', 'public/images')
//    .copy('node_modules/tinymce/skins', 'public/js/skins');

if (mix.inProduction())
{
    mix.version().disableNotifications();
}
