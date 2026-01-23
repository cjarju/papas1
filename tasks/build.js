/* End-to-end taks for building the project */

const gulp = require('gulp');
const clean = require('gulp-clean');
const changed = require('gulp-changed');
const imagemin = require('gulp-imagemin');
const sass = require('gulp-sass')(require('sass'));
const purgecss = require('gulp-purgecss');
const log = require('fancy-log');
const PluginError = require('plugin-error');

// -------------------------
// Paths
// -------------------------
const bs = {
    src: 'src/',
    dist: 'dist/'
};

const asts = {
    scss_dir: 'assets/scss/', scss_files: 'assets/scss/*.scss',
    css_dir: 'assets/css/', css_files: 'assets/css/*.css',
    js_dir: 'assets/js/', js_files: 'assets/js/*.js',
    img_dir: 'assets/images/', img_files: 'assets/images/**/*.+(jpeg|jpg|gif|png)',
    fonts_dir: 'assets/fonts/', fonts_files: 'assets/fonts/*',
    data_dir: 'assets/data/', data_files: 'assets/data/*'
};

const admin = {
    scss_dir: 'admin/assets/scss/', scss_files: 'admin/assets/scss/*.scss',
    css_dir: 'admin/assets/css/', css_files: 'admin/assets/css/*.css',
    js_dir: 'admin/assets/js/', js_files: 'admin/assets/js/*.js',
    img_dir: 'admin/assets/images/', img_files: 'admin/assets/images/**/*.+(jpeg|jpg|gif|png)',
    fonts_dir: 'admin/assets/fonts/', fonts_files: 'admin/assets/fonts/*',
    data_dir: 'admin/assets/data/', data_files: 'admin/assets/data/*',
    incl_dir: 'admin/includes/'
};

const tmp_dir = 'tmp/';

// -------------------------
// Helpers
// -------------------------
const handleError = (task) => (err) => log.error(new PluginError(task, err));

// -------------------------
// Cleaning
// -------------------------
const cleandist = () => gulp.src(bs.dist + '*', { read: false, allowEmpty: true }).pipe(clean());
const feuicleandir = () => gulp.src([bs.dist + '*', '!' + bs.dist + 'admin/'], { read: false, allowEmpty: true }).pipe(clean());
const beuicleandir = () => gulp.src(bs.dist + 'admin/', { read: false, allowEmpty: true }).pipe(clean());

// -------------------------
// Copy
// -------------------------
const copyApp2Dist = () =>
    gulp.src([bs.src + '**/*', '!' + bs.src + '**/scss{,/**}', '!' + bs.src + '**/less{,/**}'])
        .pipe(changed(bs.dist)).on('error', handleError('changed'))
        .pipe(gulp.dest(bs.dist));

const feuicopydir = () =>
    gulp.src([bs.src + '**/*', '!' + bs.src + 'admin{,/**}', '!' + bs.src + '**/scss{,/**}', '!' + bs.src + '**/less{,/**}'])
        .pipe(changed(bs.dist)).on('error', handleError('changed'))
        .pipe(gulp.dest(bs.dist));

const beuicopydir = () =>
    gulp.src(bs.src + 'admin/**/*')
        .pipe(changed(bs.dist + 'admin')).on('error', handleError('changed'))
        .pipe(gulp.dest(bs.dist + 'admin'));

// -------------------------
// SCSS compilation
// -------------------------
const scss = () =>
    gulp.src(bs.src + asts.scss_files)
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest(bs.dist + asts.css_dir));

const adminScss = () =>
    gulp.src(admin.scss_files)
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest(bs.dist + admin.css_dir));

// -------------------------
// PurgeCSS to remove unused CSS
// -------------------------
const purgeCss = () =>
    gulp.src(bs.dist + asts.css_files)
        .pipe(purgecss({
            content: [bs.dist + '**/*.html', bs.dist + '**/*.php']
        }))
        .pipe(gulp.dest(bs.dist + asts.css_dir));

// -------------------------
// Optimize images
// -------------------------
const feuiOptimizeImages = () =>
    gulp.src(bs.src + asts.img_dir + '{,logos/}*.+(png|jpg|gif|svg)')
        .pipe(imagemin())
        .pipe(gulp.dest(bs.dist + asts.img_dir));

const beuiOptimizeImages = () =>
    gulp.src(bs.src + admin.img_dir + '*.+(png|jpg|gif|svg)')
        .pipe(imagemin())
        .pipe(gulp.dest(bs.dist + admin.img_dir));

// -------------------------
// Cleanup CSS/JS/Fonts/Data
// -------------------------
const feuicleanup = () =>
    gulp.src([
        bs.dist + asts.css_dir + tmp_dir,
        bs.dist + asts.css_files, '!' + bs.dist + asts.css_dir + 'feui-main.min.css',
        bs.dist + asts.js_files, '!' + bs.dist + asts.js_dir + 'feui-{core,cust}.min.js',
        bs.dist + asts.fonts_files, '!' + bs.dist + asts.fonts_dir + 'fontawesome*', '!' + bs.dist + asts.fonts_dir + 'glyphicons*',
        bs.dist + asts.data_dir + '*.sql'
    ], { read: false, allowEmpty: true }).pipe(clean());

const beuicleanup = () =>
    gulp.src([
        bs.dist + admin.css_dir + tmp_dir,
        bs.dist + admin.css_files, '!' + bs.dist + admin.css_dir + 'beui-main.min.css',
        bs.dist + admin.js_files, '!' + bs.dist + admin.js_dir + 'beui-{core,cust}.min.js', '!' + bs.dist + admin.js_dir + 'beui-ajax.min.js',
        bs.dist + admin.fonts_files, '!' + bs.dist + admin.fonts_dir + 'fontawesome*', '!' + bs.dist + admin.fonts_dir + 'glyphicons*',
        bs.dist + admin.data_dir + '*.sql'
    ], { read: false, allowEmpty: true }).pipe(clean());

// -------------------------
// Default build task
// -------------------------
const build = gulp.series(
    cleandist,
    gulp.parallel(copyApp2Dist, scss, adminScss, feuiOptimizeImages, beuiOptimizeImages),
    purgeCss
);

// -------------------------
// Exports
// -------------------------
module.exports = {
    cleandist,
    feuicleandir,
    beuicleandir,
    copyApp2Dist,
    feuicopydir,
    beuicopydir,
    scss,
    adminScss,
    purgeCss,
    feuiOptimizeImages,
    beuiOptimizeImages,
    feuicleanup,
    beuicleanup,
    build
};
