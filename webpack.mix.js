const { mix } = require('laravel-mix');

mix.js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .copyDirectory('resources/assets/sounds', 'public/sounds')
    .copyDirectory('resources/assets/images', 'public/images')
    .extract(['vue', 'vuex', 'bootstrap-sass', 'moment', 'lodash', 'jquery'])
    .autoload({
         jquery: ['$', 'jQuery', 'window.jQuery'],
         lodash: ['window._'],
         vue: ['window.Vue', 'Vue'],
         axios: ['window.axios', 'axios'],
         moment: ['window.moment', 'moment']
    })
    .version();
