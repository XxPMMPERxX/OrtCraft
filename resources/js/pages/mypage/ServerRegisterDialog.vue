<template>
  <button
    class="btn btn-primary text-white w-full h-20"
    :disabled="props.disabled"
    @click="active = true"
  >
    サーバーを登録する
    <span class="icon-[charm--plus] h-[24px] w-[24px]"></span>
  </button>

  <Dialog v-model="active">
    <h3 class="text-lg font-bold text-center my-2">サーバ登録</h3>

    <ul class="steps w-full">
      <li class="step step-success">サーバ登録</li>
      <li class="step">サーバ認証</li>
      <li class="step">完了</li>
    </ul>

    <div>
      <div class="flex my-5 gap-3 md:gap-10 justify-center">
        <label class="flex gap-1 md:gap-2 items-center cursor-pointer">
          <input
            v-model="input.platform"
            :value="SERVER_PLATFORM_TYPE.JAVA.value"
            type="radio"
            name="platform"
            class="radio radio-xs"
          />
          <span>
            {{ SERVER_PLATFORM_TYPE.JAVA.label }}
          </span>
        </label>
        <label class="flex gap-1 md:gap-2 items-center cursor-pointer">
          <input
            v-model="input.platform"
            :value="SERVER_PLATFORM_TYPE.BE.value"
            type="radio"
            name="platform"
            class="radio radio-xs"
          />
          <span>
            {{ SERVER_PLATFORM_TYPE.BE.label }}
          </span>
        </label>
        <label class="flex gap-1 md:gap-2 items-center cursor-pointer">
          <input
            v-model="input.platform"
            :value="SERVER_PLATFORM_TYPE.JAVA_BE.value"
            type="radio"
            name="platform"
            class="radio radio-xs"
          />
          <span>
            {{ SERVER_PLATFORM_TYPE.JAVA_BE.label }}
          </span>
        </label>
      </div>

      <div class="flex flex-col gap-2">
        <input
          v-model="input.name"
          class="input input-bordered w-full"
          placeholder="サーバー名 (例. 元気ニコニコ鯖)"
        />
        <input
          v-model="input.address"
          class="input input-bordered w-full"
          placeholder="IP (例. locahost)"
        />
        <input
          v-model="input.je_port"
          class="input input-bordered w-full"
          placeholder="Java版 PORT (例. 65535)"
        />
        <input
          v-model="input.be_port"
          class="input input-bordered w-full"
          placeholder="統合版 PORT (例. 19132)"
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
import { SERVER_PLATFORM_TYPE } from '@/enums';
import { pushAlert } from '@/composables/alert';

const props = defineProps<{
  disabled: boolean,
}>();

const emit = defineEmits(['registerd:server']);

const active = defineModel({
  default: false
});

const inputDefault = {
  name: '',
  address: '',
  platform: SERVER_PLATFORM_TYPE.JAVA.value,
  je_port: null,
  be_port: null,
};
/**
 * 登録内容
 */
const input = ref(structuredClone(inputDefault));

const loading = ref(false);

const register = () => {
  loading.value = true;
  axios.post('/api/server', input.value).then((response) => {
    pushAlert({
      message: 'サーバの登録を行いました。続けて認証を行なってください。',
      color: 'success',
      close_at: 5,
    });
    // 登録されたサーバのデータを親コンポーネントに渡す
    emit('registerd:server', response.data.data);
  }).catch(() => {
    pushAlert({
      message: 'サーバの登録に失敗しました。時間をおいて再度お試しください。',
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
    input.value = inputDefault;
  });
};
</script>
