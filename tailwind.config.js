/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./node_modules/tw-elements/dist/js/**/*.js"
  ],
  theme: {
    extend: {},
    container: {
      center: true,
    }
  },
  darkMode: "class",
  plugins: [require("tw-elements/dist/plugin.cjs")]
}

