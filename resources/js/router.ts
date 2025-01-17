import { createRouter, createWebHistory } from 'vue-router';
import TopPage from './pages/Top.vue';
import AuthPage from './pages/Auth.vue';
import CallbackPage from './pages/Callback.vue';
import MyPage from './pages/MyPage.vue';
import ServerSetting from './pages/ServerSetting.vue';
import ServerDashboard from './pages/ServerDashboard.vue';

const routes = [
  {
    path: '/',
    component: TopPage,
  },
  {
    path: '/auth',
    component: AuthPage,
  },
  {
    path: '/mypage',
    component: MyPage,
  },
  {
    path: '/callback',
    component: CallbackPage,
  },
  {
    name: 'serverDashboard',
    path: '/server/:id/dashboard',
    component: ServerDashboard,
    props: true,
  },
  {
    name: 'serverSetting',
    path: '/server/:id/settings',
    component: ServerSetting,
    props: true,
  },
];
const router = createRouter({
  routes,
  history: createWebHistory(),
})
export default router;
