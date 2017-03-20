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
    runSequence         = require('run-sequence'),
    requireDir          = require('require-dir')
    ;

/* include config from tasks folder */
var tasks = requireDir('tasks');

/* note: variable scope
*  local variables defined here cannot be accessed in tasks/*.js files and vice-versa unless you export and import them (require).
*  for example: var path = 'src/assets/css/' cannot be accessed in tasks/build.js
* */

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

gulp.task('default', function() {
    console.log('default task')
});





