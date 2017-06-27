var gulp = require('gulp'),
    bower = require('gulp-bower');
 
var config = {
     bowerDir: './vendor'
}

gulp.task('bower', function() {
    return bower()
        .pipe(gulp.dest(config.bowerDir))
});

gulp.task('copy', ['bower'], function() { 
        gulp.src(['vendor/jquery/jquery.min.js'])
            .pipe(gulp.dest('public/source/lib/jquery')),
        gulp.src(['vendor/bootstrap/dist/**/*'])
            .pipe(gulp.dest('public/source/lib/bootstrap/')),
        gulp.src(['vendor/bootstrap-toggle/js/*', 'vendor/bootstrap-toggle/css/*'])
            .pipe(gulp.dest('public/source/lib/bootstrap-toggle')),
        gulp.src(['vendor/tablesorter/jquery.*'])
            .pipe(gulp.dest('public/source/lib/tablesorter')),
        gulp.src(['vendor/jquery-validation/dist/*'])
            .pipe(gulp.dest('public/source/lib/jquery-validation')),
        gulp.src(['vendor/tinymce/*', '!vendor/tinymce/bower', '!vendor/tinymce/.bower'])
            .pipe(gulp.dest('public/source/lib/tinymce'))
});

gulp.task('default', ['bower', 'copy']);
