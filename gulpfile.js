const gulp = require('gulp'), flowRemoveTypes = require('gulp-flow-remove-types'), rename = require('gulp-rename'),
    browserSync = require('browser-sync').create(),
    reload = browserSync.reload, settings = require('./settings')
;

gulp.task('flow_remove_types', async function () {
    gulp.src('public/custom-assets/js/custom-src.js')
        .pipe(flowRemoveTypes({
            pretty: true
        }))
        .pipe(rename('custom.js'))
        .pipe(gulp.dest('public/custom-assets/js'));
});

gulp.task('watch', async function () {
    browserSync.init({
        notify: false,
        proxy: settings.urlToPreview,
        ghostMode: false
    });
    gulp.watch('public/custom-assets/js/custom-src.js', gulp.series('flow_remove_types')).on('change', reload);
    //gulp.watch('public/custom-assets/css/**/*.css').on('change', reload);
});

gulp.task('default', gulp.series('watch'));
