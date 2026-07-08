import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
                brand: ['Manrope', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    red: '#E50D25',
                    'red-hover': '#CC0B20',
                    menu: '#697586',
                    'menu-muted': '#D9D9D9',
                    divider: '#DBE0EC',
                    dark: '#1C1C1C',
                    'dark-muted': '#2E2E2E',
                    surface: '#F5F6F8',
                    pink: '#FFF5F5',
                    'input-bg': '#EFF4F8',
                    green: '#3D9A50',
                },
            },
            boxShadow: {
                'header-cta': '0px 4px 8px -2px rgba(0, 0, 0, 0.1), 0px 2px 4px -2px rgba(0, 0, 0, 0.06)',
                'card': '0px 8px 24px -4px rgba(0, 0, 0, 0.08)',
                'tariff': '0px 4px 16px rgba(0, 0, 0, 0.06)',
            },
        },
    },

    plugins: [forms],
};
