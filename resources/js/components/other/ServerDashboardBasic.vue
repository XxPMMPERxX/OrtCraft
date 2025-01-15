<template>
  <div class="flex flex-col">
    <input
      v-model="input.name"
      class="input my-5"
      type="text"
      placeholder="サーバー名"
    />

    <MdEditor
      v-model="input.description"
      language="ja_JP"
      :sanitize="sanitize"
    />

    <button
      class="btn btn-info mt-8"
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
import { computed, ref } from 'vue';
import { MdEditor } from 'md-editor-v3';
import sanitizeHtml from 'sanitize-html';
import axios from '@/axios';
import 'md-editor-v3/lib/style.css';
import { pushAlert } from '@/composables/alert';

const model = defineModel({
  type: Object,
  required: true,
});

const input = ref({
  name: model.value.name,
  description: model.value.description ?? '',
});

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
      description: input.value.description,
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
