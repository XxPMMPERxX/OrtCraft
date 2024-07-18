<template>
  <div :data-theme="theme" style="height: 100%;">
    <Navbar />
    <div class="container mx-auto">
      <RouterView />
    </div>
    <ConfirmDialog />
  </div>
</template>

<script setup>
import { watch } from 'vue';
import { useRouter } from 'vue-router';
import Navbar from '@/components/Navbar.vue';
import { useAuth } from '@/composables/firebaseAuth';
import ConfirmDialog from './components/dialog/ConfirmDialog.vue';
import theme from './composables/theme';

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
