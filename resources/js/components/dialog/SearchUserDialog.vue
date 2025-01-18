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

              <template v-if="purpose === 'friend'">
                <button
                  v-if="user.is_friend"
                  class="btn btn-sm btn-disabled"
                  disabled
                >
                  フレンド済
                </button>

                <button
                  v-else-if="user.already_sent_friend_request"
                  class="btn btn-sm btn-disabled"
                  disabled
                >
                  フレンド申請済
                </button>

                <button
                  v-else
                  @click="sendFriendRequest(user)"
                  class="btn btn-sm btn-accent"
                >
                  フレンド申請
                </button>
              </template>

              <template v-else>
                <button
                  v-if="user.already_sent_member_invitation"
                  class="btn btn-sm btn-disabled"
                  disabled
                >
                  メンバー招待済
                </button>

                <button
                  v-else
                  @click="sendMemberInvitation(user)"
                  class="btn btn-sm btn-accent"
                >
                  メンバー招待
                </button>
              </template>
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
import useAlert from '@/composables/useAlert';

const active = defineModel({
  default: false,
});

const props = defineProps({
  purpose: {
    type: String,
    default: 'friend', /// friend or member
  },
  server_id: {
    type: String,
  },
});

const name = ref('');
const userList = ref([]);
const { pushAlert } = useAlert();

let timer = null;

const fetchUsers = () => {
  axios.get('/api/users', {
    params: {
      name: name.value,
      for_friend: props.purpose === 'friend' ? 1 : 0,
      for_member: props.purpose === 'member' ? 1 : 0,
      ignore_self: 1,
    },
  }).then((res) => {
    timer = null;
    userList.value = res.data.data;
  });
};

watch(name, () => {
  if (timer) {
    clearTimeout(timer);
  }

  timer = setTimeout(fetchUsers, 500);
});

watch(active, () => {
  if (!active.value) {
    name.value = '';
    userList.value = [];
  }
});

const sendFriendRequest = async (user) => {
  try {
    await axios.post('/api/send-friend-request', {
      to: user.id,
    });
    pushAlert({
      color: 'success',
      message: 'フレンド申請を送信しました',
      close_at: 10,
    });
    fetchUsers();
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

const sendMemberInvitation = async (user) => {
  try {
    await axios.post('/api/send-member-invitation', {
      to: user.id,
      server_id: props.server_id,
    });
    pushAlert({
      color: 'success',
      message: 'メンバー招待を送信しました',
      close_at: 10,
    });
    fetchUsers();
  } catch (e) {
    const {
      message = 'メンバー招待に失敗しました',
    } = e.response?.data ?? undefined;

    pushAlert({
      color: 'error',
      message,
      close_at: 10,
    });
  }
};
</script>
