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
        sans: ['Figtree', ...defaultTheme.fontFamily.sans],
      },
      colors: {
        // Warna tema SMP Utama berdasarkan logo
        primary: {
          50: '#eff6ff',
          100: '#dbeafe',
          200: '#bfdbfe',
          300: '#93c5fd',
          400: '#60a5fa',
          500: '#0033A0', // Biru utama dari logo
          600: '#002d8a',
          700: '#002673',
          800: '#001f5c',
          900: '#001845',
        },
        secondary: {
          50: '#fef2f2',
          100: '#fee2e2',
          200: '#fecaca',
          300: '#fca5a5',
          400: '#f87171',
          500: '#DC143C', // Merah dari logo
          600: '#c41230',
          700: '#a01027',
          800: '#7c0d1f',
          900: '#5f0a18',
        },
        accent: {
          yellow: '#FFD700', // Kuning dari logo
          green: '#00A86B', // Hijau dari logo
        },
      },
      backgroundImage: {
        'gradient-primary': 'linear-gradient(135deg, #0033A0 0%, #002d8a 100%)',
        'gradient-rainbow': 'linear-gradient(135deg, #DC143C 0%, #FFD700 25%, #00A86B 50%, #0033A0 100%)',
      },
      boxShadow: {
        'primary': '0 4px 14px 0 rgba(0, 51, 160, 0.39)',
        'secondary': '0 4px 14px 0 rgba(220, 20, 60, 0.39)',
      },
    },
  },
  plugins: [forms],
};