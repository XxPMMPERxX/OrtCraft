<template>
  <div class="flex justify-center">
    <span class="loading loading-spinner loading-md"></span>
    マインクラフト認証中...
  </div>
</template>

<script setup>
import apiClient from '@/apiClient';
import { loadUserData } from '@/composables/userData';
import { useRoute, useRouter } from 'vue-router';

const router = useRouter();
const route = useRoute();
const { code } =  route.query;

apiClient.post('/minecraft-auth', {
  data: {
    code,
  }
}).then(() => {
  loadUserData();
  router.replace({
    path: '/mypage',
  });
});
</script>
