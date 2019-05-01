let mix = require('laravel-mix');

mix.js('App/assets/js/app.js', 'App/Views/public/js')
   .sass('App/assets/sass/style.scss', 'App/Views/public/css')
   .options({
     processCssUrls: false
   })
//   .version()
//   .sourceMaps()
;