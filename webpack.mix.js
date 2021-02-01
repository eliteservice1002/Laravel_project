const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
  .vue()
  // .react()
  .extract()

  .js('resources/js/app.js', 'public/js')
  .postCss('resources/css/app.css', 'public/css', [
    require('tailwindcss'),
  ])

  .js('resources/js/nova/app.js', 'public/js/nova')
  .js('resources/js/nova/rtl.js', 'public/js/nova/rtl.js')
  .postCss('resources/css/nova/app.css', 'public/css/nova')
  .postCss('resources/css/nova/rtl.css', 'public/css/nova/rtl.css')


if (mix.inProduction()) {
  const ASSET_URL = process.env.ASSET_URL + '/';

  mix.version();

  mix.webpackConfig(webpack => {
    return {
      plugins: [
        new webpack.DefinePlugin({
          'process.env.ASSET_PATH': JSON.stringify(ASSET_URL),
        })
      ],
      output: {
        publicPath: ASSET_URL,
      },
    };
  });
}
