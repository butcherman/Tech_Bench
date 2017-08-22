var gulp = require('gulp'),
    bower = require('gulp-bower'),
    file = require('gulp-file');
 
var config = {
     bowerDir: './vendor'
}

gulp.copy=function(src,dest){
    return gulp.src(src, {base:"."})
        .pipe(gulp.dest(dest));
};

gulp.task('config', function() {
    var str = '[core]\n logo = "TechBenchLogo.png"\n baseURL = ""\n title = "Tech Bench"\n[upload_paths]\n maxUpload = "500"';
        return gulp.src('config').pipe(file('config.ini', str)).pipe(gulp.dest('config'));
});

gulp.task('bower', function() {
    return bower()
        .pipe(gulp.dest(config.bowerDir))
});

gulp.task('moveFiles', ['bower'], function() { 
        gulp.src(['vendor/jquery/jquery.min.js'])
            .pipe(gulp.dest('public/source/lib/jquery')),
        gulp.src(['vendor/bootstrap/dist/**/*'])
            .pipe(gulp.dest('public/source/lib/bootstrap/')),
        gulp.src(['vendor/bootstrap-toggle/js/*', 'vendor/bootstrap-toggle/css/*'])
            .pipe(gulp.dest('public/source/lib/bootstrap-toggle')),
        gulp.src(['vendor/jquery.tablesorter/dist/js/jquery.tablesorter.combined.min.js', 'vendor/jquery.tablesorter/dist/js/extras/jquery.tablesorter.pager.min.js'])
            .pipe(gulp.dest('public/source/lib/tablesorter')),
        gulp.src(['vendor/jquery-validation/dist/*'])
            .pipe(gulp.dest('public/source/lib/jquery-validation')),
        gulp.src(['vendor/tinymce/**/*.*', '!vendor/tinymce/bower.*', '!vendor/tinymce/.bower'])
            .pipe(gulp.dest('public/source/lib/tinymce')),
        gulp.src(['vendor/seiyria-bootstrap-slider/dist/**/*'])
            .pipe(gulp.dest('public/source/lib/bootstrap-slider')),
        gulp.src(['vendor/filesize/lib/*'])
            .pipe(gulp.dest('public/source/lib/filesize')),
        gulp.src(['vendor/select2/dist/**/*'])
            .pipe(gulp.dest('public/source/lib/select2')),
        gulp.src(['vendor/tinymce-placeholder-attribute/placeholder/*'])
            .pipe(gulp.dest('public/source/lib/tinymce/plugins/placeholder/')),
        gulp.src(['vendor/multiselect-two-sides/dist/js/*'])
            .pipe(gulp.dest('public/source/lib/multiselect-two-sides/'))
});

gulp.task('default', ['bower', 'moveFiles', 'config']);
