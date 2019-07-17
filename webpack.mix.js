let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

const vendors = 'resources/assets/vendors/';
const resourcesAssets = 'resources/assets/';
const srcCss = resourcesAssets + 'css/';
const srcJs = resourcesAssets + 'js/';

//destination path configuration
const dest = 'public/assets/';
const destFonts = dest + 'fonts/';
const destCss = dest + 'css/';
const destJs = dest + 'js/';
const destImg = dest + 'img/';
const destImages = dest + 'images/';
const destVendors= dest + 'vendors/';


const paths = {
    'animate': vendors + 'animate.css/',
    'accwizard': vendors + 'acc-wizard/release/',
    'fileUpload' : vendors + 'blueimp-file-upload/',
    'bootstrap': vendors + 'bootstrap/dist/',
    'markdown' : vendors + 'bootstrap-markdown/',
    'timepicker' : vendors + 'bootstrap-timepicker/',
    'jvectormap' : vendors + 'bower-jvectormap/',
    'fontawesome': vendors + 'font-awesome/',
    'flotchart': vendors + 'flotchart/',
    'countUp': vendors +'countUp.js/dist/',
    'dataTables': vendors + 'datatables/media',
    'holderjs' : vendors + 'holderjs/',
    'jquery' : vendors + 'jquery/',
    'datetimepicker': vendors + 'eonasdan-bootstrap-datetimepicker/build/',
    'fullcalendar': vendors + 'fullcalendar/dist/',
    'twtrBootstrapWizard': vendors + 'twitter-bootstrap-wizard/',
    'icheck': vendors + 'iCheck/',
    'jasnyBootstrap': vendors + 'jasny-bootstrap/dist/',
    'bootstrapValidator' : vendors + 'bootstrapvalidator/dist/',
    'jqueryui' : vendors + 'jquery-ui/',
    'moment' : vendors + 'moment/',
    'sparkline' : vendors + 'sparkline/src/',
    'jqueryeasypiechart' : vendors + 'bower-jquery-easyPieChart/dist/',
    'datatables' : vendors + 'datatables.net/',
    'datatablesbs' : vendors + 'datatables.net-bs/',
    'datatablesresponsive' : vendors + 'datatables.net-responsive/',
    'jqvmap': vendors + 'jqvmap/',
    'raphael' : vendors + 'raphael/',
    'magnify' : vendors + 'bootstrap-magnify/',
    'xeditable' : vendors + 'x-editable/dist/',
    'jquerymockjax': vendors + 'jquery-mockjax/dist/'
};

//copy frontend skins to public
mix.copy(srcCss + 'frontend/skins', destCss + 'frontend/skins');


// Copy fonts straight to public
mix.copy(paths.bootstrap + 'fonts', destFonts);
mix.copy(paths.fontawesome + 'fonts', destFonts);
mix.copy(resourcesAssets + 'css/fonts.css', destCss);

// Copy images straight to public
mix.copy(paths.jqueryui + 'themes/base/images', destImg);
mix.copy(resourcesAssets + 'img', destImg, false);
mix.copy(resourcesAssets + 'img/authors', destImg + '/authors');
mix.copy(resourcesAssets + 'images', destImages, false);
mix.copy(resourcesAssets + 'images/authors', destImages + '/authors');
// mix.copy(resourcesAssets + 'images/cart', destImages + '/cart');


// animate
mix.copy(paths.animate + 'animate.min.css',  destVendors + 'animate');
// metis menu
mix.copy( srcJs + 'metisMenu.js', destJs);

// backbone
// mix.copy(paths.backbone + 'backbone-min.js',  destVendors + 'backbone/js');

// x-editable
mix.copy(paths.xeditable + 'bootstrap3-editable/css/bootstrap-editable.css',  destVendors + 'x-editable/css');
mix.copy(paths.xeditable + 'bootstrap3-editable/js/bootstrap-editable.js',  destVendors + 'x-editable/js');
mix.copy(paths.xeditable + 'bootstrap3-editable/img',  destVendors + 'x-editable/img');

mix.copy(paths.xeditable + 'inputs-ext/typeaheadjs/lib/typeahead.js',  destVendors + 'x-editable/js');
mix.copy(paths.xeditable + 'inputs-ext/typeaheadjs/lib/typeahead.js-bootstrap.css',  destVendors + 'x-editable/css');
mix.copy(paths.xeditable + 'inputs-ext/typeaheadjs/typeaheadjs.js',  destVendors + 'x-editable/js');
mix.copy(paths.xeditable + 'inputs-ext/address/address.js',  destVendors + 'x-editable/js');

//imgmagnify
mix.copy(paths.magnify + 'css/bootstrap-magnify.css',  destVendors + 'bootstrap-magnify');
mix.copy(paths.magnify + 'js/bootstrap-magnify.js',  destVendors + 'bootstrap-magnify');

//jasny-bootstrap
mix.copy(paths.jasnyBootstrap + 'css/jasny-bootstrap.css',  destVendors + 'jasny-bootstrap/css');
mix.copy(paths.jasnyBootstrap + 'js/jasny-bootstrap.js',  destVendors + 'jasny-bootstrap/js');


// bootstrapvalidator
mix.copy(paths.bootstrapValidator + 'css/bootstrapValidator.min.css',  destVendors + 'bootstrapvalidator/css');
mix.copy(paths.bootstrapValidator + 'js/bootstrapValidator.min.js',  destVendors + 'bootstrapvalidator/js');

//select2
// mix.copy(paths.select2 + 'css/select2.min.css',  destVendors + 'select2/css');
// mix.copy(paths.select2 + 'js/select2.js',  destVendors + 'select2/js');
// mix.copy(paths.select2BootstrapTheme + 'select2-bootstrap.css',  destVendors + 'select2/css');


//icheck
mix.copy(paths.icheck + 'icheck.js', destVendors + 'iCheck/js');
mix.copy(paths.icheck + 'skins/', destVendors + 'iCheck/css', false);


// countUp js
mix.copy(paths.countUp + 'countUp.js',  destVendors + 'countUp_js/js');

// bower-jquery-easyPieChart
mix.copy(paths.jqueryeasypiechart + 'jquery.easypiechart.min.js',  destVendors + 'bower-jquery-easyPieChart/js');
mix.copy(paths.jqueryeasypiechart + 'easypiechart.min.js',  destVendors + 'bower-jquery-easyPieChart/js');
mix.copy( srcJs + 'pages/jquery.easingpie.js',  destVendors + 'bower-jquery-easyPieChart/js');

// moment
mix.copy(paths.moment + 'min/moment.min.js',  destVendors + 'moment/js');

// jquery-mockjax
mix.copy(paths.jquerymockjax + 'jquery.mockjax.js',  destVendors + 'jquery-mockjax/js');

// bootstrap-datetimepicker
mix.copy(paths.datetimepicker + 'css/bootstrap-datetimepicker.min.css',  destVendors + 'datetimepicker/css');
mix.copy(paths.datetimepicker + 'js/bootstrap-datetimepicker.min.js',  destVendors + 'datetimepicker/js');


// toastr
// mix.copy(paths.toastr + 'toastr.css',  destVendors + 'toastr/css');
// mix.copy(paths.toastr + 'toastr.min.js',  destVendors + 'toastr/js');

// font-awesome
mix.copy(paths.fontawesome + 'css/font-awesome.min.css', 'public/assets/css');

// datatables
mix.copy(paths.datatables + 'js/jquery.dataTables.js',  destVendors + 'datatables/js');

mix.copy(paths.datatablesbs + 'js/dataTables.bootstrap.js',  destVendors + 'datatables/js');
mix.copy(paths.datatablesbs + 'css/dataTables.bootstrap.css',  destVendors + 'datatables/css');
mix.copy(paths.datatablesresponsive + 'js/dataTables.responsive.js',  destVendors + 'datatables/js');

// flot charts
mix.copy(paths.flotchart + 'jquery.flot.js', destVendors + 'flotchart/js');
mix.copy(paths.flotchart + 'jquery.flot.resize.js', destVendors + 'flotchart/js');

// fullcalendar
mix.copy(paths.fullcalendar + 'fullcalendar.css', destVendors + 'fullcalendar/css');
mix.copy(paths.fullcalendar + 'fullcalendar.min.js', destVendors + 'fullcalendar/js');
// mix.copy( srcCss + 'pages/calendar_custom.css', destCss + 'pages');

//  bower-jvectormap
mix.copy(paths.jvectormap + 'jquery-jvectormap-1.2.2.css',  destVendors + 'bower-jvectormap/css');
mix.copy(paths.jvectormap + 'jquery-jvectormap-1.2.2.min.js',  destVendors + 'bower-jvectormap/js/jquery-jvectormap-1.2.2.min.js');
mix.copy(paths.jvectormap + 'jquery-jvectormap-world-mill-en.js',  destVendors + 'bower-jvectormap/js/jquery-jvectormap-world-mill-en.js');


// 404 page
mix.copy( srcCss + 'pages/404.css', destCss + 'pages');
mix.copy( srcJs + '404.js', destJs);

// 500 page
mix.copy( srcCss + 'pages/500.css', destCss + 'pages');

// news item page
mix.copy( srcCss + 'pages/blog.css', destCss + 'pages');

// indexpage
mix.copy( srcJs + 'dashboard.js', destJs + 'pages');
mix.copy( srcJs + 'todolist.js', destJs + 'pages');

//compose page
mix.copy( srcJs + 'pages/add_newblog.js', destJs + 'pages');

// sparkline charts page
mix.copy( srcCss + 'pages/sparklinecharts.css', destCss + 'pages');
mix.copy( srcJs + 'pages/sparkline.js', destJs + 'pages');
mix.copy( srcJs + 'jquery.sparkline.js',  destVendors + 'sparklinecharts');
mix.copy( srcJs + 'jquery.flot.spline.js',  destVendors + 'splinecharts');


// userprofile page
mix.copy( srcCss + 'pages/user_profile.css', destCss + 'pages');
mix.copy( srcJs + 'pages/user_profile.js', destJs + 'pages');

//adduser page
mix.copy( srcJs + 'pages/adduser.js', destJs + 'pages');
mix.copy( srcJs + 'pages/edituser.js', destJs + 'pages');


// login page
mix.copy(paths.bootstrap + 'css/bootstrap.min.css',  destCss);
mix.copy( srcCss + 'pages/login.css', destCss + 'pages');

//form wizard page
mix.copy( srcCss + 'pages/wizard.css', destCss + 'pages');
mix.copy( srcJs + 'pages/form_wizard.js', destJs + 'pages');
mix.copy(paths.twtrBootstrapWizard + 'jquery.bootstrap.wizard.js',  destVendors + 'bootstrapwizard');
mix.copy( srcJs + 'pages/form_wizard.js', destJs + 'pages');

// lockscreen builder
mix.copy( srcCss + 'pages/lockscreen.css', destCss + 'pages');
mix.copy( srcJs + 'lockscreen.js', destJs + 'pages');

//  default layout page
mix.copy( srcJs + 'jquery-1.11.1.min.js', destJs);
mix.copy( srcJs + 'bootstrap.min.js', destJs);
mix.copy( srcJs + 'livicons-1.4.min.js', destJs);
mix.copy( srcJs + 'josh.js', destJs);
mix.copy(paths.jqueryui + 'jquery-ui.min.js', destJs);
mix.copy(paths.raphael + 'raphael-min.js', destJs);
mix.copy(paths.holderjs + 'holder.js', destJs);
mix.copy(paths.holderjs + 'holder.min.js', destJs);

// frontend pages



//frontend forgotpwd js
mix.copy( srcJs + 'frontend/forgotpwd_custom.js', destJs + 'frontend');

//frontned login js
mix.copy( srcJs + 'frontend/login_custom.js', destJs + 'frontend');

//frontned register js
mix.copy( srcJs + 'frontend/register_custom.js', destJs + 'frontend');

// mix.copy(paths.mixitup + 'src/jquery.mixitup.js',  destVendors + 'mixitup');

// register
mix.copy( srcCss + 'frontend/register.css', destCss + 'frontend');
mix.copy( srcJs + 'jquery.min.js', destJs);

// forgot
mix.copy( srcCss + 'frontend/forgot.css', destCss + 'frontend');

// mix.copy( srcCss + 'pages/pickers.css', destCss + 'pages');



// Custom Styles

//css section
// Custom Styles
//black color scheme
mix.combine(
    [
        srcCss + 'fonts.css',
        srcCss + 'bootstrap.min.css',
        srcCss + 'font-awesome.min.css',
        srcCss + 'black.css',
        srcCss + 'panel.css',
        srcCss + 'metisMenu.css'
    ], destCss + 'app.css');

//white color scheme
mix.combine(
    [
        /*replace "black.css" with "white.css" to apply white theme for template*/
        srcCss + 'fonts.css',
        srcCss + 'bootstrap.min.css',
        srcCss + 'font-awesome.min.css',
        srcCss + 'styles/white.css',
        srcCss + 'panel.css',
        srcCss + 'metisMenu.css'
    ], destCss + 'app_white.css');

/*frontend css mix*/
/*default skin*/
mix.combine(
    [
        srcCss + 'fonts.css',
        srcCss +  'bootstrap.min.css',
        srcCss +  'font-awesome.min.css',
        srcCss +  'frontend/custom.css'
    ], destCss + 'lib.css');

// all global js files into app.js
mix.combine(
    [
        srcJs + 'jquery-1.11.1.min.js',
        vendors + 'jquery-ui/jquery-ui.min.js',
        srcJs + 'bootstrap.min.js',
        vendors + 'raphael/raphael-min.js',
        srcJs + 'livicons-1.4.min.js',
        srcJs + 'metisMenu.js',
        srcJs + 'josh.js',
        vendors+ 'holderjs/holder.min.js'
    ], destJs + 'app.js');

/*frontend js mix*/

mix.combine(
    [
        srcJs + 'jquery-1.11.1.min.js',
        srcJs + 'bootstrap.min.js',
        vendors + 'raphael/raphael-min.js',
        srcJs + 'livicons-1.4.min.js',
        srcJs + 'frontend/josh_frontend.js'
    ], destJs + 'frontend/lib.js');

