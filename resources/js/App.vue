<template>
  <div data-theme="emerald" style="height: 100%;">
    <Navbar />
    <div class="container mx-auto">
      <RouterView />
    </div>
  </div>
</template>

<script setup>
import { watch } from 'vue';
import { useRouter } from 'vue-router';
import Navbar from '@/components/Navbar.vue';
import { useAuth } from '@/composables/firebaseAuth';

const { firebaseUser } = useAuth();
const router = useRouter();

/**
 * ログアウト時にトップに戻るように
 */
watch(firebaseUser, () => {

  if (!firebaseUser.value) {
    router.push({
      path: '/'
    });
  }
});
</script>
