<template>
  Top
  <button @click="addAlert()" class="btn">test</button>
</template>

<script setup lang="ts">
import { pushAlert, type Alert } from '@/composables/alert';

function addAlert() {
  const colors = [
    'default',
    'info',
    'success',
    'error',
    'warning',
  ];

  // 配列からランダムに選ぶためのインデックス
  const randomIndex = Math.floor(Math.random() * 4);

  // ランダムに選ばれた seasons
  const color = colors[randomIndex];
  const alert: Alert = {
    message: 'test',
    color,
    closeable: false,
    close_at: 10,
  };
  pushAlert(alert);
}
</script>
