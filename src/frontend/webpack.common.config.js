const path = require('path');
const glob = require('glob');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const ImageminPlugin = require('imagemin-webpack-plugin').default;

module.exports = {
  entry: [
    './src/index.js'
  ],
  module: {
    rules: [
      {
        enforce: 'pre',
        test: /\.(js|jsx)?$/,
        loader: 'eslint-loader',
        exclude: /node_modules/
      },
      {
        exclude: /node_modules/,
        loader: 'babel-loader',
        options: {
          presets: ['react', 'es2015', 'stage-2']
        }
      },
      {
        test: /\.styl$/,
        use: ExtractTextPlugin.extract({
          fallback: 'style-loader',
          use: ['css-loader', 'stylus-loader']
        })
      },
      {
        test: /\.pug$/,
        loader: 'pug-loader',
      },
      {
        test: /\.(png|jpg|gif|svg|ico)$/,
        loader: 'file-loader',
        options: {
          name: 'public/[name].[ext]?[hash]'
        }
      },
    ]
  },
  resolve: {
    extensions: ['.js', '.jsx','.css', '.styl', '.pug']
  },
  plugins: [
    new ImageminPlugin({
      externalImages: {
        context: 'src',
        sources: glob.sync('src/img/**/*'),
        destination: 'dist',
      }
    })
  ]
};
