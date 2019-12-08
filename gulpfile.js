const gulp = require('gulp'), flowRemoveTypes = require('gulp-flow-remove-types'), rename = require('gulp-rename');

gulp.task('flow_remove_types', async function () {
    gulp.src('public/custom-assets/js/custom-src.js')
        .pipe(flowRemoveTypes({
            pretty: true
        }))
        .pipe(rename('custom.js'))
        .pipe(gulp.dest('public/custom-assets/js'));
});

gulp.task('watch', async function () {
    gulp.watch('public/custom-assets/js/custom-src.js', gulp.series('flow_remove_types'));
});

gulp.task('default', gulp.series('watch'));

/*gulp.task('styles', function () {
    return gulp.src(settings.themeLocation + 'css/style.css')
        .pipe(postcss([cssImport, mixins, cssvars, nested, rgba, colorFunctions, autoprefixer]))
        .on('error', (error) => console.log(error.toString()))
        .pipe(gulp.dest(settings.themeLocation));
});*/

/*gulp.task('scripts', function (callback) {
    webpack(require('./webpack.config.js'), function (err, stats) {
        if (err) {
            console.log(err.toString());
        }

        console.log(stats.toString());
        callback();
    });
});*/

/*gulp.task('watch', function () {
    browserSync.init({
        notify: false,
        proxy: settings.urlToPreview,
        ghostMode: false
    });
    /!*gulp.watch('./!**!/!*.php', function() {
      browserSync.reload();
    });*!/
    gulp.watch(['./!**!/!*.php', './!**!/!*.html', './public/custom-assets/!**!/!*.css', './public/custom-assets/js/!**!/!*.js']).on('change', reload);
    //gulp.watch(settings.themeLocation + 'public/custom-assets/css/!**!/!*.css').on('change', reload);
    //gulp.watch(settings.themeLocation + 'js/scripts.js').on('change', reload);
    //gulp.watch(settings.themeLocation + 'public/custom-assets/css/!**!/!*.css', gulp.parallel('waitForStyles'));
    //gulp.watch([settings.themeLocation + 'public/custom-assets/js/modules/!*.js', settings.themeLocation + 'js/scripts.js'], gulp.parallel('waitForScripts'));
});*/

/*
gulp.task('waitForStyles', gulp.series('styles', function () {
    return gulp.src(settings.themeLocation + 'style.css')
        .pipe(browserSync.stream());
}))

gulp.task('waitForScripts', gulp.series('scripts', function (cb) {
    browserSync.reload();
    cb()
}))*/
