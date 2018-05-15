const merge = require('webpack-merge');
const webpack = require('webpack');
const UglifyJSPlugin = require('uglifyjs-webpack-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const common = require('./webpack.common.config.js');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const path = require('path');

const prodConfig = {
  output: {
    path: path.join(__dirname, 'dist'),
    publicPath: '/',
    filename: 'fishcaptcha.js'
  },
  plugins: [
    new ExtractTextPlugin('fishcaptcha.css'),
    new CleanWebpackPlugin(['dist']),
    new webpack.DefinePlugin({
      NODE_ENV: JSON.stringify('production'),
      'process.env.NODE_ENV': JSON.stringify('production')
    }),
    //new UglifyJSPlugin()
  ],
  devtool: 'inline-source-map',
};

module.exports = merge(common, prodConfig);
