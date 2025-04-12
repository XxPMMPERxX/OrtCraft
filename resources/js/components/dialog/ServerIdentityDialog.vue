<template>
  <Dialog v-model="active">
    <h3 class="text-lg font-bold text-center mb-5">サーバ接続情報追加</h3>

    <div class="flex justify-center">
      <ul class="steps my-5">
        <li class="step step-primary">追加</li>
        <li class="step">認証</li>
        <li class="step">完了</li>
      </ul>
    </div>

    <div>
      <div class="font-bold text-sm my-2">
        <p>ホスト名は必須</p>
        <p>ポート番号はどちらかは必須</p>
      </div>

      <div class="flex flex-col gap-2">
        <input
          v-model="input.address"
          class="input input-bordered w-full"
          placeholder="ラベル"
        />

        <input
          v-model="input.address"
          class="input input-bordered w-full"
          placeholder="ホスト名 (例. 192.168.1.1)"
        />

        <input
          v-model="input.je_port"
          class="input input-bordered w-full"
          placeholder="JEポート (例. 65535)"
        />

        <input
          v-model="input.be_port"
          class="input input-bordered w-full"
          placeholder="BEポート (例. 19132)"
        />
      </div>

      <button
        @click="addIdentity()"
        class="btn btn-accent text-white w-full mt-5"
        :disabled="loading"
      >
        <span
          class="loading loading-spinner"
          v-if="loading"
        >
        </span>
        追加
      </button>
    </div>
  </Dialog>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import axios from '@/axios';
import Dialog from '@/components/dialog/Dialog.vue';
import useAlert from '@/composables/useAlert';
import type server from '@/@types/server';

const props = defineProps<{
  serverData: server | null,
}>();

const active = defineModel({
  default: false
});

const loading = ref(false);

const { pushAlert } = useAlert();

const input = ref({
  address: '',
  je_port: '',
  be_port: '',
});

const addIdentity = () => {
  loading.value = true;
  axios.post(`/api/servers/${props.serverData?.id}/register-identity`, {
    ...input.value,
  }).then(() => {
    pushAlert({
      message: '接続情報の追加を行いました。続けて認証を行なってください。',
      color: 'success',
      close_at: 5,
    });
  }).catch(() => {
    pushAlert({
      message: '接続情報の追加に失敗しました。時間をおいて再度お試しください。',
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
