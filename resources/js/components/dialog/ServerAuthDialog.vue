<template>
  <Dialog v-model="active">
    <h3 class="text-lg font-bold text-center my-2">サーバ認証</h3>

    <ul class="steps w-full">
      <li class="step step-success">サーバ登録</li>
      <li class="step step-success">サーバ認証</li>
      <li class="step">完了</li>
    </ul>

    <div>
      <div class="flex flex-col gap-2">
        <div class="my-5">
          <p>【認証方法】</p>
          <p>
            下に表示されている認証コードをいずれかの場所に設定し、「認証」ボタンを押してください
          </p>
          <p>1. サーバー名(MOTD)</p>
        </div>
        <input
          :value="serverData?.auth_code"
          class="input input-bordered w-full"
          placeholder="認証コード"
          readonly
        />
      </div>

      <button
        @click="authServer()"
        class="btn btn-accent text-white w-full mt-5"
        :disabled="loading"
      >
        <span
          class="loading loading-spinner"
          v-if="loading"
        >
        </span>
        認証
      </button>
    </div>
  </Dialog>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import axios from '@/axios';
import Dialog from '@/components/dialog/Dialog.vue';
import { pushAlert } from '@/composables/alert';
import { type server } from '@/@types/server';

const props = defineProps<{
  serverData: server | null,
}>();

const active = defineModel({
  default: false
});

const loading = ref(false);

const authServer = () => {
  loading.value = true;
  axios.put(`/api/servers/${props.serverData?.id}/auth`).then(() => {
    pushAlert({
      message: 'サーバの登録を行いました。続けて認証を行なってください。',
      color: 'success',
      close_at: 5,
    });
  }).catch(() => {
    pushAlert({
      message: 'サーバの登録に失敗しました。時間をおいて再度お試しください。',
      color: 'error',
      close_at: 5,
    });
  }).finally(() => {
    /**
     * ロードをストップして、ダイアログを閉じる
     * 入力をリセット
     */
    loading.value = false;
    active.value = false;
  });
};
</script>
