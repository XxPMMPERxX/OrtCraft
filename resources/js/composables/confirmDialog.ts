import { ref, watch, type Ref } from 'vue';

const dialogRef = ref<HTMLDialogElement|null>(null);
const result = ref<null|boolean>(null);

export { dialogRef };

export const confirm = async (): Promise<Ref<null|boolean>> => {
  result.value = null;
  dialogRef.value?.showModal();
  return new Promise((resolve) => {
    watch(result, () => {
      resolve(result);
    });
  });
};

export const close = (_result: boolean = false) => {
  dialogRef.value?.close();
  result.value = _result;
}
