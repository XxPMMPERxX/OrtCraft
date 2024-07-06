<template>
  <dialog class="modal" ref="dialogRef" @keydown="disableEscape">
    <div class="modal-box">
      <h3 class="text-lg font-bold" v-if="content.title">
        {{ content.title }}
      </h3>
      <p class="py-4">
        {{ content.body }}
      </p>
      <div class="modal-action">
        <button class="btn btn-block btn-outline btn-success w-1/2" @click="close(true)">
          {{ content.okLabel }}
        </button>
        <button class="btn btn-block btn-outline btn-error w-1/2" @click="close(false)">
          {{ content.cancelLabel }}
        </button>
      </div>
    </div>
  </dialog>
</template>

<script setup lang="ts">
import { close, dialogRef, content } from '@/composables/confirmDialog';

/**
 * エスケープキーでのダイアログ閉じを無効化
 */
function disableEscape(event: KeyboardEvent) {
  if (event.key === 'Escape') {
    event.preventDefault();
  }
}
</script>
