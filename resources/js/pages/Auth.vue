<template>
  <div>
    <Tab>
      <template #tabTitle.signin>
        サインイン
      </template>
      <template #tabTitle.signup>
        サインアップ
      </template>

      <template #tabContent.signin>
        <form @submit.prevent class="md:w-1/2 mt-10 md:mx-auto mx-5 flex flex-col gap-2">
          <label class="input input-bordered flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 opacity-70">
              <path
                d="M2.5 3A1.5 1.5 0 0 0 1 4.5v.793c.026.009.051.02.076.032L7.674 8.51c.206.1.446.1.652 0l6.598-3.185A.755.755 0 0 1 15 5.293V4.5A1.5 1.5 0 0 0 13.5 3h-11Z" />
              <path
                d="M15 6.954 8.978 9.86a2.25 2.25 0 0 1-1.956 0L1 6.954V11.5A1.5 1.5 0 0 0 2.5 13h11a1.5 1.5 0 0 0 1.5-1.5V6.954Z" />
            </svg>
            <input type="text" class="grow" placeholder="Email" v-model="input.email" />
          </label>
          <label class="input input-bordered flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 opacity-70">
              <path fill-rule="evenodd"
                d="M14 6a4 4 0 0 1-4.899 3.899l-1.955 1.955a.5.5 0 0 1-.353.146H5v1.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2.293a.5.5 0 0 1 .146-.353l3.955-3.955A4 4 0 1 1 14 6Zm-4-2a.75.75 0 0 0 0 1.5.5.5 0 0 1 .5.5.75.75 0 0 0 1.5 0 2 2 0 0 0-2-2Z"
                clip-rule="evenodd" />
            </svg>
            <input type="password" class="grow" placeholder="Password" autocomplete="current-password"
              v-model="input.password" />
          </label>
          <button class="btn btn-neutral w-full mt-10" @click="signIn()" :disabled="isLoading">
            <span class="loading loading-spinner" v-if="isLoading"></span>
            サインイン
          </button>
        </form>
      </template>

      <template #tabContent.signup>
        <form @submit.prevent class="md:w-1/2 mt-10 md:mx-auto mx-5 flex flex-col gap-2">
          <label class="input input-bordered flex items-center gap-2">
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
              width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
              <path fill-rule="evenodd"
                d="M12 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4h-4Z"
                clip-rule="evenodd"
              />
            </svg>
            <input type="text" class="grow" placeholder="Username" autocomplete="username" v-model="input.username" />
          </label>

          <label class="input input-bordered flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 opacity-70">
              <path
                d="M2.5 3A1.5 1.5 0 0 0 1 4.5v.793c.026.009.051.02.076.032L7.674 8.51c.206.1.446.1.652 0l6.598-3.185A.755.755 0 0 1 15 5.293V4.5A1.5 1.5 0 0 0 13.5 3h-11Z" />
              <path
                d="M15 6.954 8.978 9.86a2.25 2.25 0 0 1-1.956 0L1 6.954V11.5A1.5 1.5 0 0 0 2.5 13h11a1.5 1.5 0 0 0 1.5-1.5V6.954Z" />
            </svg>
            <input type="text" class="grow" placeholder="Email" v-model="input.email" />
          </label>

          <label class="input input-bordered flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 opacity-70">
              <path fill-rule="evenodd"
                d="M14 6a4 4 0 0 1-4.899 3.899l-1.955 1.955a.5.5 0 0 1-.353.146H5v1.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2.293a.5.5 0 0 1 .146-.353l3.955-3.955A4 4 0 1 1 14 6Zm-4-2a.75.75 0 0 0 0 1.5.5.5 0 0 1 .5.5.75.75 0 0 0 1.5 0 2 2 0 0 0-2-2Z"
                clip-rule="evenodd" />
            </svg>
            <input type="password" class="grow" placeholder="Password" autocomplete="off" v-model="input.password" />
          </label>
          <button class="btn btn-neutral w-full mt-10" @click="signUp" :disabled="isLoading">
            <span class="loading loading-spinner" v-if="isLoading"></span>
            サインアップ
          </button>
        </form>
      </template>
    </Tab>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import Navbar from '@/components/Navbar.vue';
import Tab from '@/components/Tab.vue';
import { useAuth } from '@/composables/firebaseAuth';
import apiClient from '@/apiClient';
import router from '@/router';


const { firebaseUser, signIn: _signIn, signUp: _signUp } = useAuth();
const isLoading = ref(false);
const input = ref({
  username: '',
  email: '',
  password: '',
});

if (firebaseUser.value) {
  router.push({
    path: '/'
  });
}

const signIn = async () => {
  const { email, password } = input.value;

  isLoading.value = true;
  await _signIn(email, password);
  await apiClient.post('/auth');
  isLoading.value = false;

  router.push({
    path: '/'
  });
};

const signUp = async () => {
  const { username, email, password } = input.value;

  isLoading.value = true;
  await _signUp(email, password);
  await apiClient.post('/auth', {
    data: { username },
  });
  isLoading.value = false;

  router.push({
    path: '/'
  });
}

</script>
