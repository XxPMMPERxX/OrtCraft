import { ref } from 'vue';
import axios from '@/axios';
import server from '@/@types/server';

const servers = ref<server[]>([]);
const loading = ref(false);

export default function useServerStore() {
  const fetchServers = async () => {
    try {
      loading.value = true;

      servers.value = [];

      const response = await axios.get('/api/servers', { params: { only_own: 1} });
      servers.value = response.data.data;
    } catch (error) {
      console.error(error);
    } finally {
      loading.value = false;
    }
  };

  return {
    fetchServers,
    servers,
  }
}
