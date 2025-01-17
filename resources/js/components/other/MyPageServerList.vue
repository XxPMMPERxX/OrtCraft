<template>
  <div>
    <div v-if="servers.length > 0"
      class="inline-flex overflow-x-scroll rounded-box gap-2 w-full my-5 py-10 dark:bg-gray-600 border border-base-300">
      <div class="p-2 first:pl-28 last:pr-28" v-for="(server, index) in servers" :key="index">
        <div class="card card-compact bg-base-100 w-96 shadow-xl hover:cursor-pointer hover:opacity-80"
          @click="$router.push({ name: 'serverDashboard', params: { id: server.id } })">
          <figure>
            <div class="w-full h-40 bg-gray-300">
            </div>
          </figure>

          <div class="card-body">
            <h2 class="card-title">
              {{ server.name }}
              <svg v-if="server.identity?.is_verify" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
              </svg>

              <div v-else class="tooltip z-10" data-tip="サーバが未認証です。ダッシュボードから認証を行ってください。">
                <svg class="h-5 w-5 text-yellow-500" width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                  stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" />
                  <circle cx="12" cy="12" r="9" />
                  <line x1="12" y1="8" x2="12" y2="12" />
                  <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
              </div>
            </h2>
          </div>
        </div>
      </div>
    </div>

    <!--
      サーバー登録用ダイアログ
    -->
    <ServerRegisterDialog />
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import ServerRegisterDialog from '@/components/dialog/ServerRegisterDialog.vue';
import useServerStore from '@/composables/useServerStore';

const {
  fetchServers,
  servers,
} = useServerStore();


onMounted(() => {
  fetchServers();
});
</script>
