import { ref, watch } from 'vue';

interface Content {
  title?: string,
  body?: string,
  okLabel?: string,
  cancelLabel?: string,
};

const dialogRef = ref<HTMLDialogElement|null>(null);
const result = ref<null|boolean>(null);
const content = ref<Content>({
  okLabel: 'ok',
  cancelLabel: 'cancel'
});
const confirm = async (_content?: Content): Promise<null|boolean> => {
  result.value = null;
  dialogRef.value?.showModal();
  if (_content) {
    content.value = { ...content.value, ..._content };
  }
  return new Promise((resolve) => {
    watch(result, () => {
      resolve(result.value);
    });
  });
};

const close = (_result: boolean = false) => {
  dialogRef.value?.close();
  result.value = _result;
};

export { dialogRef, confirm, close, content };
