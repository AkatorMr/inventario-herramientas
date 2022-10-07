// vue.config.js
module.exports = {
    devServer: {
        proxy: {
            '/api': {
                target: 'http://localhost:80/',
                changeOrigin: true
              }
        }
    },
    outputDir: "build/"
  }