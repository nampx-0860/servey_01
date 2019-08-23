const elixir = require('laravel-elixir');
var shell = require('gulp-shell');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */
gulp.task('langjs', shell.task('php artisan lang:js -c public/plugins/languages/messages.js'));

elixir(function (mix) {
    mix.task('langjs')
        .copy('node_modules/jquery/dist/jquery.min.js', 'public/plugins/jquery')
        .copy('node_modules/bootstrap/', 'public/plugins/bootstrap')
        .copy('node_modules/font-awesome/fonts', 'public/plugins/font-awesome/fonts')
        .copy('node_modules/font-awesome/css/font-awesome.min.css', 'public/plugins/font-awesome/css')
        .copy('node_modules/jquery-menu-aim/jquery.menu-aim.js', 'public/plugins/jquery-menu-aim')
        .copy('node_modules/pace-progress/pace.min.js', 'public/plugins/pace-progress')
        .copy('node_modules/js-offcanvas/dist/_css/minified/js-offcanvas.css', 'public/plugins/js-offcanvas')
        .copy('node_modules/js-offcanvas/dist/_js/js-offcanvas.min.js', 'public/plugins/js-offcanvas')
        .copy('node_modules/ionicons/dist', 'public/plugins/ionicons')
        .copy('node_modules/metismenu/dist/metisMenu.min.css', 'public/plugins/metismenu')
        .copy('node_modules/metismenu/dist/metisMenu.min.js', 'public/plugins/metismenu')
        .copy('node_modules/jquery-ui-dist', 'public/plugins/jquery-ui')
        .copy('node_modules/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css', 'public/plugins/tempusdominus-bootstrap-4')
        .copy('node_modules/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js', 'public/plugins/tempusdominus-bootstrap-4')
        .copy('node_modules/moment/min/moment-with-locales.min.js', 'public/plugins/moment')
        .copy('resources/assets/templates/survey/css', 'public/templates/survey/css')
        .copy('resources/assets/templates/survey/js', 'public/templates/survey/js')
        .copy('resources/assets/templates/survey/images', 'public/templates/survey/images')
        .copy('resources/assets/templates/survey/fonts', 'public/templates/survey/fonts')
        .copy('node_modules/datatables.net-dt/css', 'public/plugins/datatables/css')
        .copy('node_modules/datatables.net/js', 'public/plugins/datatables/js')
        .copy('node_modules/datatables.net-dt/images', 'public/plugins/datatables/images')
        .copy('node_modules/highcharts/css/highcharts.css', 'public/plugins/highcharts')
        .copy('node_modules/highcharts/js/highcharts.js', 'public/plugins/highcharts')
        .copy('node_modules/highcharts/js/highcharts-3d.js', 'public/plugins/highcharts')
        .copy('node_modules/highcharts/js/modules/exporting.js', 'public/plugins/highcharts/modules')
        .copy('node_modules/highcharts/js/modules/export-data.js', 'public/plugins/highcharts/modules')
        .copy('node_modules/sweetalert/', 'public/plugins/sweetalert')
        .copy('node_modules/popper.js/dist/umd/popper.min.js', 'public/plugins/popper/popper.min.js')
        .copy('node_modules/jquery-validation/dist', 'public/plugins/jquery-validation')
        .copy('node_modules/collect.js/build/collect.min.js', 'public/plugins/collect/')
        .copy('node_modules/linkifyjs/dist/linkify.min.js', 'public/plugins/linkifyjs/dist/linkify.min.js')
        .copy('node_modules/linkifyjs/dist/linkify-jquery.min.js', 'public/plugins/linkifyjs/dist/linkify-jquery.min.js')
        .version(['public/templates/survey/css/*.css', 'public/templates/survey/js/*.js']);
});
