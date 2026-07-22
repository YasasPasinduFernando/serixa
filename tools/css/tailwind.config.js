/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    '../../framework/src/**/*.php',
    '../../packages/*/src/**/*.php',
    '../../website/**/*.php',
    '../../starters/**/*.php',
    '../../playground/**/*.php',
    '../../validation/**/*.php',
    './safelist.txt',
  ],
  safelist: require('./safelist.json'),
  darkMode: 'class',
  theme: { extend: {} },
  plugins: [],
}
