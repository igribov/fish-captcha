const webpack = require('webpack');
const path = require('path');
const merge = require('webpack-merge');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const ExtractTextPlugin = require('extract-text-webpack-plugin');

const common = require('./webpack.common.config.js');

const devConfig = {
  output: {
    path: path.join(__dirname, 'dist'),
    publicPath: '/',
    filename: 'bundle.js'
  },
  devtool: 'inline-source-map',
  devServer: {
    historyApiFallback: true,
    port: 8000,
    contentBase: './dist'
  },
  plugins: [
    new HtmlWebpackPlugin({
      template: 'src/index.pug',
      hash: true,
    }),
    new webpack.DefinePlugin({
      NODE_ENV: JSON.stringify('dev')
    }),
    new ExtractTextPlugin('[name][hash].css'),
  ]
};

module.exports = merge(common, devConfig);