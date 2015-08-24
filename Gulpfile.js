'use strict';
var gulp = require('gulp');
var uglify = require('gulp-uglify');
var compass = require('gulp-compass');
var clean = require('gulp-clean');
var minifycss = require('gulp-minify-css');
var rename = require('gulp-rename');
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

gulp.task('build_js', function(){
  browserify({
    entries: [path.ENTRY_POINT],
    transform: [reactify],
    standalone: 'MyApp'
  })
  .bundle()
  .pipe(source(path.MINIFIED_OUT))
  .pipe(gulp.dest(path.DEST_SRC));

});

gulp.task('build_css', function(){
  gulp.src('./frontend/scss/**/*.scss')
    .pipe(compass({
      css: './public/assets/css',
      sass: './frontend/scss'
    }))
    .pipe(gulp.dest('./public/assets/css'))
    .pipe(rename({ suffix: '.min' }))
    .pipe(minifycss())
    .pipe(gulp.dest('./public/assets/css'));
});

gulp.task('obfuscate', function() {
  console.log('OBFUSCATING!!');
  gulp.src(path.DEST_SRC + '/**/*.js')
    .pipe(uglify())
    .pipe(gulp.dest(path.DEST_BUILD));
});

gulp.task('watch', function() {
  gulp.watch('./frontend/scss/**/*.scss', ['build_css']);

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


gulp.task('build', ['build_js', 'build_css', 'obfuscate']);
gulp.task('default', ['build_js', 'build_css', 'watch']);
