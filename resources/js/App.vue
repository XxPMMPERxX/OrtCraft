<template>
  <div>
    <Navbar />
    <div class="container mx-auto">
      <RouterView />
    </div>
    <ConfirmDialog />
    <NotificationListDialog />
    <Altert />
  </div>
</template>

<script setup>
import { watch } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '@/composables/useAuth';
import useTheme from './composables/useTheme';
import ConfirmDialog from './components/dialog/ConfirmDialog.vue';
import Altert from './components/alert/Altert.vue';
import Navbar from '@/components/Navbar.vue';
import useUserData from './composables/useUserData';
import useAlert from './composables/useAlert';
import NotificationListDialog from './components/dialog/NotificationListDialog.vue';
import useNotificationDialog from './composables/useNotificationDialog';

const { userData } = useUserData();
const { firebaseUser } = useAuth();
const router = useRouter();
const { pushAlert } = useAlert();
const {
  checkHasNewNotification,
  open: openNotifications,
} = useNotificationDialog();

/**
 * ログアウト時ログイン画面に遷移
 */
watch(firebaseUser, () => {
  if (!firebaseUser.value) {
    userData.value = null;
    router.push({
      path: '/auth'
    });
  }
});

watch(userData, async () => {
  if (userData.value) {
    window.Echo.connector.options.auth.headers.Authorization = await firebaseUser.value.getIdToken();

    window.Echo.private(`App.Models.User.${userData.value.id}`)
      .notification((notification) => {
        // console.log(notification);
        checkHasNewNotification();

        pushAlert({
          message: notification.title,
          color: 'info',
          close_at: 10,
          onClick: () => {
            openNotifications(notification.id)
          },
        });
      });
  } else {
    window.Echo.leaveAllChannels();
  }
}, { immediate: true });

const {
  theme,
} = useTheme();
// テーマ更新毎にセット
watch(theme, () => {
  document.querySelector('html').dataset.theme = theme.value;
},
{
  immediate: true,
});

const autoCheckNotification = () => {
  if (firebaseUser.value) {
    checkHasNewNotification();
  }
};

// setInterval(autoCheckNotification, 60 * 1000);

autoCheckNotification();
</script>
