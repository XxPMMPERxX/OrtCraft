/** @type {import('tailwindcss').Config} */
import daisyui from 'daisyui';

export default {
  content: [
    "./resources/**/*.ts",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {},
  },
  plugins: [
    daisyui,
  ],
}

