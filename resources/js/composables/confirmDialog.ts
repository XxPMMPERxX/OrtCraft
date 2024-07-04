import { ref, watch, type Ref } from 'vue';

const active = ref(false);
const _result = ref(false);

export { active };

export const confirm = async (): Promise<Ref<boolean>> => {
  active.value = true;
  _result.value = false;
  return new Promise((resolve) => {
    watch(active, () => {
      resolve(_result);
    });
  });
};

export const close = (result: boolean = false) => {
  _result.value = result;
  active.value = false;
}
