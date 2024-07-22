<template>
  <transition-group name="alert" tag="ul" class="alert-wrapper md:w-1/2 w-full">
    <li v-for="alert in alerts" :key="alert.key" class="my-2 w-full">
      <div role="alert" class="alert flex justify-between" :class="getAlertColorClass(alert.color)" >
        <div class="flex gap-1.5 items-center">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            class="stroke-current h-6 w-6 shrink-0">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <span>
            {{ alert.message }}
          </span>
        </div>

        <button class="btn btn-sm btn-ghost" v-if="alert.closeable" @click="closeAlert(alert)">
          <span class="icon-[ic--baseline-close]" style="width: 20px; height: 20px;"></span>
        </button>
      </div>
    </li>
  </transition-group>
</template>

<script setup lang="ts">
import { alerts, closeAlert } from '@/composables/alert';

function getAlertColorClass(color: string = 'default') {
  let className: string | null | undefined = null;

  switch (color) {
    case 'default':
      className = '';
      break;
    case 'info':
      className = 'alert-info';
      break;
    case 'success':
      className = 'alert-success';
      break;
    case 'warning':
      className = 'alert-warning';
      break;
    case 'error':
      className = 'alert-error';
      break;
  }

  return className;
}
</script>

<style>
.alert-wrapper {
  position: absolute;
  right: 0;
  bottom: 0;
  z-index: 1000000;
  padding: 20px;
  overflow: hidden;
}

.alert-move, /* 移動する要素にトランジションを適用 */
.alert-enter-active,
.alert-leave-active {
  transition: all 0.8s ease;
}

.alert-enter-from,
.alert-leave-to {
  opacity: 0;
  transform: translateX(50%);
}

/* leave する項目をレイアウトフローから外すことで
   アニメーションが正しく計算されるようになる */
.alert-leave-active {
  position: absolute;
}
</style>
