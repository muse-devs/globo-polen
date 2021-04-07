var gulp = require("gulp");
const { dest } = require("gulp");
const uglify = require("gulp-uglify-es").default;
const log = require("fancy-log");
var iconfont = require('gulp-iconfont');
var iconfontCss = require('gulp-iconfont-css');

var fontName = 'MuseIcons';

gulp.task('iconfont', function(){
  gulp.src(['icons/*.svg'])
    .pipe(iconfontCss({
      fontName: fontName,
      path: 'templates/_icons.scss',
      targetPath: '../scss/_icons.scss',
      fontPath: 'fonts/'
    }))
    .pipe(iconfont({
      fontName: fontName,
      normalize: true,
      fontHeight: 1000
     }))
    .pipe(gulp.dest('fonts/'));
});

function themeJsUglify() {
	log("Uglify nos arquivos do tema");
	return gulp.src("js/*.js").pipe(uglify()).pipe(dest("js/min"));
}

exports.compressjs = themeJsUglify;
