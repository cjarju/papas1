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
