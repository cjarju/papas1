var path                = require('path'); // core (built-in)
    gulp                = require('gulp'),
    gsourcemaps         = require('gulp-sourcemaps'),
    gsass               = require('gulp-sass'),
    guncss              = require('gulp-uncss'),
    gautoprefixer       = require('gulp-autoprefixer'),
    gcombinemq          = require('gulp-combine-mq'),
    gcssc               = require('gulp-css-condense'),
    gcsso               = require('gulp-csso'),
    gstripCssComments   = require('gulp-strip-css-comments'),
    gstripComments      = require('gulp-strip-comments'),
    guglify             = require('gulp-uglify'),
    guseref             = require('gulp-useref'),
    gulpif              = require('gulp-if'),
    gchanged            = require('gulp-changed'),
    gcached             = require('gulp-cached'),
    gutil               = require('gulp-util'),
    grename             = require('gulp-rename'),
    gclean              = require('gulp-clean'),
    gconcat             = require('gulp-concat'),
    gjshint             = require('gulp-jshint'),
    gw3css              = require('gulp-w3c-css'),
    ghtmlhint           = require('gulp-htmlhint'),
    gphpcs              = require('gulp-phpcs'),
    gimagemin           = require('gulp-imagemin'),
    lazypipe            = require('lazypipe'),
    browserSync         = require('browser-sync').create(),
    runSequence         = require('run-sequence')
    ;

var bs = {
        root: 'papas1/',
        src:  'src/',
        dist: 'dist/'
    }
    , asts = {
        scss_dir:       'assets/scss/',     scss_files:     'assets/scss/*.scss',
        css_dir:        'assets/css/',      css_files:      'assets/css/*.css',
        js_dir:         'assets/js/',       js_files:       'assets/js/*.js',
        img_dir:        'assets/images/',   img_files:      'assets/images/**/*.@(jpeg|jpg|gif|png)',
        fonts_dir:      'assets/fonts/',    fonts_files:    'assets/fonts/*',
        data_dir:       'assets/data/',     data_files:     'assets/data/*'
    }
    , admin = {
        scss_dir:       'admin/assets/scss/',   scss_files:     'admin/assets/scss/*.scss',
        css_dir:        'admin/assets/css/',    css_files:      'admin/assets/css/*.css',
        js_dir:         'admin/assets/js/',     js_files:       'admin/assets/js/*.js',
        img_dir:        'admin/assets/images/', img_files:      'admin/assets/images/**/*.@(jpeg|jpg|gif|png)',
        fonts_dir:      'admin/assets/fonts/',  fonts_files:    'admin/assets/fonts/*',
        data_dir:       'admin/assets/data/',   data_files:     'admin/assets/data/*',
        incl_dir:       'admin/includes/'
    }
    , feui_srv_url = 'https://localhost/'+bs.root+bs.dist
    , beui_srv_url = 'https://localhost/'+bs.root+bs.dist+'admin/'
    , tmp_dir      = 'tmp/'
    ;

/* build tasks: clean, copy, css/js/image optimization, cleanup */

// Delete the dist directory
gulp.task('cleandist', function() {
    return gulp.src(bs.dist+'*', {read: false})// don't read contents, get list only
        .pipe(gclean());
});

gulp.task('feuicleandir', function() {
    return gulp.src([bs.dist+'*', '!'+bs.dist+'admin/'], {read: false})// don't read contents, get list only
        .pipe(gclean());
});

gulp.task('beuicleandir', function() {
    return gulp.src(bs.dist+'admin/', {read: false})// don't read contents, get list only
        .pipe(gclean());
});

// copy
gulp.task('copyapp2dist', function() {
    return gulp.src([bs.src+'**/*', '!'+bs.src+'**/scss{,/**}', '!'+bs.src+'**/less{,/**}'])
        .pipe(gchanged(bs.dist)).on('error', gutil.log) // Return files that have changed only
        .pipe(gulp.dest(bs.dist));
});

gulp.task('feuicopydir', function() {
    return gulp.src([bs.src+'**/*', '!'+bs.src+'admin{,/**}', '!'+bs.src+'**/scss{,/**}', '!'+bs.src+'**/less{,/**}'])
        .pipe(gchanged(bs.dist)).on('error', gutil.log) // Return files that have changed only
        .pipe(gulp.dest(bs.dist));
});

gulp.task('beuicopydir', function() {
    return gulp.src(bs.src+'admin/**/*')
        .pipe(gchanged(bs.dist+'admin')).on('error', gutil.log) // Return files that have changed only
        .pipe(gulp.dest(bs.dist+'admin'));
});

/*
css optimization notes
  .pipe(gcombinemq()) //unsafe, things might go wrong: read is_it_safe_2_consolidate doc under assets_tools
*/

/* optimize css/js task: all in one (aio) */

/*
 To match upstream Bootstrap's (v3.6) level of browser compatibility, set autoprefixer's browsers option to:
 ["Android 2.3", "Android >= 4", "Chrome >= 20", "Firefox >= 24", "Explorer >= 8", "iOS >= 6", "Opera >= 12", "Safari >= 6"]
 */

var feuiCssTasks = lazypipe()
    .pipe(guncss, {
        html: [feui_srv_url],
        ignore: [/\.*navbar-shrink*/, /\.alert*/, /\.*close*/, /\.has-success*/, /\.has-warning*/, /\.has-error*/, /help-block/,
            /\.popover*/, /\.tooltip*/, /\.modal*/, /\.carousel*/, /\.affix*/, /\.fade*/, /\.dropdown*/, /\.*collaps*/, /\.*active*/,
            /\.*cb-hidden*/, /\.fa-angle*/,
            /\.rg-view*/, /rg-loading/, /\.es-nav*/]
    })
    .pipe(gstripCssComments, {preserve: false})
    .pipe(gautoprefixer, { browsers: ["Android 2.3", "Android >= 4", "Chrome >= 20", "Firefox >= 24", "Explorer >= 8",  "iOS >= 6", "Opera >= 12", "Safari >= 6"], cascade:false })
    .pipe(gcsso);

/* I can also the admin stylesheet instead of specifying the selectors: has the same effect */
var beuiCssTasks = lazypipe()
    .pipe(guncss, {
        html: [beui_srv_url, beui_srv_url+'about/edit?id=1',
               beui_srv_url+'users/', beui_srv_url+'users/new', beui_srv_url+'users/edit?id=1',
               beui_srv_url+'services/', beui_srv_url+'services/new', beui_srv_url+'services/edit?id=1',
               beui_srv_url+'gallery/', beui_srv_url+'gallery/new', beui_srv_url+'gallery/edit?id=1'],
        ignore: [/\.*navbar-shrink*/, /\.alert*/, /\.*close*/, /\.has-success*/, /\.has-warning*/, /\.has-error*/, /help-block/,
            /\.popover*/, /\.tooltip*/, /\.modal*/, /\.carousel*/, /\.affix*/, /\.fade*/, /\.dropdown*/, /\.*collaps*/, /\.*active*/,
            /\.ls_*/, /\.grow*/, /\.*notification*/, /\.*error*/]
    })
    .pipe(gstripCssComments, {preserve: false})
    .pipe(gautoprefixer, { browsers: ["Android 2.3", "Android >= 4", "Chrome >= 20", "Firefox >= 24", "Explorer >= 8",  "iOS >= 6", "Opera >= 12", "Safari >= 6"], cascade:false })
    .pipe(gcsso);
/*
 useref: parses the build blocks in the HTML, replaces them, concatenate the assets, returns the new stream (index.php for example)
 and concatenated streams (*.css and/or *.js).

 For modifications of assets, use gulp-if to conditionally handle specific types of assets and not process the html/php stream.

 gulpif(condition, func1(), optionalFunc2())
 * you can run multiple functions if a single stream type is expected (js or css)
 * if mixed streams (html/php, css and/or js) are expected as when you run useref, you must use lazypipe to run multiple functions

 var csstasks = lazypipe()
 .pipe(gcssc, optionalArg, optionalArg)
 .pipe(gcsso, optionalArg, optionalArg);

 */

// optimize css/js
gulp.task('feuioptimizecssjs', function () {
    return gulp.src(bs.src+'index.php')
        .pipe(guseref())
        .pipe(gulpif('*.css', feuiCssTasks()))
        .pipe(gulpif('*.js', guglify()))
        .pipe(gulp.dest(bs.dist)); // dir to write new stream index.php
});

// optimize css/js

/* backend ui requires authentication. disable it first before running task
 comment out contents of  admin/includes/_restrict.php file temporarily
 */

gulp.task('beuioptimizecssjs', function () {
     gulp.src([bs.src+admin.incl_dir+'_head_elements.php', bs.src+admin.incl_dir+'_footer.php'])
        .pipe(guseref())
        .pipe(gulpif('*.css', beuiCssTasks()))
        .pipe(gulpif('*.js', guglify()))
        .pipe(gulp.dest(bs.dist+admin.incl_dir)); // dir to write new streams _head_elements.php, _footer.php
    
    /* minify beui-ajax.min.js used on index of users, services, gallery only */
    return gulp.src(bs.dist+admin.js_dir+'beui-ajax.min.js')
        .pipe(guglify())
        .pipe(gulp.dest(bs.dist+admin.js_dir));
});

/* optimize css/js separated tasks: all in many (aim) */

/* css optimization step by step */

var concat_file  = 'concat';

/* concat css files in the desired order */
gulp.task('feuiconcatcss', function () {
    return gulp.src(bs.dist+asts.css_dir+'{bootstrap.min,ie10-viewport-bug,agency,elastislide,gallery,share-buttons,font-awesome,recaptcha}.css')
        .pipe(gconcat(concat_file+'.css'))
        .pipe(gulp.dest(bs.dist+asts.css_dir));
});

gulp.task('beuiconcatcss', function () {
    return gulp.src(bs.dist+admin.css_dir+'{bootstrap.min,font-awesome,admin}.css')
        .pipe(gconcat(concat_file+'.css'))
        .pipe(gulp.dest(bs.dist+admin.css_dir));
});

// remove unused css
/*
 for php files you have to connect to the server to fetch the generated html file
 prevent js injected css from being removed using the ignore option

 uncss creates a stream with the same name, writes to it as it processes the CSS definitions. for this reason you can't save the
 output stream in the same directory as the source.
 */
gulp.task('feuiuncss', function() {
    return gulp.src(bs.dist+asts.css_dir+concat_file+'.css')
        .pipe(guncss({
            html: [feui_srv_url],
            ignore: [/\.*navbar-shrink*/, /\.alert*/, /\.*close*/, /\.has-success*/, /\.has-warning*/, /\.has-error*/, /help-block/,
                /\.popover*/, /\.tooltip*/, /\.modal*/, /\.carousel*/, /\.affix*/, /\.fade*/, /\.dropdown*/, /\.*collaps*/, /\.*active*/,
                /\.*cb-hidden*/, /\.fa-angle*/,
                /\.rg-view*/, /rg-loading/, /\.es-nav*/]
        }))
        .pipe(gulp.dest(bs.dist+asts.css_dir+tmp_dir));
});

gulp.task('beuiuncss', function() {
    return gulp.src(bs.dist+admin.css_dir+concat_file+'.css')
        .pipe(guncss({
            html: [beui_srv_url, beui_srv_url+'about/edit?id=1',
                beui_srv_url+'users/', beui_srv_url+'users/new', beui_srv_url+'users/edit?id=1',
                beui_srv_url+'services/', beui_srv_url+'services/new', beui_srv_url+'services/edit?id=1',
                beui_srv_url+'gallery/', beui_srv_url+'gallery/new', beui_srv_url+'gallery/edit?id=1'],
            ignore: [/\.*navbar-shrink*/, /\.alert*/, /\.*close*/, /\.has-success*/, /\.has-warning*/, /\.has-error*/, /help-block/,
                /\.popover*/, /\.tooltip*/, /\.modal*/, /\.carousel*/, /\.affix*/, /\.fade*/, /\.dropdown*/, /\.*collaps*/, /\.*active*/,
                /\.ls_*/, /\.grow*/, /\.*notification*/, /\.*error*/]
        }))
        .pipe(gulp.dest(bs.dist+admin.css_dir+tmp_dir));
});

/* minify css */

gulp.task('feuiminifycss', function () {
    return gulp.src(bs.dist+asts.css_dir+tmp_dir+concat_file+'.css')
        .pipe(gstripCssComments({preserve: false}))
        .pipe(gautoprefixer({ browsers: ["Android 2.3", "Android >= 4", "Chrome >= 20", "Firefox >= 24", "Explorer >= 8",  "iOS >= 6", "Opera >= 12", "Safari >= 6"], cascade:false }))
        .pipe(gcsso())
        .pipe(grename({ basename: 'feui-main', extname: '.min.css'}))
        .pipe(gulp.dest(bs.dist+asts.css_dir));
});

gulp.task('beuiminifycss', function () {
    return gulp.src(bs.dist+admin.css_dir+tmp_dir+concat_file+'.css')
        .pipe(gstripCssComments({preserve: false}))
        .pipe(gautoprefixer({ browsers: ["Android 2.3", "Android >= 4", "Chrome >= 20", "Firefox >= 24", "Explorer >= 8",  "iOS >= 6", "Opera >= 12", "Safari >= 6"], cascade:false }))
        .pipe(gcsso())
        .pipe(grename({ basename: 'beui-main', extname: '.min.css'}))
        .pipe(gulp.dest(bs.dist+admin.css_dir));
});

/* js optimization step by step */

var concat_core_file  = 'concatcore',
    concat_cust_file  = 'concatcust'
    ;

/* concat js files in the desired order */
gulp.task('feuiconcatjs', function () {
    gulp.src(bs.dist+asts.js_dir+'{jquery.min,bootstrap.min,ie10-viewport-bug}.js')
        .pipe(gconcat(concat_core_file+'.js'))
        .pipe(gulp.dest(bs.dist+asts.js_dir));

    return gulp.src(bs.dist+asts.js_dir+'{jquery.easing.min,jquery.tmpl.min,elastislide,gallery,classie,cbpAnimatedHeader.min,jqBootstrapValidation,contact_me,agency,share-buttons}.js')
        .pipe(gconcat(concat_cust_file+'.js'))
        .pipe(gulp.dest(bs.dist+asts.js_dir));
});

gulp.task('beuiconcatjs', function () {
    gulp.src(bs.dist+admin.js_dir+'{jquery.min,bootstrap.min,ie10-viewport-bug}.js')
        .pipe(gconcat(concat_core_file+'.js'))
        .pipe(gulp.dest(bs.dist+admin.js_dir));

    return gulp.src(bs.dist+admin.js_dir+'admin.js')
        .pipe(gconcat(concat_cust_file+'.js'))
        .pipe(gulp.dest(bs.dist+admin.js_dir));
});

/* minify js */

gulp.task('feuiminifyjs', function () {
    gulp.src(bs.dist+asts.js_dir+concat_core_file+'.js')
        .pipe(guglify())
        .pipe(grename({ basename: 'feui-core', extname: '.min.js'}))
        .pipe(gulp.dest(bs.dist+asts.js_dir));
    return gulp.src(bs.dist+asts.js_dir+concat_cust_file+'.js')
        .pipe(guglify())
        .pipe(grename({ basename: 'feui-cust', extname: '.min.js'}))
        .pipe(gulp.dest(bs.dist+asts.js_dir));
});

gulp.task('beuiminifyjs', function () {
    gulp.src(bs.dist+admin.js_dir+concat_core_file+'.js')
        .pipe(guglify())
        .pipe(grename({ basename: 'beui-core', extname: '.min.js'}))
        .pipe(gulp.dest(bs.dist+admin.js_dir));
    gulp.src(bs.dist+admin.js_dir+concat_cust_file+'.js')
        .pipe(guglify())
        .pipe(grename({ basename: 'beui-cust', extname: '.min.js'}))
        .pipe(gulp.dest(bs.dist+admin.js_dir));
    return gulp.src(bs.dist+admin.js_dir+'beui-ajax.min.js')
        .pipe(guglify())
        .pipe(gulp.dest(bs.dist+admin.js_dir));
});

/* edit index.php or the right file(s) to use final js and css file(s) using link tags */

//cleanup css/js directories
gulp.task('feuicleanup', function() {
    return gulp.src(
        [
            bs.dist+asts.css_dir+tmp_dir, bs.dist+asts.css_files, '!'+bs.dist+asts.css_dir+'feui-main.min.css',
            bs.dist+asts.js_files, '!'+bs.dist+asts.js_dir+'feui-{core,cust}.min.js',
            bs.dist+asts.fonts_files, '!'+bs.dist+asts.fonts_dir+'fontawesome*', '!'+bs.dist+asts.fonts_dir+'glyphicons*',
            bs.dist+asts.data_dir+'*.sql'
        ]
        , {read: false}
        )// don't read contents, get list only
        .pipe(gclean());
});

gulp.task('beuicleanup', function() {
    return gulp.src(
        [
            bs.dist+admin.css_dir+tmp_dir, bs.dist+admin.css_files, '!'+bs.dist+admin.css_dir+'beui-main.min.css',
            bs.dist+admin.js_files, '!'+bs.dist+admin.js_dir+'beui-{core,cust}.min.js', '!'+bs.dist+admin.js_dir+'beui-ajax.min.js',
            bs.dist+admin.fonts_files, '!'+bs.dist+admin.fonts_dir+'fontawesome*', '!'+bs.dist+admin.fonts_dir+'glyphicons*',
            bs.dist+admin.data_dir+'*.sql'
        ]
        , {read: false}
    )// don't read contents, get list only
        .pipe(gclean());
});

/* optimize images */
gulp.task('feuioptimizeimages', function(){
    return gulp.src(bs.src+asts.img_dir+'{,logos/}*.+(png|jpg|gif|svg)')
        .pipe(gimagemin())
        .pipe(gulp.dest(bs.dist+asts.img_dir))
});

gulp.task('beuioptimizeimages', function(){
    return gulp.src(bs.src+admin.img_dir+'*.+(png|jpg|gif|svg)')
        .pipe(gimagemin())
        .pipe(gulp.dest(bs.dist+admin.img_dir))
});

/* When task-name is called, Gulp will run task-one first. When task-one finishes, Gulp will automatically start task-two.
Finally, when task-two is complete, Gulp will run task-three.*/

gulp.task('build', function(callback) {
    runSequence('cleandist', 'copyapp2dist',
                'feuioptimizecssjs', 'feuicleanup', 'feuioptimizeimages',
                'beuioptimizecssjs', 'beuicleanup', 'beuioptimizeimages',
                 callback);
});

gulp.task('feuibuild', function(callback) {
    runSequence('feuicleandir', 'feuicopydir', 'feuioptimizecssjs', 'feuicleanup', 'feuioptimizeimages', callback);
});

gulp.task('beuibuild', function(callback) {
    runSequence('beuicleandir', 'beuicopydir', 'beuioptimizecssjs', 'beuicleanup', 'beuioptimizeimages', callback);
});


/* Gulp first runs task-one. When task-one is completed, Gulp runs every task in the second argument simultaneously.
All tasks in this second argument must be completed before task-three is run.
gulp.task('task-name', function(callback) {
    runSequence('task-one', ['tasks','two','run','in','parallel'], 'task-three', callback);
});
 */

/* dev tasks */

/* initialize browser obj */
gulp.task('browserSync', function() {
    return browserSync.init({
        proxy: "https://localhost/devajax/src/"
    })
});

/* reload browser */
gulp.task('browserReload', function() {
    return browserSync.reload();
});

/* compile scss --> css */
gulp.task('sass', function() {
    return gulp.src(bs.src+asts.scss_files) // Return all files ending with .scss in src/assets/scss and children dirs
        .pipe(gcached('assetscss', {optimizeMemory: true})).on('error', gutil.log) // Return files that have changed only
        .pipe(gsass()).on('error', gutil.log) // Compile and return CSS files
        .pipe(gulp.dest(bs.src+asts.css_dir)) // Write CSS files
    //.pipe(browserSync.stream()) // Inject changes into browser without reload. redundant as there's a watcher for css dir
});

gulp.task('devbuild', ['browserSync', 'sass'], function (){

    gulp.watch(bs.src+asts.scss_files, ['sass']);

    gulp.watch([bs.src+asts.css_files, bs.src+asts.js_files], function(event) {
        gutil.log('File: ' + gutil.colors.green(path.basename(event.path)) + ' was ' + event.type + '.');
        browserSync.reload();
    });

    gulp.watch(bs.src+'index.php').on('change', function(file) {
        gutil.log('File: ' + gutil.colors.green(path.basename(file.path)) + ' was changed.');
        browserSync.reload();
    });
});

gulp.task('default', function() {
    console.log('default task')
});





