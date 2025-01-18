<template>
  <Dialog v-model="isShowNotificationDialog" max-width-class="max-w-3xl">
    <h3 class="text-lg font-bold text-center my-2">
      通知一覧
    </h3>

    <div class="text-center my-5">
      <span v-if="loading" class="loading loading-dots loading-md"></span>
    </div>

    <table class="table table-lg">
      <tbody>
        <tr
          v-for="notification in notifications" :key="notification.id"
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
                v-if="notification.type === NOTIFICATION_TYPE.FRIENDREQUEST.value"
                class="btn btn-sm btn-accent"
                :class="{ 'btn-disabled': loadingNotificationAction === notification.id }"
                @click="approveFriendRequest(notification.id)"
              >
                フレンド承認
                <span
                  v-if="loadingNotificationAction === notification.id"
                  class="loading loading-spinner"
                >
                </span>
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-if="!loading" class="flex justify-center my-2">
      <div v-if="paginate.max_page <= 5" class="join">
        <button
          v-for="pageNum in paginate.max_page" :key="pageNum"
          class="join-item btn"
          :class="{ 'btn-active': pageNum === paginate.page }"
          @click="selectPage(pageNum)"
        >
          {{ pageNum }}
        </button>
      </div>

      <div v-else class="join">
        <button
          class="join-item btn"
          :class="{ 'btn-disabled': paginate.page - 1 < 1 }"
          @click="selectPage(paginate.page - 1)"
        >
          «
        </button>

        <button
          class="join-item btn"
        >
          Page {{ paginate.page }}
        </button>

        <button
          class="join-item btn"
          :class="{ 'btn-disabled': paginate.page + 1 > paginate.max_page }"
          @click="selectPage(paginate.page + 1)"
        >
          »
        </button>
      </div>
    </div>
  </Dialog>
</template>

<script setup>
import axios from '@/axios';
import Dialog from './Dialog.vue';
import { ref, watch } from 'vue';
import useNotificationDialog from '@/composables/useNotificationDialog';
import { NOTIFICATION_TYPE } from '@/enums';
import useFriendStore from '@/composables/useFriendStore';
import useAlert from '@/composables/useAlert';

const {
  pushAlert,
} = useAlert();

const {
  fetchNotifications,
  checkHasNewNotification,
  loading,
  notifications,
  paginate,
  isShowNotificationDialog,
  highlightId,
} = useNotificationDialog();


const selectPage = (pageNum) => {
  if (pageNum < 1 || pageNum > paginate.value.max_page) {
    return;
  }

  paginate.value.page = pageNum;
  fetchNotifications();
}

watch(isShowNotificationDialog, () => {
  if (isShowNotificationDialog.value) {
    paginate.value.page = 1;
    fetchNotifications().then(checkHasNewNotification);

    setTimeout(() => {
      highlightId.value = null;
    }, 3000);
  }
})

const {
  fetchFriends,
} = useFriendStore();
const loadingNotificationAction = ref(null);
const approveFriendRequest = async (notificationId) => {
  try {
    loadingNotificationAction.value = notificationId;

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
    loadingNotificationAction.value = null;
  }
}
</script>
