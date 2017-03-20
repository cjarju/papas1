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