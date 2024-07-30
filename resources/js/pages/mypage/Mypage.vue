<template>
  <div class="container [width:350px] md:[width:650px] mx-auto mt-10">
    <div class="flex flex-col gap-5">
      <div class="w-full mb-7">
        <div class="carousel rounded-box overflow-x-scroll gap-2 max-w-full mb-5">
          <div class="carousel-item p-2" v-for="(server,index) in myServers" :key="index">
            <div class="card card-compact bg-base-100 w-40 shadow-xl">
              <figure>
                <img
                  src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp"
                  alt="Shoes" />
              </figure>
              <div class="card-body">
                <h2 class="card-title">{{ server.name }}</h2>
                <div class="card-actions justify-end">
                  <button class="btn btn-primary">Buy Now</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <ServerRegisterDialog
          :disabled="!userData?.is_verified_minecraft"
          @registerd:server="onServerRegisterd"
        />
      </div>

      <div class="divider"></div>

      <div class="w-full">
        <div v-if="userData?.is_verified_minecraft">
          <p class="text-md font-bold">ゲーマータグ</p>
          <input
            type="text"
            class="w-full input input-bordered"
            placeholder="Steve"
            readonly
            :value="userData?.minecraft_gamertag"
          />
        </div>
        <div v-else>
          <a
            class="btn btn-outline btn-primary"
            :href="authorizeURI"
          >
            マイクラ認証
          </a>
        </div>
      </div>
      <div class="w-full">
        <p class="text-md font-bold">テーマ</p>
        <div class="dropdown w-full">
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
import { ref } from 'vue';
import axios from '@/axios';
import { useRouter } from 'vue-router';
import authorizeURI from '@/config/microsoft';
import { useUserData } from '@/composables/userData';
import { useAuth } from '@/composables/firebaseAuth';
import theme from '@/composables/theme';
import ServerRegisterDialog from '@/pages/mypage/ServerRegisterDialog.vue';

// import ServerAuthDialog from './ServerAuthDialog.vue';

const { firebaseUser } = useAuth();
const userData = useUserData();

const myServers = ref([]);

const themes = [
  'light',
  'dark',
  'cupcake',
  'nord'
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
  const response = await axios.get('/api/server');
  myServers.value = response.data.data;
};

const onServerRegisterd = async () => {
  await fetchServers();
};

fetchServers();
</script>
