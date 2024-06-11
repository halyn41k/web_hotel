const { defineConfig } = require('@vue/cli-service');
const webpack = require('webpack'); 
const path = require('path');

module.exports = defineConfig({
  transpileDependencies: true,
  configureWebpack: {
    plugins: [
      new webpack.DefinePlugin({
        '__VUE_PROD_HYDRATION_MISMATCH_DETAILS__': JSON.stringify(true)
      })
    ]
  },
  devServer: {
    proxy: {
      '/api': {
        target: 'http://localhost:8080',
      }
    }
  },
  chainWebpack: config => {
    config.plugin('html').tap(args => {
      args[0].template = path.resolve(__dirname, 'src/index.html'); // Шлях до src/index.html
      return args;
    });
  }
});
