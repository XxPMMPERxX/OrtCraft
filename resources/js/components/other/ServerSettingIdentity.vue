<template>
  <div class="flex flex-col">
    <div class="flex flex-col items-center gap-5 pt-10">
      <div class="flex w-3/4 px-4">
        <div class="w-20 text-center">使用中</div>
        <div class="w-full text-center"></div>
      </div>

      <div
        v-for="identity in serverData.identities"
        :key="identity.id"
        class="flex w-3/4 px-4 py-2"
      >
        <div class="flex justify-between w-full">
          <div class="flex flex-col items-center w-20">
            <input
              type="radio"
              name="activeIdentity"
              class="radio radio-primary"
              :checked="identity === serverData.identity"
            />
          </div>
          <div class="w-full flex items-center justify-between">
            {{ identity.label }}

            <div class="flex gap-2">
              <div v-if="identity.je_port" class="badge badge-neutral">
                {{ identity.address }}:{{ identity.je_port }}
              </div>
              <div v-if="identity.be_port" class="badge badge-neutral">
                {{ identity.address }}:{{ identity.be_port }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <button
        class="btn btn-primary w-full"
        @click="isShowIdentityDialog = true"
      >
        接続情報追加
      </button>
    </div>

    <ServerIdentityDialog
      v-model="isShowIdentityDialog"
      v-model:server-data="serverData"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import ServerIdentityDialog from '../dialog/ServerIdentityDialog.vue';


const serverData = defineModel();
const isShowIdentityDialog = ref(false);
</script>
