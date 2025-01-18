import { ref } from 'vue';
import axios from '@/axios';

const isShowNotificationDialog = ref(false);
const highlightId = ref<number|null>(null);

const loading = ref(false);

const notifications = ref([]);

const paginate = ref({
  page: 1,
  items_per_page: 5,
  total: 0,
  max_page: 1,
});

const hasNewNotification = ref(false);

export default function useNotificationDialog() {
  const open = (id = null) => {
    isShowNotificationDialog.value = true;
    highlightId.value = id;
  };

  const fetchNotifications = async () => {
    loading.value = true;
    // notifications.value = [];
    const response = await axios.get('/api/notifications', {
      params: {
        page: paginate.value.page,
        items_per_page: paginate.value.items_per_page,
      }
    });
    loading.value = false;
    notifications.value = response.data.data;

    const { meta } = response.data;
    paginate.value = {
      page: meta.current_page,
      items_per_page: meta.per_page,
      total: meta.total,
      max_page: meta.last_page,
    };
  }

  const checkHasNewNotification = () => {
    axios.get('/api/check-notification')
      .then(({ data }) => {
        hasNewNotification.value = data.data.has_new_notification;
      });
  };

  return {
    open,
    checkHasNewNotification,
    fetchNotifications,
    loading,
    hasNewNotification,
    notifications,
    paginate,
    highlightId,
    isShowNotificationDialog,
  }
};
