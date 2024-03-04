/** @type {import('tailwindcss').Config} */
export const content = [
  "./resources/**/*.blade.php",
  "./resources/**/*.js",
  "./resources/**/*.vue",
  './resources/**/*.css',
  './resources/**/*.svg',
];
export const theme = {
  extend: {},
};
export const plugins = [
  // require('tailwind-scrollbar'),
];
export const variants = {
  backgroundCOlor: ['dark', 'dark-hover', 'dark-group-hover', 'dark-even', 'dark-odd'],
};
export const darkMode = 'class';