<template>
  Top

  {{ firebaseUser }}
</template>

<script setup lang="ts">
import Navbar from '@/components/Navbar.vue';
import { useAuth } from '@/composables/firebaseAuth';

const { firebaseUser } = useAuth();
</script>
