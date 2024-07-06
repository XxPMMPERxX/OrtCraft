import { ref, watchEffect } from 'vue'

const theme = ref('light');

if (localStorage.getItem('theme')) {
  theme.value = localStorage.getItem('theme') ?? 'light';
}
watchEffect(() => {
  localStorage.setItem('theme', theme.value)
});

export default theme;
