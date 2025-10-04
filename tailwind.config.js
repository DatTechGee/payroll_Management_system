/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    50: '#f0f9ff',
                    100: '#e0f2fe',
                    200: '#bae6fd',
                    300: '#7dd3fc',
                    400: '#38bdf8',
                    500: '#0ea5e9',
                    600: '#0284c7',
                    700: '#0369a1',
                    800: '#075985',
                    900: '#0c4a6e',
                },
            },
            spacing: {
                '72': '18rem',
                '84': '21rem',
                '96': '24rem',
            },
            animation: {
                'fade-in': 'fadeIn 0.5s ease-in-out',
                'slide-in': 'slideIn 0.5s ease-in-out',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideIn: {
                    '0%': { transform: 'translateX(-100%)' },
                    '100%': { transform: 'translateX(0)' },
                },
            },
            fontSize: {
                'xxs': '0.625rem',
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio'),
        function({ addComponents }) {
            addComponents({
                '.action-button': {
                    '@apply inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200 ease-in-out gap-1.5': {}
                },
                '.action-button-edit': {
                    '@apply inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200 ease-in-out gap-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100': {}
                },
                '.action-button-view': {
                    '@apply inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200 ease-in-out gap-1.5 bg-gray-50 text-gray-600 hover:bg-gray-100': {}
                },
                '.action-button-delete': {
                    '@apply inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200 ease-in-out gap-1.5 bg-red-50 text-red-600 hover:bg-red-100': {}
                }
            })
        }
    ],
}