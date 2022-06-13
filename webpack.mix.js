const mix = require('laravel-mix');
const fs = require('fs');
const path = require('path');

require('laravel-mix-merge-manifest');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

//  Compile primary app scripts
mix.js('resources/js/app.js', 'public/js')
    .sass('resources/scss/app.scss', 'public/css')
    .copyDirectory('resources/images', 'public/images')
    .copy('node_modules/tinymce/skins', 'public/js/skins')
    .vue().mergeManifest();

//  Compile all app.js files in Module folders
const dirs = p => fs.readdirSync(p).filter(f => fs.statSync(path.resolve(p, f)).isDirectory())
let modules = dirs('./Modules');

[...modules].forEach((module) => {
    let path = './Modules/' + module + '/Resources';

    fs.exists(path, (exists) => {
        if(exists)
        {
            mix.sass(path + '/sass/app.scss', 'public/css/' + module.toLowerCase() + '.css')
            mix.js(path + '/js/app.js', 'public/js/' + module.toLowerCase() + '.js').vue()
        }
    });

});

// mix.webpackConfig({
//     stats: {
//         children: true,
//     },
// });

if(mix.inProduction())
{
    mix.version().disableNotifications();
}
