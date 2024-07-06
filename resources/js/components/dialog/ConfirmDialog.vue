<template>
  <dialog class="modal" ref="dialogRef" @keydown="disableEscape">
    <div class="modal-box">
      <h3 class="text-lg font-bold">Hello!</h3>
      <p class="py-4">Press ESC key or click the button below to close</p>
      <div class="modal-action">
        <button class="btn btn-success" @click="close(true)">YES</button>
        <button class="btn btn-error" @click="close(false)">NO</button>
      </div>
    </div>
  </dialog>
</template>

<script setup lang="ts">
import { close, dialogRef } from '@/composables/confirmDialog';

/**
 * エスケープキーでのダイアログ閉じを無効化
 */
function disableEscape(event: KeyboardEvent) {
  if (event.key === 'Escape') {
    event.preventDefault();
  }
}
</script>
