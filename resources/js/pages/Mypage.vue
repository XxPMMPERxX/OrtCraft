<template>
  <div>
    <a
      class="btn btn-outline btn-primary"
      :href="authorizeURI"
      v-if="!userData?.minecraft_uid"
    >
      マイクラ認証
    </a>
    <div>{{ userData?.minecraft_gamertag }}</div>
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
