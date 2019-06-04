/* eslint-env node */

module.exports = {

    entry: {
        'app/module-edit': './app/module-edit'
    },

    output: {
        filename: './[name].min.js'
    },

    externals: {
        'jquery': 'jQuery'
    }

};
