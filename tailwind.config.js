/** @type {import('tailwindcss').Config} */
export default {
  content: [
    'resources/**/*.blade.php',
    'resources/**/*.js'
  ],
  theme: {
    extend: {
      colors: {
        black: '#113040' 
      },
      fontSize: {
        '2xs':'0.625rem'
      }
    },
  },
  plugins: [],
}

