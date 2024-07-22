<template>
  <Suspense>
    <div :data-theme="theme" style="height: 100%;position: relative;">
      <Navbar />
      <div class="container mx-auto">
        <RouterView />
      </div>
      <ConfirmDialog />
      <Altert />
    </div>
  </Suspense>
</template>

<script setup>
import { watch } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '@/composables/firebaseAuth';
import theme from './composables/theme';
import ConfirmDialog from './components/dialog/ConfirmDialog.vue';
import Altert from './components/alert/Altert.vue';
import Navbar from '@/components/Navbar.vue';

const { firebaseUser } = useAuth();
const router = useRouter();

/**
 * ログアウト時ログイン画面に遷移
 */
watch(firebaseUser, () => {
  if (!firebaseUser.value) {
    router.push({
      path: '/auth'
    });
  }
});
</script>
