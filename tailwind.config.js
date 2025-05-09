/** @type {import('tailwindcss').Config} */
import daisyui from 'daisyui';
import { addDynamicIconSelectors } from '@iconify/tailwind';
import typography from '@tailwindcss/typography';

export default {
  content: [
    "./resources/**/*.ts",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {},
  },
  darkMode: ['selector', '[data-theme="dark"]'],
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
    typography,
    daisyui,
    addDynamicIconSelectors(),
  ],
}

