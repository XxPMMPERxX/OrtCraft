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
              <th>Role</th>
            </tr>
            <tr>
              <th colspan="3">
                <div class="flex gap-2">
                  <input
                    v-model="searchUserName"
                    class="input input-sm input-bordered w-4/5"
                    placeholder="メンバー内検索"
                  />

                  <button class="btn btn-sm btn-accent w-auto" @click="() => {}">
                    メンバー追加
                    <span class="icon-[charm--plus]"></span>
                  </button>
                </div>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="member in model.members" :key="member.id">
              <td>
              </td>
              <td>
                <div class="flex items-center gap-3">
                  <div class="avatar">
                    <div class="w-12 rounded-full">
                      <img :src="`/storage/${member?.icon_path}`" />
                    </div>
                  </div>
                  <div>
                    <div class="font-bold">
                      <div v-if="member.id === userData.id" class="badge badge-ghost">You</div>
                      {{ member.name }}
                    </div>
                  </div>
                </div>
              </td>

              <td class="text-center">
                <div
                  class="badge text-white"
                  :class="{
                    'badge-error': member.pivot.user_role == SERVER_MEMBER_ROLE.OWNER.value,
                    'badge-primary': member.pivot.user_role == SERVER_MEMBER_ROLE.ADMIN.value,
                  }"
                >
                  {{ getRoleName(member.pivot.user_role) }}
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import useUserData from '@/composables/useUserData';
import { SERVER_MEMBER_ROLE } from '@/enums';
import { ref } from 'vue';

const { userData } = useUserData();
const model = defineModel({
  type: Object,
  required: true,
});

const searchUserName = ref('');

const getRoleName = (role) => {
  switch (role) {
    case SERVER_MEMBER_ROLE.OWNER.value:
      return SERVER_MEMBER_ROLE.OWNER.label;
    case SERVER_MEMBER_ROLE.ADMIN.value:
      return SERVER_MEMBER_ROLE.ADMIN.label;
    default:
      return '';
  }
}
</script>
