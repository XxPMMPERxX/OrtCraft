import { ref } from 'vue';
import { useAuth } from './firebaseAuth';
import axios from '@/axios';

interface UserData {
  id: string,
  icon: string,
  name: string,
  icon_path: string | null,
  comment: string | null,
  description: string | null,
  is_verified_minecraft: boolean,
  minecraft_be_uid: string | null,
  minecraft_be_gamertag: string | null,
  minecraft_java_uid: string | null,
  minecraft_java_gamertag: string | null,
};

const userData = ref<UserData|null>(null);
const { firebaseUser, signOut } = useAuth();

export const loadUserData = async () => {
  try {
    const response = await axios.get('/api/auth');
    const { data } = response.data
    userData.value = data;
  } catch (e) {
    signOut();
  }
};

export const useUserData = () => {
  return userData;
};

if (firebaseUser.value) {
  await loadUserData();
}

export type { UserData };
