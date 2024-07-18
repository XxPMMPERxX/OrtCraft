import { ref } from 'vue';
import { useAuth } from './firebaseAuth';
import axios from '@/axios';

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
  try {
    const response = await axios.post('/api/auth');
    const { data } = response.data
    userData.value = data;
  } catch (e) {
    //
  }
};

export const useUserData = () => {
  return userData;
};

if (firebaseUser.value) {
  await loadUserData();
}
