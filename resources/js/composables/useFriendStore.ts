import { ref } from 'vue';
import axios from '@/axios';
import { UserData } from './useUserData';

const friends = ref<UserData[]>([]);
const loading = ref(false);

export default function useFriendStore() {
  const fetchFriends = async (params = {}) => {
    try {
      loading.value = true;

      friends.value = [];

      const response = await axios.get('/api/friends', params);
      friends.value = response.data.data;
    } catch (error) {
      console.error(error);
    } finally {
      loading.value = false;
    }
  };

  return {
    fetchFriends,
    friends,
  }
}
