<template>
  <div class="container [width:350px] md:[width:650px] mx-auto mt-10">
    <div class="flex flex-col gap-5">
      <div class="w-full mb-7">
        <Tab>
          <template #tabTitle.server>
            サーバー
          </template>
          <template #tabTitle.friend>
            フレンド
          </template>

          <template #tabContent.server>
            <MyPageServerList />
          </template>

          <template #tabContent.friend>
            <MyPageFriendList />
          </template>
        </Tab>
      </div>

      <div class="divider"></div>

      <div class="flex gap-5">
        <a
          v-if="!userData?.minecraft_be_gamertag"
          class="btn btn-outline btn-primary"
          :href="authorizeURI"
        >
          統合版連携
        </a>
        <button
          v-else
          class="btn btn-active"
          @click="confirmCancelMinecraftAuth(SERVER_PLATFORM_TYPE.BE.value)"
        >
          統合版連携済み
        </button>

        <a
          v-if="!userData?.minecraft_java_gamertag"
          class="btn btn-disabled"
          href="#"
        >
          java版連携
        </a>
        <button
          v-else
          class="btn btn-active"
          @click="confirmCancelMinecraftAuth(SERVER_PLATFORM_TYPE.JAVA.value)"
        >
          java版連携済み
        </button>
      </div>

      <div class="mt-5 mb-36 w-full">
        <p class="text-md font-bold">テーマ</p>
        <div class="dropdown dropdown-top w-full">
          <div tabindex="0" role="button" class="btn btn-neutral m-1 w-full uppercase">{{ theme }}</div>
          <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[10001] w-full p-2 shadow">
            <li v-for="_theme in themes" :key="_theme">
              <a @click="setTheme(_theme)" :class="theme === _theme ? 'bg-accent' : ''">
                <span class="uppercase">
                  {{ _theme }}
                </span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
import axios from '@/axios';
import { useRouter } from 'vue-router';
import authorizeURI from '@/config/microsoft';
import { loadUserData, useUserData } from '@/composables/userData';
import { useAuth } from '@/composables/firebaseAuth';
import theme from '@/composables/theme';
import { confirm } from '@/composables/confirmDialog';
import { SERVER_PLATFORM_TYPE } from '@/enums';
import { pushAlert } from '@/composables/alert';
import Tab from '@/components/Tab.vue';
import MyPageServerList from '@/components/other/MyPageServerList.vue';
import MyPageFriendList from '@/components/other/MyPageFriendList.vue';

const { firebaseUser } = useAuth();
const userData = useUserData();


const themes = [
  "light",
  "dark",
  "cupcake",
  "nord",
  "winter",
  "fantasy",
  "emerald",
];

if (!firebaseUser.value) {
  const router = useRouter();
  router.replace({
    path: '/'
  });
}

const setTheme = (_theme: string) => {
  theme.value = _theme;
}


const confirmCancelMinecraftAuth = async (platform) => {
  const gamertag = platform === SERVER_PLATFORM_TYPE.BE.value
    ? userData.value?.minecraft_be_gamertag
    : userData.value?.minecraft_java_gamertag;

  const content = {
    title: `連携中 [${gamertag}]`,
    body: `連携を解除しますか？`,
    okLabel: '解除する',
    cancelLabel: 'キャンセル'
  }

  if (await confirm(content)) {
    axios.delete('/api/minecraft-auth/cancel', {
      params: {
        platform
      }
    }).then(() => {
      pushAlert({
        message: '連携を解除しました。',
        color: 'success',
        close_at: 5,
      });
      loadUserData();
    });
  }
}
</script>
