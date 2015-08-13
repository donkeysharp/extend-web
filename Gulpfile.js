var gulp = require('gulp');
var uglify = require('gulp-uglify');
var clean = require('gulp-clean');
var source = require('vinyl-source-stream');
var browserify = require('browserify');
var watchify = require('watchify');
var reactify = require('reactify');
var streamify = require('gulp-streamify');

var path = {
  MINIFIED_OUT: 'build.min.js',
  OUT: 'build.js',
  DEST: 'dist',
  DEST_BUILD: './public/assets/js',
  DEST_SRC: './frontend/.dest/js/src',
  ENTRY_POINT: './frontend/js/main.jsx'
};

gulp.task('watch', function() {
  var watcher  = watchify(browserify({
    entries: [path.ENTRY_POINT],
    transform: [reactify],
    standalone: 'MyApp'
  }));

  return watcher.on('update', function () {
    watcher.bundle()
      .pipe(source(path.MINIFIED_OUT))
      .pipe(gulp.dest(path.DEST_BUILD));
      console.log('Updated');
    })
    .bundle()
    .pipe(source(path.MINIFIED_OUT))
    .pipe(gulp.dest(path.DEST_BUILD));
});

gulp.task('build', function(){
  browserify({
    entries: [path.ENTRY_POINT],
    transform: [reactify],
    standalone: 'MyApp'
  })
    .bundle()
    .pipe(source(path.MINIFIED_OUT))
    .pipe(gulp.dest(path.DEST_SRC));


});
gulp.task('obfuscate', function() {
  gulp.src(path.DEST_SRC + '/*.js')
    .pipe(uglify())
    .pipe(gulp.dest(path.DEST_BUILD));
});


gulp.task('production', ['build', 'obfuscate']);

gulp.task('default', ['watch']);
