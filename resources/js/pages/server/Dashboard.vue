<template>
  <div class="container [width:350px] md:[width:760px] mx-auto mt-10">
    <Tab>
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
        <ServerDashboardBasic v-if="server" v-model="server" />
      </template>

      <template #tabContent.identity>
        <ServerDashboardIdentity v-if="server" />
      </template>

      <template #tabContent.member>
        <ServerDashboardMember v-if="server" />
      </template>
    </Tab>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import axios from '@/axios';
import ServerDashboardBasic from '@/components/other/ServerDashboardBasic.vue';
import ServerDashboardIdentity from '@/components/other/ServerDashboardIdentity.vue';
import ServerDashboardMember from '@/components/other/ServerDashboardMember.vue';
import Tab from '@/components/Tab.vue';

const props = defineProps({
  id: String
});

const server = ref(null)

onMounted(() => {
  axios.get(`/api/servers/${props.id}`).then(({ data }) => {
    server.value = data.data;
  });
})
</script>
