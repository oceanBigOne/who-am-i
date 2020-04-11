const path = require("path"),
  resolve = path.resolve,
  MiniCssExtractPlugin = require("mini-css-extract-plugin"),
  // CleanWebpackPlugin = require('clean-webpack-plugin'),
  UglifyJsPlugin = require("uglifyjs-webpack-plugin"),
  OptimizeCSSAssetsPlugin = require("optimize-css-assets-webpack-plugin");
const CopyPlugin = require("copy-webpack-plugin");

let entries = {};
try {
  entries = require(resolve(__dirname, "../assets", "entry.js"));
} catch (e) {
  console.error("Entry file not found.");
  process.exit(1);
}
let keys = Object.keys(entries);
if (!keys[0]) {
  console.error("Entry file is empty.");
  process.exit(0);
}
keys.map(entry => {
  entries[entry] = resolve(__dirname, "../assets", entries[entry]);
});

module.exports = {
  entry: entries,
  output: {
    filename: "[name].js",
    path: resolve(__dirname, ".", "../public/dist")
  },
  optimization: {
    minimizer: [
      new UglifyJsPlugin({
        cache: true,
        parallel: true,
        sourceMap: true
      }),
      new OptimizeCSSAssetsPlugin({})
    ]
  },
  module: {
    rules: [
      {
        test: /\.jsx?$/,
        exclude: /(node_modules|bower_components)/,
        use: [
          {
            loader: "babel-loader",
            options: {
              presets: ["@babel/preset-env", "@babel/react"]
            }
          }
        ]
      },
      {
        test: /\.(sa|sc|c)ss$/,
        use: [
          {
            loader: MiniCssExtractPlugin.loader
          },
          {
            loader: "css-loader"
          },
          "sass-loader"
        ]
      },
      {
        test: /\.(png|gif|jpg|jpeg|woff|woff2|eot|ttf|otf|svg)$/i,
        use: [
          {
            loader: "file-loader",
            options: {
              name: "[hash].[ext]"
            }
          }
        ]
      }
    ]
  },
  plugins: [
    // new CleanWebpackPlugin(['out'], {
    //     root: resolve(__dirname, '..')
    // }),
    new MiniCssExtractPlugin("[name].css"),
    new CopyPlugin([
      /* GENERIC */
      { from: "assets/images/", to: "images" },
      {
        from: "node_modules/jquery/dist/jquery.min.js",
        to: "js/jquery.min.js"
      },
      {
        from: "node_modules/jquery/dist/jquery.min.map",
        to: "js/jquery.min.map"
      },
      {
        from: "node_modules/bootstrap/dist/js/bootstrap.bundle.min.js",
        to: "js/bootstrap.bundle.min.js"
      } /* bundle file contain popper.js */,
      {
        from: "node_modules/bootstrap/dist/js/bootstrap.bundle.min.js.map",
        to: "js/bootstrap.bundle.min.js.map"
      },
      {
        from: "node_modules/@fortawesome/fontawesome-free/webfonts",
        to: "fonts/fontawesome"
      },
      /* CUSTOMS THEMES */
      {
        from: "assets/styles/@themes/localhost/images",
        to: "localhost/images"
      },
      { from: "assets/styles/@themes/localhost/fonts", to: "localhost/fonts" },
      { from: "assets/styles/@themes/mycustom/images", to: "mycustom/images" },
      { from: "assets/styles/@themes/mycustom/fonts", to: "mycustom/fonts" },
      { from: "assets/styles/@themes/mymata/images", to: "mymata/images" },
      { from: "assets/styles/@themes/mymata/fonts", to: "mymata/fonts" }
    ])
  ]
};
