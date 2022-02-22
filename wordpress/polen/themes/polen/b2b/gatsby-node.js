const path = require("path");
const fs = require("fs");

exports.onPreInit = () => {
  if (process.argv[2] === "build") {
    fs.rmdirSync(path.join(__dirname, "empresas"), { recursive: true });
    fs.renameSync(
      path.join(__dirname, "public"),
      path.join(__dirname, "public_dev")
    );
  }
};

exports.onPostBuild = () => {
  fs.renameSync(
    path.join(__dirname, "public"),
    path.join(__dirname, "empresas")
  );
  fs.renameSync(
    path.join(__dirname, "public_dev"),
    path.join(__dirname, "public")
  );
};
