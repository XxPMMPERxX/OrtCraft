<template>
  <tr
    class="duration-1000"
    :class="{ 'bg-orange-100': notification.id === highlightId }"
  >
    <td class="flex items-center justify-between">
      <div class="flex flex-col gap-2">
        {{ notification.data.title }}
        <span class="text-sm text-gray-400">
          {{ notification.created_at }}
        </span>
      </div>

      <div class="w-1/4 text-center">
        <button
          v-if="notification.type === NOTIFICATION_TYPE.FriendRequest.value"
          class="btn btn-sm btn-accent"
          :class="{ 'btn-disabled': loadingNotificationAction }"
          @click="approveFriendRequest(notification.id)"
        >
          フレンド承認
          <span
            v-if="loadingNotificationAction"
            class="loading loading-spinner"
          >
          </span>
        </button>

        <button
          v-else-if="notification.type === NOTIFICATION_TYPE.MemberInvitation.value"
          class="btn btn-sm btn-accent"
          :class="{ 'btn-disabled': loadingNotificationAction }"
          @click="approveMemberInvitation(notification.id)"
        >
          メンバ招待承認
          <span
            v-if="loadingNotificationAction"
            class="loading loading-spinner"
          >
          </span>
        </button>
      </div>
    </td>
  </tr>
</template>

<script setup>
import { ref } from 'vue';
import axios from '@/axios';
import useFriendStore from '@/composables/useFriendStore';
import useAlert from '@/composables/useAlert';
import { NOTIFICATION_TYPE } from '@/enums';
import useNotificationDialog from '@/composables/useNotificationDialog';
import useServerStore from '@/composables/useServerStore';

defineProps({
  notification: {
    type: Object,
    required: true,
  },
});

const {
  fetchNotifications,
  highlightId,
} = useNotificationDialog();

const {
  fetchFriends,
} = useFriendStore();
const loadingNotificationAction = ref(false);

const { pushAlert } = useAlert();

const approveFriendRequest = async (notificationId) => {
  try {
    loadingNotificationAction.value = true;

    await axios.post('/api/approve-friend-request', {
      notificationId,
    });
    fetchNotifications();
    fetchFriends();
  } catch (error) {
    const {
      message = 'フレンドの承認に失敗しました',
    } = error.response?.data ?? undefined;
    pushAlert({
      message,
      color: 'error',
      closeable: true,
    });
    fetchNotifications();
  } finally {
    loadingNotificationAction.value = false;
  }
}

const {
  fetchServers,
} = useServerStore();
const approveMemberInvitation = async (notificationId) => {
  try {
    loadingNotificationAction.value = true;

    await axios.post('/api/approve-member-invitation', {
      notificationId,
    });
    fetchNotifications();
    fetchServers();
  } catch (error) {
    const {
      message = 'サーバ招待の承認に失敗しました',
    } = error.response?.data ?? undefined;
    pushAlert({
      message,
      color: 'error',
      closeable: true,
    });
    fetchNotifications();
  } finally {
    loadingNotificationAction.value = false;
  }
};
</script>
