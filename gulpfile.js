const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const sourcemaps = require('gulp-sourcemaps');
const autoprefixer = require('gulp-autoprefixer');
const imagemin = require('gulp-imagemin');
const clean = require('gulp-clean');
const changed = require('gulp-changed');
const log = require('fancy-log');
const browserSync = require('browser-sync').create();
const path = require('path');

const APP_INTERNAL_URL = process.env.APP_INTERNAL_URL;

// --------------------
// Paths
// --------------------
const paths = {
  src: 'src/',
  dist: 'dist/',
  scss: 'src/assets/scss/**/*.scss',
  cssDest: 'dist/assets/css',
  images: 'src/assets/images/**/*',
  imgDest: 'dist/assets/images'
};

// --------------------
// Clean dist
// --------------------
function cleandist() {
  return gulp
    .src(paths.dist, { allowEmpty: true, read: false })
    .pipe(clean());
}

// --------------------
// Copy files
// --------------------
function copyapp2dist() {
  return gulp
    .src(
      [
        `${paths.src}**/*`,
        `!${paths.src}**/*.scss`
      ],
      { allowEmpty: true, dot: true }
    )
    .pipe(changed(paths.dist))
    .pipe(gulp.dest(paths.dist));
}

// --------------------
// SCSS compilation
// --------------------
function styles() {
  return gulp.src(paths.scss, { sourcemaps: true })
    .pipe(sass({ outputStyle: 'expanded', quietDeps: true }).on('error', sass.logError))
    .pipe(autoprefixer({ cascade: false }))
    .pipe(gulp.dest(paths.cssDest, { sourcemaps: '.' }))
    .pipe(browserSync.stream());
}

// --------------------
// Images
// --------------------
function images() {
  return gulp.src(paths.images)
    .pipe(imagemin())
    .pipe(gulp.dest(paths.imgDest));
}

// --------------------
// BrowserSync / Watch
// --------------------
function serve(done) {
  browserSync.init({
    proxy: APP_INTERNAL_URL,
    open: false,
    notify: false
  });
  done();
}

function watchFiles() {
  gulp.watch(paths.scss, styles);
  gulp.watch(`${paths.src}**/*`).on('change', (file) => {
    log(`File changed: ${path.basename(file)}`);
    browserSync.reload();
  });
}

// --------------------
// Build & Dev tasks
// --------------------
const build = gulp.series(
  cleandist,
  copyapp2dist,
  gulp.parallel(styles, images)
);

const watch = gulp.series(build, serve, watchFiles);

// --------------------
// Exports
// --------------------
exports.cleandist = cleandist;
exports.copyapp2dist = copyapp2dist;
exports.styles = styles;
exports.images = images;
exports.build = build;
exports.watch = watch;
exports.default = build;
