/* 
 * Gulp CSS and JS optimizer
 * - For BE and FE UIs
 */

const gulp = require('gulp');
const concat = require('gulp-concat');
const rename = require('gulp-rename');
const uglify = require('gulp-uglify');
const autoprefixer = require('gulp-autoprefixer');
const purgecss = require('gulp-purgecss');
const cleanCSS = require('gulp-clean-css'); // replaces stripCssComments + csso
const gulpif = require('gulp-if');
const useref = require('gulp-useref');
const path = require('path');
const merge = require('merge-stream');

// --------------------------------------------------
// Env & paths
// --------------------------------------------------

const bs = {
  src: path.resolve(__dirname, '../src/') + '/',
  dist: path.resolve(__dirname, '../dist/') + '/'
};

const asts = {
  css_dir: 'assets/css/',
  js_dir: 'assets/js/'
};

const admin = {
  css_dir: 'admin/assets/css/',
  js_dir: 'admin/assets/js/',
  incl_dir: 'admin/includes/'
};

const tmp_dir = 'tmp/';
const concat_file = 'concat';
const concat_core_file = 'concatcore';
const concat_cust_file = 'concatcust';

// --------------------------------------------------
// Safelists (formerly guncss ignore)
// --------------------------------------------------

const feuiSafelist = {
  greedy: [
    /navbar/, /modal/, /dropdown/, /tooltip/, /popover/, /carousel/,
    /fade/, /collaps/, /active/, /alert/, /has-(success|warning|error)/,
    /help-block/, /fa-angle/, /cb-hidden/, /rg-/, /es-nav/
  ]
};

const beuiSafelist = {
  greedy: [
    /navbar/, /modal/, /dropdown/, /tooltip/, /popover/, /notification/,
    /error/, /active/, /ls_/, /grow/
  ]
};

// --------------------------------------------------
// Helper: PurgeCSS
// --------------------------------------------------

function optimizeCss(contentPaths, safelist) {
  return purgecss({ content: contentPaths, safelist });
}

// --------------------------------------------------
// Step-by-step CSS pipeline
// --------------------------------------------------

function feuiconcatcss() {
  return gulp.src([
    bs.dist + asts.css_dir + 'bootstrap.min.css',
    bs.dist + asts.css_dir + 'ie10-viewport-bug.css',
    bs.dist + asts.css_dir + 'agency.css',
    bs.dist + asts.css_dir + 'elastislide.css',
    bs.dist + asts.css_dir + 'gallery.css',
    bs.dist + asts.css_dir + 'share-buttons.css',
    bs.dist + asts.css_dir + 'font-awesome.css',
    bs.dist + asts.css_dir + 'recaptcha.css'
  ])
    .pipe(concat(`${concat_file}.css`))
    .pipe(gulp.dest(bs.dist + asts.css_dir));
}

function beuiconcatcss() {
  return gulp.src([
    bs.dist + admin.css_dir + 'bootstrap.min.css',
    bs.dist + admin.css_dir + 'font-awesome.css',
    bs.dist + admin.css_dir + 'admin.css'
  ])
    .pipe(concat(`${concat_file}.css`))
    .pipe(gulp.dest(bs.dist + admin.css_dir));
}

function feuiuncss() {
  return gulp.src(bs.dist + asts.css_dir + `${concat_file}.css`)
    .pipe(optimizeCss([`${bs.dist}/**/*.php`, `${bs.dist}/**/*.html`], feuiSafelist))
    .pipe(gulp.dest(bs.dist + asts.css_dir + tmp_dir));
}

function beuiuncss() {
  return gulp.src(bs.dist + admin.css_dir + `${concat_file}.css`)
    .pipe(optimizeCss([`${bs.dist}/admin/**/*.php`], beuiSafelist))
    .pipe(gulp.dest(bs.dist + admin.css_dir + tmp_dir));
}

function feuiminifycss() {
  return gulp.src(bs.dist + asts.css_dir + tmp_dir + `${concat_file}.css`)
    .pipe(autoprefixer({ cascade: false }))
    .pipe(cleanCSS({ level: 2 }))
    .pipe(rename({ basename: 'feui-main', extname: '.min.css' }))
    .pipe(gulp.dest(bs.dist + asts.css_dir));
}

function beuiminifycss() {
  return gulp.src(bs.dist + admin.css_dir + tmp_dir + `${concat_file}.css`)
    .pipe(autoprefixer({ cascade: false }))
    .pipe(cleanCSS({ level: 2 }))
    .pipe(rename({ basename: 'beui-main', extname: '.min.css' }))
    .pipe(gulp.dest(bs.dist + admin.css_dir));
}

// --------------------------------------------------
// Step-by-step JS pipeline
// --------------------------------------------------

function feuiconcatjs() {
  const core = gulp.src([
    bs.dist + asts.js_dir + 'jquery.min.js',
    bs.dist + asts.js_dir + 'bootstrap.min.js',
    bs.dist + asts.js_dir + 'ie10-viewport-bug.js'
  ])
    .pipe(concat(`${concat_core_file}.js`))
    .pipe(gulp.dest(bs.dist + asts.js_dir));

  const custom = gulp.src([
    bs.dist + asts.js_dir + 'jquery.easing.min.js',
    bs.dist + asts.js_dir + 'jquery.tmpl.min.js',
    bs.dist + asts.js_dir + 'elastislide.js',
    bs.dist + asts.js_dir + 'gallery.js',
    bs.dist + asts.js_dir + 'classie.js',
    bs.dist + asts.js_dir + 'cbpAnimatedHeader.min.js',
    bs.dist + asts.js_dir + 'jqBootstrapValidation.js',
    bs.dist + asts.js_dir + 'contact_me.js',
    bs.dist + asts.js_dir + 'agency.js',
    bs.dist + asts.js_dir + 'share-buttons.js'
  ])
    .pipe(concat(`${concat_cust_file}.js`))
    .pipe(gulp.dest(bs.dist + asts.js_dir));

  return merge(core, custom);
}

function beuiconcatjs() {
  const core = gulp.src([
    bs.dist + admin.js_dir + 'jquery.min.js',
    bs.dist + admin.js_dir + 'bootstrap.min.js',
    bs.dist + admin.js_dir + 'ie10-viewport-bug.js'
  ])
    .pipe(concat(`${concat_core_file}.js`))
    .pipe(gulp.dest(bs.dist + admin.js_dir));

  const custom = gulp.src(bs.dist + admin.js_dir + 'admin.js')
    .pipe(concat(`${concat_cust_file}.js`))
    .pipe(gulp.dest(bs.dist + admin.js_dir));

  return merge(core, custom);
}

function feuiminifyjs() {
  return gulp.src([
      bs.dist + asts.js_dir + `${concat_core_file}.js`,
      bs.dist + asts.js_dir + `${concat_cust_file}.js`
    ], { allowEmpty: true })
    .pipe(uglify())
    .pipe(rename(path => {
      if (path.basename === concat_core_file) path.basename = 'feui-core';
      else if (path.basename === concat_cust_file) path.basename = 'feui-cust';
    }))
    .pipe(gulp.dest(bs.dist + asts.js_dir));
}

function beuiminifyjs() {
  return gulp.src([
      bs.dist + admin.js_dir + `${concat_core_file}.js`,
      bs.dist + admin.js_dir + `${concat_cust_file}.js`,
      bs.dist + admin.js_dir + 'beui-ajax.min.js'
    ], { allowEmpty: true })
    .pipe(uglify())
    .pipe(rename(path => {
      if (path.basename === concat_core_file) path.basename = 'beui-core';
      else if (path.basename === concat_cust_file) path.basename = 'beui-cust';
    }))
    .pipe(gulp.dest(bs.dist + admin.js_dir));
}

// --------------------------------------------------
// One-step optimizer (useref)
// --------------------------------------------------

function feuiOptimizeCssJs() {
  return gulp.src(bs.src + 'index.php')
    .pipe(useref())
    .pipe(gulpif('*.css', optimizeCss([`${bs.dist}/**/*.php`, `${bs.dist}/**/*.html`], feuiSafelist)))
    .pipe(gulpif('*.css', autoprefixer({ cascade: false })))
    .pipe(gulpif('*.css', cleanCSS({ level: 2 })))
    .pipe(gulpif('*.js', uglify()))
    .pipe(gulp.dest(bs.dist));
}

function beuiOptimizeCssJs() {
  gulp.src([
    bs.src + admin.incl_dir + '_head_elements.php',
    bs.src + admin.incl_dir + '_footer.php'
  ])
    .pipe(useref())
    .pipe(gulpif('*.css', optimizeCss([`${bs.dist}/admin/**/*.php`], beuiSafelist)))
    .pipe(gulpif('*.css', autoprefixer({ cascade: false })))
    .pipe(gulpif('*.css', cleanCSS({ level: 2 })))
    .pipe(gulpif('*.js', uglify()))
    .pipe(gulp.dest(bs.dist + admin.incl_dir));

  return gulp.src(bs.dist + admin.js_dir + 'beui-ajax.min.js')
    .pipe(uglify())
    .pipe(gulp.dest(bs.dist + admin.js_dir));
}

// --------------------------------------------------
// Exports (Gulp 4)
// --------------------------------------------------

// Step-by-step
exports.feuicss = gulp.series(feuiconcatcss, feuiuncss, feuiminifycss);
exports.beuicss = gulp.series(beuiconcatcss, beuiuncss, beuiminifycss);
exports.feuijs = gulp.series(feuiconcatjs, feuiminifyjs);
exports.beuijs = gulp.series(beuiconcatjs, beuiminifyjs);

// One-step
exports.feuicssjs = feuiOptimizeCssJs;
exports.beuicssjs = beuiOptimizeCssJs;
