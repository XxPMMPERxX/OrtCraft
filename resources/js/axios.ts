import axios, { AxiosError } from "axios";
import { useAuth } from "@/composables/firebaseAuth";
import { pushAlert } from "./composables/alert";

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
        pushAlert({
          message: '認証エラー: 再度ログインを行なってください',
          color: 'error',
          closeable: false,
        });
        signOut();
        break;
    }
  }
);

export default axios;
