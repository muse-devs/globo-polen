var gulp = require("gulp");
const { dest } = require("gulp");
const uglify = require("gulp-uglify-es").default;
const log = require("fancy-log");

function themeJsUglify() {
	log("Uglify nos arquivos do tema");
	return gulp.src("js/*.js").pipe(uglify()).pipe(dest("js/min"));
}

exports.compressjs = themeJsUglify;
