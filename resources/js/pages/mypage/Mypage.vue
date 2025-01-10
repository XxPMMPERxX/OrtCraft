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
            <div
              v-if="myServers.length > 0"
              class="inline-flex overflow-x-scroll rounded-box gap-2 w-full my-5 py-10 dark:bg-gray-600 border border-base-300"
            >
              <div class="p-2 first:pl-28 last:pr-28" v-for="(server,index) in myServers" :key="index">
                <div
                  class="card card-compact bg-base-100 w-96 shadow-xl hover:cursor-pointer hover:opacity-80"
                  @click="$router.push(`/server/${server.id}/dashboard`)"
                >
                  <figure>
                    <div class="w-full h-40 bg-gray-300">
                    </div>
                  </figure>

                  <div class="card-body">
                    <h2 class="card-title">
                      {{ server.name }}
                      <svg
                        v-if="server.verified_at"
                        class="h-5 w-5 text-blue-500"  fill="none" viewBox="0 0 24 24" stroke="currentColor"
                      >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                      </svg>

                      <div
                        v-else
                        class="tooltip z-10"
                        data-tip="サーバが未認証です。ダッシュボードから認証を行ってください。"
                      >
                        <svg
                          class="h-5 w-5 text-yellow-500"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"
                        >
                          <path stroke="none" d="M0 0h24v24H0z"/>
                          <circle cx="12" cy="12" r="9" />
                          <line x1="12" y1="8" x2="12" y2="12" />
                          <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                      </div>

                    </h2>
                  </div>
                </div>
              </div>
            </div>


            <!--
              サーバー登録用ダイアログ
            -->
            <ServerRegisterDialog
              @registerd:server="onServerRegisterd"
            />

            <!--
              サーバー認証用ダイアログ
            -->
            <ServerAuthDialog v-model="isShowServerAuthDialog" :server-data="serverData"/>
          </template>

          <template #tabContent.friend>
            <div class="w-full my-5">
              <div class="h-96 overflow-y-auto border rounded-box">
                <table class="table">
                  <!-- head -->
                  <thead>
                    <tr class="sticky top-0 bg-base-100 z-50">
                      <th></th>
                      <th>Name</th>
                      <th>Status</th>
                    </tr>
                    <tr>
                      <th colspan="3">
                        <div class="flex gap-2">
                          <input
                            class="input input-sm input-bordered w-4/5"
                            placeholder="フレンド内検索"
                          />
                          <button
                            class="btn btn-sm btn-accent w-auto"
                            @click="isShowSearchUserDialog = true"
                          >
                            フレンド追加
                            <span class="icon-[charm--plus]"></span>
                          </button>
                        </div>
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="friend in friends" :key="friend.id">
                      <td></td>
                      <td>
                        <div class="flex items-start gap-3">
                          <div class="avatar">
                            <div class="w-12 rounded-full">
                              <img :src="`storage/${userData?.icon_path}`" />
                            </div>
                          </div>
                          <div>
                            <div class="font-bold">
                              {{ friend.name }}
                            </div>
                            <div class="font-sm opacity-50">
                              {{ friend.comment }}
                            </div>
                          </div>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <SearchUserDialog v-model="isShowSearchUserDialog" />
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
import { onMounted, ref } from 'vue';
import axios from '@/axios';
import { useRouter } from 'vue-router';
import authorizeURI from '@/config/microsoft';
import { loadUserData, UserData, useUserData } from '@/composables/userData';
import { useAuth } from '@/composables/firebaseAuth';
import theme from '@/composables/theme';
import ServerRegisterDialog from '@/components/dialog/ServerRegisterDialog.vue';
import ServerAuthDialog from '@/components/dialog/ServerAuthDialog.vue';
import SearchUserDialog from '@/components/dialog/SearchUserDialog.vue';
import { type server } from '@/@types/server';
import { confirm } from '@/composables/confirmDialog';
import { SERVER_PLATFORM_TYPE } from '@/enums';
import { pushAlert } from '@/composables/alert';
import Tab from '@/components/Tab.vue';

const { firebaseUser } = useAuth();
const userData = useUserData();

const myServers = ref<server[]>([]);
const isShowServerAuthDialog = ref(false);
const serverData = ref<server|null>(null)

const isShowSearchUserDialog = ref(false);

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

const fetchServers = async () => {
  const response = await axios.get('/api/server', {
    params: {
      only_own: 1
    }
  });
  myServers.value = response.data.data;
};

const onServerRegisterd = (server: server) => {
  fetchServers();

  showServerAuthDialog(server);
};

const showServerAuthDialog = (server: server) => {
  isShowServerAuthDialog.value = true;
  serverData.value = server;
};

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

const friends = ref<UserData[]>([]);
const fetchFriends = async () => {
  const response = await axios.get('/api/friends');

  friends.value = response.data.data;
}

onMounted(() => {
  fetchFriends();
  fetchServers();
});
</script>
