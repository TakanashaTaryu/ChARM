/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./src/**/*.{html,js}"],
  theme: {
    fontFamily: {
      lexend : ['Lexend','sans-serif']
    },
    extend: {
      colors: {
        'custom_white' : '#FFFBF2',
        'bright_cream' : '#FFE2B6',
        'dark_cream' : '#FDC178',
        'bright_orange' : '#F9983B',
        'custom_orange' : '#F06800',
        'dark_orange' : '#BD4700',
        'custom_brown' : '#8B2C00',
        'dark_brown' : '#591700',
        'custom_black' : '#591700',
      },
    },
  },
  plugins: [],
}

