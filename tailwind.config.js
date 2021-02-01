module.exports = {
    purge: [
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    darkMode: false, // or 'media' or 'class'
    theme: {
        extend: {
            colors: { //Will override the color pattern completly nce I have idea about the whole cutom pattern
                'johrh-dark-header': '#231F20',
                'johrh-gold': '#B39155',
                'johrh-gray': '#F5F5F5'
              }
        }
    },
    variants: {
        extend: {},
    },
    plugins: [],
}
