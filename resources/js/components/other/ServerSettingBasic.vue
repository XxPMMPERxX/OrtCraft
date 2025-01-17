<template>
  <div class="flex flex-col pt-5">
    <div class="flex flex-col gap-2">
      <span class="font-bold">
        サーバー名
      </span>

      <input
        v-model="input.name"
        class="input input-bordered"
        type="text"
        placeholder="サーバー名"
      />
    </div>

    <div class="my-5 flex flex-col gap-2">
      <span class="font-bold">
        紹介文
        <span class="text-xs ml-2 font-normal text-gray-400">
          Markdown記法が利用できます
        </span>
      </span>

      <MdEditor
        v-model="input.description"
        language="ja_JP"
        :sanitize="sanitize"
      />
    </div>

    <button
      class="btn btn-info mt-2"
      :class="{ 'btn-disabled': !changed }"
      :disabled="loading"
      @click="save"
    >
      <span class="loading loading-spinner" v-if="loading"></span>
      保存
    </button>
  </div>
</template>

<script setup>
import { computed, ref, watch } from 'vue';
import { MdEditor } from 'md-editor-v3';
import sanitizeHtml from 'sanitize-html';
import axios from '@/axios';
import 'md-editor-v3/lib/style.css';
import useAlert from '@/composables/useAlert';

const model = defineModel({
  type: Object,
  required: true,
});

const input = ref({
  name: model.value.name,
  description: model.value.description ?? '',
});

const { pushAlert } = useAlert();

watch(model, () => {
  input.value = {
    name: model.value.name,
    description: model.value.description ?? '',
  };
})


const changed = computed(() => {
  return input.value.name !== model.value.name ||
    input.value.description !== (model.value.description ?? '');
});

const loading = ref(false);
const save = async () => {
  try {
    loading.value = true;
    const response = await axios.patch(`/api/servers/${model.value.id}`, {
      name: input.value.name,
      description: sanitize(input.value.description),
    });

    const {
      data,
    } = response.data;

    model.value = {
      ...model.value,
      name: data.name,
      description: data.description,
    };
  } catch (e) {
    pushAlert({
      message: 'サーバーの情報を更新できませんでした。',
      color: 'error',
      close_at: 5,
    });
  } finally {
    loading.value = false;
  }
}

const sanitize = (html) => sanitizeHtml(html);
</script>
