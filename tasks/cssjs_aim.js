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