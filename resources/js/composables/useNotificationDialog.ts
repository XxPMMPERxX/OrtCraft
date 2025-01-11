import { ref } from 'vue';

const isShowNotificationDialog = ref(false);
const highlightId = ref<number|null>(null);

export default function useNotificationDialog() {
  const open = (id = null) => {
    isShowNotificationDialog.value = true;
    highlightId.value = id;
  };

  return {
    open,
    highlightId,
    isShowNotificationDialog,
  }
};
