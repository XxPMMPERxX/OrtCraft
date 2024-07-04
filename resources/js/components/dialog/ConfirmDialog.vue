<template>
  <dialog class="modal" :open="active">
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
import { close, active } from '@/composables/confirmDialog';

</script>
