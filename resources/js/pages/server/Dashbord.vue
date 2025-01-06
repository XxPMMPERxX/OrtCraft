<template>
  <div>
    <h1>Dashboard</h1>
    {{ id }}
  </div>
</template>

<script setup>
defineProps({
  id: String
});
</script>
