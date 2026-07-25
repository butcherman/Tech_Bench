/** @type {import('tailwindcss').Config} */
// TODO - Is this file necessary??
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  plugins: [require('tailwindcss-primeui')],
}

