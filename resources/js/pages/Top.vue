<template>
  <div class="container [width:350px] md:[width:760px] mx-auto my-10">
    <Tab>

      <template #tabTitle.friend>
        お気に入り
      </template>
      <template #tabTitle.server>
        全てのサーバ
      </template>

      <template #tabContent.friend>
        TODO
      </template>
      <template #tabContent.server>
        TODO
      </template>
    </Tab>
  </div>
</template>

<script setup lang="ts">
import Tab from '@/components/Tab.vue';
</script>
