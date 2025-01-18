<template>
  <div class="navbar bg-base-100">
    <div class="navbar-start">
      <RouterLink class="btn btn-ghost text-xl" :to="{
        path: '/'
      }">
        {{ appName }}
      </RouterLink>
    </div>

    <div class="navbar-end z-[100]">
      <button
        v-if="firebaseUser" class="btn btn-sm btn-ghost btn-circle mr-5 w-10 h-10"
        @click="openNotifications()"
      >
        <div class="indicator">
          <span class="indicator-item badge badge-primary badge-xs"></span>
          <svg class="h-6 w-6"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z"/>
            <path d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" />
            <path d="M9 17v1a3 3 0 0 0 6 0v-1" />
          </svg>
        </div>
      </button>

      <RouterLink :to="{ path: '/auth' }" class="btn btn-ghost btn-circle" v-if="!firebaseUser">
        <svg class="w-6 h-6 text-gray-800"
          :class="theme === 'dark' ? 'text-white' : ''"
          aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
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
              :src="`/storage/${userData?.icon_path}`"
            />
          </div>
        </div>
        <ul
          tabindex="0"
          class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow"
        >
          <li class="text-center my-2">
            {{ userData?.name }} さん
          </li>
          <li>
            <RouterLink class="justify-between" :to="{
              path: '/mypage'
            }">
              マイページ
            </RouterLink>
          </li>
          <li>
            <RouterLink class="justify-between" :to="{
              path: '/myservers'
            }">
              サーバ管理
            </RouterLink>
          </li>
          <li>
            <a>設定</a>
          </li>
          <li>
            <a @click="signOut()">ログアウト</a>
          </li>
        </ul>
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import { useAuth } from '@/composables/useAuth';
import useUserData from '@/composables/useUserData';
import useConfirmDialog from '@/composables/useConfirmDialog';
import useTheme from '@/composables/useTheme';
import useNotificationDialog from '@/composables/useNotificationDialog';
import { RouterLink } from 'vue-router';

const {
  firebaseUser,
  signOut: _signOut
} = useAuth();

const appName = import.meta.env.VITE_APP_NAME;
const { userData } = useUserData();
const { confirm } = useConfirmDialog();
const { theme } = useTheme();

const signOut = async () => {
  const result = await confirm({
    title: 'ログアウト',
    body: 'ログアウトしてよろしいですか？',
    okLabel: 'はい',
    cancelLabel: 'やめる',
  });

  if (result) {
    _signOut();
  }
};

const {
  open: openNotifications,
} = useNotificationDialog();

</script>
