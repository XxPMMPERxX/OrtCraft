<template>
  <dialog ref="modal" class="modal" @keydown="disableEscape">
    <div class="modal-box">
      <slot></slot>
    </div>
    <form method="dialog" class="modal-backdrop">
      <button @click.stop="active = false">close</button>
    </form>
  </dialog>
</template>

<script setup lang="ts">
import { watchEffect, ref } from 'vue';
const active = defineModel();

const modal = ref<HTMLDialogElement|null>(null);

watchEffect(() => {
  /**
   * props.activeが有効の場合はダイアログを表示
   */
  if (active.value) {
    modal.value?.showModal();
  } else {
    modal.value?.close();
  }
});

/**
 * エスケープキーでのダイアログ閉じをキャプチャ
 * active 経友で閉じる
 */
 function disableEscape(event: KeyboardEvent) {
  if (event.key === 'Escape') {
    active.value = false;
    event.preventDefault();
  }
}
</script>
