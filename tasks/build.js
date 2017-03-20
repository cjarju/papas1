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


/* other build tasks: different from css/js optimization */

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
