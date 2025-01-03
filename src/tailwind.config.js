/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
        screens: {
            '-sm': {max: '639px'},
            '-md': {max: '767px'},
            '-lg': {max: '1023px'}
        }
    },
  },
  plugins: [require('tailwindcss-primeui')],
}

