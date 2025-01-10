import { ref } from 'vue';

const isShowNotificationDialog = ref(false);
const highlightId = ref<number|null>(null);

export default function useNotificationDialog() {
  const open = (id = null) => {
    isShowNotificationDialog.value = true;
    console.log(id);
    highlightId.value = id;
  };

  return {
    open,
    highlightId,
    isShowNotificationDialog,
  }
};
