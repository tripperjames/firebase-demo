/* eslint-env node */

module.exports = {

    target: 'webworker',

    entry: {
        'app/worker': 'yootheme/packages/styler/src/lib/worker'
    },

    output: {
        filename: './[name].min.js'
    },

    node: {
        fs: 'empty'
    },

    performance: {
        hints: false
    }

};
