const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const path = require('path'); // You might need path for output directory later, good to include

module.exports = {
    // Set mode to 'development' for watch mode.
    // Webpack automatically optimizes differently for 'production'.
    mode: 'development',

    entry: './js/main.js',
    output: {
        // __dirname points to the current directory of webpack.config.js
        path: path.resolve(__dirname, 'js'), // Use path.resolve for robust pathing
        filename: 'main.bundle.js'
    },

    module: {
        rules: [
            {
                test: /\.scss$/,
                use: [
                    { loader: MiniCssExtractPlugin.loader },
                    { loader: 'css-loader' },
                    { loader: 'sass-loader' }
                ]
            },
            {
                test: /\.(jpe?g|png|gif|woff|woff2|eot|ttf|svg)(\?[a-z0-9=.]+)?$/,
                // Correct way to pass options to a loader
                loader: 'url-loader',
                options: {
                    limit: 100000 // The 'limit' value moved to 'options'
                }
            }
        ]
    },

    plugins: [
        new MiniCssExtractPlugin({
            filename: '../css/main.bundle.css'
        })
    ]
};