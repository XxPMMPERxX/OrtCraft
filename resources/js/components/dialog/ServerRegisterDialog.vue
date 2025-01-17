<template>
  <button
    class="btn btn-primary mt-5 text-white w-full h-20"
    @click="active = true"
  >
    サーバーを登録する
    <span class="icon-[charm--plus] h-[24px] w-[24px]"></span>
  </button>

  <Dialog v-model="active">
    <h3 class="text-lg font-bold text-center my-2">サーバ登録</h3>

    <div>
      <div class="mt-5 flex flex-col gap-2">
        <input
          v-model="input.name"
          class="input input-bordered w-full"
          placeholder="サーバー名 (例. 元気ニコニコ鯖)"
        />
      </div>

      <button
        @click="register()"
        class="btn btn-accent text-white w-full mt-5"
        :disabled="loading"
      >
        <span
          class="loading loading-spinner"
          v-if="loading"
        ></span>
        登録
      </button>
    </div>
  </Dialog>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import axios from '@/axios';
import Dialog from '@/components/dialog/Dialog.vue';
import useAlert from '@/composables/useAlert';
import useServerStore from '@/composables/useServerStore';

const active = defineModel({
  default: false
});

const inputDefault = {
  name: '',
};
/**
 * 登録内容
 */
const input = ref({...inputDefault});

const loading = ref(false);

const {
  fetchServers,
} = useServerStore();

const {
  pushAlert,
} = useAlert();

const register = () => {
  loading.value = true;
  axios.post('/api/servers', input.value).then(() => {
    pushAlert({
      message: 'サーバの登録を行いました。',
      color: 'success',
      close_at: 5,
    });
    fetchServers();
  }).catch((error) => {
    const {
      message = 'サーバの登録に失敗しました。時間をおいて再度お試しください。',
    } = error.response?.data ?? undefined;
    pushAlert({
      message,
      color: 'error',
      closeable: true,
    });
  }).finally(() => {
    /**
     * ロードをストップして、ダイアログを閉じる
     * 入力をリセット
     */
    loading.value = false;
    active.value = false;
    input.value = {...inputDefault};
  });
};
</script>
