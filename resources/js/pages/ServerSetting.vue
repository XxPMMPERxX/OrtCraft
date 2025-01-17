<template>
  <div v-if="server" class="container [width:350px] md:[width:760px] mx-auto my-10">
    <h1 class="text-2xl font-bold mb-5">
      {{ server?.name }} 設定
    </h1>

    <RouterLink
      :to="{ name: 'serverDashboard', params: { id: server?.id } }"
      class="link link-primary"
    >
      ダッシュボードに移動する
    </RouterLink>

    <Tab class="mt-5">
      <template #tabTitle.basic>
        基本
      </template>

      <template #tabTitle.identity>
        接続情報
      </template>

      <template #tabTitle.member>
        メンバー
      </template>


      <template #tabContent.basic>
        <ServerSettingBasic v-if="server" v-model="server" />
      </template>

      <template #tabContent.identity>
        <ServerSettingIdentity v-if="server" />
      </template>

      <template #tabContent.member>
        <ServerSettingMember v-if="server" v-model="server" />
      </template>
    </Tab>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from '@/axios';
import ServerSettingBasic from '@/components/other/ServerSettingBasic.vue';
import ServerSettingIdentity from '@/components/other/ServerSettingIdentity.vue';
import ServerSettingMember from '@/components/other/ServerSettingMember.vue';
import Tab from '@/components/Tab.vue';
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
