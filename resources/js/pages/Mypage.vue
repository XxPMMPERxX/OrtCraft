<template>
  <div class="container md:[width:450px] mx-auto mt-10">
    <a
      class="btn btn-outline btn-primary"
      :href="authorizeURI"
      v-if="!userData?.minecraft_uid"
    >
      マイクラ認証
    </a>
    <label
      v-if="userData?.minecraft_uid"
      class="input input-bordered flex items-center gap-2"
    >
      ゲーマータグ
      <input type="text" class="grow" placeholder="Steve" readonly :value="userData?.minecraft_gamertag" />
    </label>
  </div>
</template>

<script setup lang="ts">
import { useAuth } from '@/composables/firebaseAuth';
import { useRouter } from 'vue-router';
import authorizeURI from '@/config/mincrosoft';
import { useUserData } from '@/composables/userData';

const { firebaseUser } = useAuth();
const userData = useUserData();

if (!firebaseUser.value) {
  const router = useRouter();
  router.replace({
    path: '/'
  });
}
</script>
