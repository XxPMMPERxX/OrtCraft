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

      <div class="dropdown dropdown-end" v-if="firebaseUser">
        <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
          <div class="w-10 rounded-full">
            <img
              alt="User Icon"
              :src="userIcon" />
          </div>
        </div>
        <ul
          tabindex="0"
          class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
          <li>
            <RouterLink class="justify-between" :to="{
              path: '/mypage'
            }">
              mypage
            </RouterLink>
          </li>
          <li><a>Settings</a></li>
          <li><a @click="signOut()">Logout</a></li>
        </ul>
      </div>

    </div>
  </div>
</template>
<script setup lang="ts">
import { toSvg } from 'jdenticon';
import { useAuth } from '@/composables/firebaseAuth';
import { useUserData } from '@/composables/userData';

const {
  firebaseUser,
  signOut
} = useAuth();

const appName = import.meta.env.VITE_APP_NAME;
const userData = useUserData();

const userIcon =
  userData.value?.icon ?? URL.createObjectURL(
    new Blob(
      [toSvg(userData.value?.id ?? 'ortcraft', 32)],
      { type: 'image/svg+xml' }
    )
  );
</script>
