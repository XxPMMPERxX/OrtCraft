import { ref } from 'vue';

const theme = ref('light');

if (localStorage.getItem('theme')) {
  theme.value = localStorage.getItem('theme') ?? 'light';
}

export default function useTheme() {
  const setTheme = (_theme: string) => {
    theme.value = _theme;
    localStorage.setItem('theme', _theme);
  };

  return {
    setTheme,
    theme,
  };
}
