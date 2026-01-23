/*
 * Watch files with BrowserSync in dev mode
*/

const gulp = require('gulp');
const browserSync = require('browser-sync').create();
const cached = require('gulp-cached');
const sass = require('gulp-sass')(require('sass'));
const log = require('fancy-log');
const chalk = require('chalk');
const path = require('path');

// -------------------------
// Paths
// -------------------------
const APP_INTERNAL_URL = process.env.APP_INTERNAL_URL;

const bs = {
  src: path.resolve(__dirname, '../src/') + '/',
  dist: path.resolve(__dirname, '../dist/') + '/'
};

const asts = {
  scss_dir: 'assets/scss/', scss_files: 'assets/scss/*.scss',
  css_dir: 'assets/css/', css_files: 'assets/css/*.css',
  js_dir: 'assets/js/', js_files: 'assets/js/*.js'
};

// -------------------------
// Browser Sync
// -------------------------
function browserSyncInit(done) {
  browserSync.init({
    proxy: APP_INTERNAL_URL, // local dev server URL
    port: 3000,              // BrowserSync serves here
    open: false,             // set to false in headless/Docker
    notify: false
  });
  done();
}

// -------------------------
// SCSS compilation
// -------------------------
function compileSass() {
  return gulp.src(bs.src + asts.scss_files)
    .pipe(cached('assetscss', { optimizeMemory: true }))
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest(bs.src + asts.css_dir))
    .pipe(browserSync.stream()); // inject CSS changes without full reload
}

// -------------------------
// Watchers
// -------------------------
function watchFiles() {
  // SCSS files
  gulp.watch(bs.src + asts.scss_files, compileSass);

  // CSS / JS / PHP files - reload browser on changes
  gulp.watch([bs.src + asts.css_files, bs.src + asts.js_files, bs.src + 'index.php'])
    .on('change', (file) => {
      log(`${chalk.green(path.basename(file))} was changed.`);
      browserSync.reload(); // call reload directly, no need for 'done'
    });
}

// -------------------------
// Dev Build Task
// -------------------------
const watchchanges = gulp.series(
  browserSyncInit,
  compileSass,
  watchFiles
);

// -------------------------
// Exports
// -------------------------
module.exports = {
  compileSass,
  browserSyncInit,
  watchFiles,
  watchchanges
};
