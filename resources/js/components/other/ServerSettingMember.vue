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

                  <button class="btn btn-sm btn-accent w-auto" @click="isOpenSearchUserDialog = true">
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
                    'badge-warning': member.pivot.user_role == SERVER_MEMBER_ROLE.OWNER.value,
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

    <div class="mt-24">
      <button
        v-if="userData.id === owner.id"
        class="btn btn-error text-white w-full"
        @click="deleteServer()"
      >
        サーバ削除
      </button>

      <button
        v-else
        class="btn btn-error text-white w-full"
      >
        サーバ脱退
      </button>
    </div>

    <SearchUserDialog
      v-model="isOpenSearchUserDialog"
      purpose="member"
      :server_id="model.id"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from '@/axios';
import useUserData from '@/composables/useUserData';
import SearchUserDialog from '@/components/dialog/SearchUserDialog.vue';
import { SERVER_MEMBER_ROLE } from '@/enums';
import useConfirmDialog from '@/composables/useConfirmDialog';
import useAlert from '@/composables/useAlert';
import { useRouter } from 'vue-router';

const isOpenSearchUserDialog = ref(false);

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

const owner = model.value.members.find((member) => member.pivot.user_role === SERVER_MEMBER_ROLE.OWNER.value);

const {
  confirm,
} = useConfirmDialog();
const {
  pushAlert,
} = useAlert();
const router = useRouter();
const deleteServer = async () => {
  const isConfirmed = await confirm({
    title: 'サーバ削除',
    body: 'サーバを削除してよろしいですか？（この操作は取り消せません）',
  });

  if (!isConfirmed) return;

  try {
    await axios.delete(`/api/servers/${model.value.id}`);
    pushAlert({
      message: 'サーバを削除しました',
      color: 'success',
      close_at: 5,
    });
    router.replace({
      path: '/myservers',
    });
  } catch (error) {
    const {
      message = 'サーバの削除に失敗しました',
    } = error.response?.data ?? undefined;

    pushAlert({
      message,
      color: 'error',
      close_at: 5,
    });
  }
};
</script>
