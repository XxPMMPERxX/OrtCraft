<template>
  <div class="flex justify-center">
    <span class="loading loading-spinner loading-md"></span>
    マインクラフト認証中...
  </div>
</template>

<script setup>
import axios from '@/axios';
import { pushAlert } from '@/composables/alert';
import { loadUserData } from '@/composables/userData';
import { useRoute, useRouter } from 'vue-router';

const router = useRouter();
const route = useRoute();
const { code } =  route.query;

/**
 * code がある場合は、認証リクエストを投げる
 * ない場合は mypage に戻る
 */
if (code) {

  axios.post('/api/minecraft-auth', { code }).then(() => {
    loadUserData();
  }).catch(() => {
    pushAlert({
      message: 'マインクラフトの認証に失敗しました。時間を空けて再度お確かめください。',
      color: 'error',
      closeable: true,
    });
  }).finally(() => {
    router.replace({
      path: '/mypage',
    });
  });

} else {

  router.replace({
    path: '/mypage',
  });

}


</script>
