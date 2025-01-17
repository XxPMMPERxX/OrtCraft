<template>
  <div>
    <div class="w-full my-5">
      <div class="h-96 overflow-y-auto border rounded-box">
        <table class="table">
          <!-- head -->
          <thead>
            <tr class="sticky top-0 bg-base-100 z-50">
              <th></th>
              <th>Name</th>
              <th>Status</th>
            </tr>
            <tr>
              <th colspan="3">
                <div class="flex gap-2">
                  <input
                    v-model="searchUserName"
                    class="input input-sm input-bordered w-4/5"
                    placeholder="フレンド内検索"
                  />

                  <button class="btn btn-sm btn-accent w-auto" @click="isShowSearchUserDialog = true">
                    フレンド追加
                    <span class="icon-[charm--plus]"></span>
                  </button>
                </div>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="friend in filteredFriends" :key="friend.id">
              <td></td>
              <td>
                <div class="flex items-start gap-3">
                  <div class="avatar">
                    <div class="w-12 rounded-full">
                      <img :src="`storage/${friend?.icon_path}`" />
                    </div>
                  </div>
                  <div>
                    <div class="font-bold">
                      {{ friend.name }}
                    </div>
                    <div class="font-sm opacity-50">
                      {{ friend.comment }}
                    </div>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <SearchUserDialog v-model="isShowSearchUserDialog" />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import SearchUserDialog from '@/components/dialog/SearchUserDialog.vue';
import useFriendStore from '@/composables/useFriendStore';


const isShowSearchUserDialog = ref(false);
const {
  fetchFriends,
  friends,
} = useFriendStore();

const searchUserName = ref('');
const filteredFriends = computed(() => {
  if (!searchUserName.value) {
    return friends.value;
  }

  return friends.value.filter((friend) => {
    return friend.name.includes(searchUserName.value);
  });
});

onMounted(() => {
  fetchFriends();
});
</script>
