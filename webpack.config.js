const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const path = require('path');

module.exports = (env, argv) => {
    const isProd = argv.mode !== 'development';

    return {
        mode: isProd ? 'production' : 'development',
        devtool: isProd ? false : 'source-map',

        entry: './js/main.js',
        output: {
            path: path.resolve(__dirname, 'dist'),
            filename: 'js/main.bundle.js',
            // Rutas relativas al CSS generado (dist/css/) para fuentes e imágenes
            publicPath: '../',
            clean: true
        },

        // jQuery lo provee WordPress (handle 'jquery'); así no se carga dos veces
        externals: {
            jquery: 'jQuery'
        },

        module: {
            rules: [
                {
                    test: /\.js$/,
                    exclude: /node_modules/,
                    type: 'javascript/auto'
                },
                {
                    test: /\.(sa|sc|c)ss$/,
                    use: [
                        MiniCssExtractPlugin.loader,
                        { loader: 'css-loader', options: { sourceMap: !isProd } },
                        {
                            loader: 'sass-loader',
                            options: {
                                sourceMap: !isProd,
                                sassOptions: {
                                    quietDeps: true,
                                    silenceDeprecations: ['import', 'global-builtin', 'color-functions', 'mixed-decls', 'slash-div']
                                }
                            }
                        }
                    ]
                },
                {
                    test: /\.(woff2?|eot|ttf|otf)$/,
                    type: 'asset/resource',
                    generator: { filename: 'fonts/[name][ext]' }
                },
                {
                    test: /\.(jpe?g|png|gif|svg|webp)$/,
                    type: 'asset',
                    parser: { dataUrlCondition: { maxSize: 4 * 1024 } },
                    generator: { filename: 'images/[name].[contenthash:8][ext]' }
                }
            ]
        },

        optimization: {
            minimize: isProd,
            minimizer: [
                new TerserPlugin({
                    extractComments: false,
                    terserOptions: {
                        format: { comments: false },
                        compress: { drop_console: true, passes: 2 }
                    }
                }),
                new CssMinimizerPlugin({
                    minimizerOptions: {
                        preset: ['default', { discardComments: { removeAll: true } }]
                    }
                })
            ]
        },

        plugins: [
            new MiniCssExtractPlugin({
                filename: 'css/main.bundle.css'
            })
        ],

        performance: { hints: false }
    };
};
