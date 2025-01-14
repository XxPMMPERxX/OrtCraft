<template>
  <Dialog v-model="active">
    <h3 class="text-lg font-bold text-center my-2">ユーザ検索</h3>

    <input
      v-model="name"
      class="input input-sm input-bordered w-full"
      placeholder="ユーザ名"
    />

    <div class="mt-3 h-80 overflow-y-auto border rounded-box">
      <table class="table">
        <thead>
          <tr class="sticky top-0 bg-base-100 z-50">
            <th class="text-center">ユーザ一覧</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in userList" :key="user.id">
            <td class="flex justify-between items-center">
              {{ user.name }}
              <button
                v-if="user.is_friend"
                class="btn btn-sm btn-disabled"
                disabled
              >
                フレンド済
              </button>

              <button
                v-else
                @click="sendFriendRequest(user)"
                class="btn btn-sm btn-accent"
              >
                フレンド申請
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </Dialog>
</template>

<script setup>
import { ref, watch } from 'vue';
import axios from '@/axios';
import Dialog from './Dialog.vue';
import { pushAlert } from '@/composables/alert';

const active = defineModel({
  default: false,
});

const name = ref('');
const userList = ref([]);

const sendFriendRequest = async (user) => {
  try {
    await axios.post('/api/send-friend-request', {
      to: user.id,
    });
  } catch (e) {
    const {
      message = 'フレンド申請に失敗しました',
    } = e.response?.data ?? undefined;

    pushAlert({
      color: 'error',
      message,
      close_at: 10,
    });
  }

};

let timer = null;
watch(name, () => {
  if (timer) {
    clearTimeout(timer);
  }

  timer = setTimeout(() => {
    axios.get('/api/users', {
      params: {
        name: name.value,
        ignore_self: 1,
      },
    }).then((res) => {
      timer = null;
      userList.value = res.data.data;
    })
  }, 500);
});

watch(active, () => {
  if (!active.value) {
    name.value = '';
    userList.value = [];
  }
});
</script>
