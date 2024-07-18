import axios, { AxiosError } from "axios";
import { useAuth } from "@/composables/firebaseAuth";

axios.interceptors.request.use(async (request) => {
  const { firebaseUser } = useAuth();
  const authorization = await firebaseUser.value?.getIdToken() ?? '';
  request.headers.Authorization = authorization;

  return request;
});

axios.interceptors.response.use(
  (response) => response,
  (error: AxiosError) => {
    const { signOut } = useAuth();
    switch (error.response?.status) {
      case 401:
        signOut();
        break;
    }
  }
);

export default axios;
