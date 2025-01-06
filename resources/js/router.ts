import { createRouter, createWebHistory } from 'vue-router';
import TopPage from './pages/Top.vue';
import AuthPage from './pages/auth/Auth.vue';
import CallbackPage from './pages/callback/Callback.vue';
import MyPage from './pages/mypage/Mypage.vue';
import Dashbord from './pages/server/Dashbord.vue';

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
    path: '/server/:id/dashboard',
    component: Dashbord,
    props: true,
  },
];
const router = createRouter({
  routes,
  history: createWebHistory(),
})
export default router;
