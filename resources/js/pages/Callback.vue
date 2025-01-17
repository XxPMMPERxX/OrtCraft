<template>
  <div class="flex justify-center">
    <span class="loading loading-spinner loading-md"></span>
    マインクラフト認証中...
  </div>
</template>

<script setup>
import axios from '@/axios';
import useAlert from '@/composables/useAlert';
import useUserData from '@/composables/useUserData';
import { useRoute, useRouter } from 'vue-router';

const router = useRouter();
const route = useRoute();
const { loadUserData } = useUserData();
const { code } =  route.query;
const { pushAlert } = useAlert();

/**
 * code がある場合は、認証リクエストを投げる
 * ない場合は mypage に戻る
 */
if (code) {
  axios.post('/api/minecraft-auth', { code }).then(() => {
    pushAlert({
      message: 'マインクラフトアカウントを連携しました。',
      color: 'success',
      close_at: 5,
    });
    loadUserData();
  }).catch((error) => {
    const {
      message = 'マインクラフトの認証に失敗しました。時間を空けて再度お確かめください。',
    } = error.response?.data ?? undefined;

    pushAlert({
      message,
      color: 'error',
      close_at: 5,
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
