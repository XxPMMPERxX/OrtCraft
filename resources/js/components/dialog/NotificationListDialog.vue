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
        <NotificationItem
          v-for="notification in notifications" :key="notification.id"
          :notification="notification"
        />
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
import Dialog from './Dialog.vue';
import { watch } from 'vue';
import useNotificationDialog from '@/composables/useNotificationDialog';
import NotificationItem from '../other/NotificationItem.vue';

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
</script>
