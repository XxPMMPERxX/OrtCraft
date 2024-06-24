<template>
  <div class="navbar bg-base-100">
    <div class="navbar-start">
      <RouterLink class="btn btn-ghost text-xl" :to="{
        path: '/'
      }">
        {{ appName }}
      </RouterLink>
    </div>

    <div class="navbar-end">
      <RouterLink :to="{ path: '/auth' }" class="btn btn-ghost btn-circle" v-if="!firebaseUser">
        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
          width="24" height="24" fill="none" viewBox="0 0 24 24">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M16 12H4m12 0-4 4m4-4-4-4m3-4h2a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3h-2" />
        </svg>
      </RouterLink>

      <div class="tooltip tooltip-bottom" data-tip="サインアウト">
        <button class="btn btn-ghost btn-circle" v-if="firebaseUser" @click="signOut()">
          <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M20 12H8m12 0-4 4m4-4-4-4M9 4H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h2" />
          </svg>
        </button>
      </div>

    </div>
  </div>
</template>
<script setup lang="ts">
import { useAuth } from '@/composables/firebaseAuth';

const {
  firebaseUser,
  signOut
} = useAuth();

const appName = import.meta.env.VITE_APP_NAME;
</script>
