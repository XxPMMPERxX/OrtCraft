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
  daisyui: {
    themes: [
      "light",
      "dark",
      "cupcake",
      "nord",
      "winter",
      "fantasy",
      "emerald",
    ],
  },
  plugins: [
    daisyui,
  ],
}

