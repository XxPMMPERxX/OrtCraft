<template>
  <div class="container [width:350px] md:[width:650px] mx-auto mt-10">
    <div class="flex flex-col gap-5">
      <div class="w-full mb-7">
        <button class="btn btn-primary text-white w-full h-20">
          サーバーを登録する
          <span class="icon-[charm--plus] h-[24px] w-[24px]"></span>
        </button>
      </div>

      <div class="divider"></div>

      <div class="w-full">
        <a
          class="btn btn-outline btn-primary"
          :href="authorizeURI"
          v-if="!userData?.minecraft_uid"
        >
          マイクラ認証
        </a>
        <p class="text-md font-bold" v-if="userData?.minecraft_uid">ゲーマータグ</p>
        <input type="text" class="w-full input input-bordered" placeholder="Steve" readonly :value="userData?.minecraft_gamertag" v-if="userData?.minecraft_uid" />
      </div>
      <div class="w-full">
        <p class="text-md font-bold">テーマ</p>
        <div class="dropdown w-full">
          <div tabindex="0" role="button" class="btn btn-neutral m-1 w-full uppercase">{{ theme }}</div>
          <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-full p-2 shadow">
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
import { useRouter } from 'vue-router';
import authorizeURI from '@/config/microsoft';
import { useUserData } from '@/composables/userData';
import { useAuth } from '@/composables/firebaseAuth';
import theme from '@/composables/theme';

const { firebaseUser } = useAuth();
const userData = useUserData();

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
</script>
