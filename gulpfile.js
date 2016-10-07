var gulp = require ('gulp'),
    uglify = require('gulp-uglify'),
    autoprefixer = require('gulp-autoprefixer'),
    sass = require('gulp-sass'),
    browserSync = require('browser-sync').create()

// --------------  scss  --------------   //
gulp.task('css', function(){
   gulp.src('assets/sass/style.scss') // select all css files in css/ folder
        .pipe(sass({outputStyle : 'compressed'}))
        .pipe(autoprefixer('last 2 versions'))
        .pipe(gulp.dest('assets/css/'))
        .pipe(browserSync.stream());
});

// --------------  watch changes  --------------   //
gulp.task('watch', function(){
    gulp.watch('assets/sass/style.scss', ['css']);
    gulp.watch('assets/sass/**/*', ['css']);
});


// what to run when 'gulp' is entered in the terminal //
gulp.task('default', ['css', 'watch']);
