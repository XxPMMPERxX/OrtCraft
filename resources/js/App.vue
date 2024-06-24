<template>
  <Navbar />
  <div class="container mx-auto">
    <RouterView />
  </div>
</template>

<script setup>
import Navbar from '@/components/Navbar.vue';
</script>
