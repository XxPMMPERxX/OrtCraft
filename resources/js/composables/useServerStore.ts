import { ref } from 'vue';
import axios from '@/axios';
import { server } from '@/@types/server';

const servers = ref<server[]>([]);
const loading = ref(false);

export default function useServerStore() {
  const fetchServers = async (params = {}) => {
    try {
      loading.value = true;

      servers.value = [];

      const response = await axios.get('/api/servers', params);
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
