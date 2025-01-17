<template>
  <div v-if="server" class="container [width:350px] md:[width:760px] mx-auto my-10">
    <h1 class="text-2xl font-bold mb-5">
      {{ server?.name }} ダッシュボード
    </h1>

    <RouterLink
      :to="{ name: 'serverSetting', params: { id: server?.id } }"
      class="link link-primary"
    >
      設定に移動する
    </RouterLink>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from '@/axios';
import useAlert from '@/composables/useAlert';

const props = defineProps({
  id: String
});

const { pushAlert } = useAlert();
const router = useRouter();
const server = ref(null);
onMounted(() => {
  axios.get(`/api/servers/${props.id}`).then(({ data }) => {
    server.value = data.data;
  })
  .catch(() => {
    pushAlert({
      message: 'サーバーが見つかりませんでした。',
      color: 'error',
    });
    router.replace('/');
  });
});
</script>
