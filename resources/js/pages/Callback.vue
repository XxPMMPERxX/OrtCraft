<template>
  <div class="flex justify-center">
    <span class="loading loading-spinner loading-md"></span>
    マインクラフト認証中...
  </div>
</template>

<script setup>
import axios from '@/axios';
import { loadUserData } from '@/composables/userData';
import { useRoute, useRouter } from 'vue-router';

const router = useRouter();
const route = useRoute();
const { code } =  route.query;

axios.post('/api/minecraft-auth', { code }).then(() => {
  loadUserData();
  router.replace({
    path: '/mypage',
  });
});
</script>
