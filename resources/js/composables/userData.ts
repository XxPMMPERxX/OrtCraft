import { ref } from 'vue';
import { useAuth } from './firebaseAuth';
import apiClient from '@/apiClient';

interface UserData {
  id: string,
  icon: string,
  name: string,
  minecraft_uid: string | null,
  minecraft_gamertag: string | null,
};

const userData = ref<UserData|null>(null);
const { firebaseUser } = useAuth();

export const loadUserData = async () => {
  const response = await apiClient.post('/auth');
  const { data } = await response.json();
  userData.value = data;
};

export const useUserData = () => {
  return userData;
};

if (firebaseUser.value) {
  await loadUserData();
}
