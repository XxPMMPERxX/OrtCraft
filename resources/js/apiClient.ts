import { useAuth } from "@/composables/firebaseAuth";

const config = async () => {
  const { firebaseUser } = useAuth();
  const authorization = await firebaseUser.value?.getIdToken() ?? '';
  const API_PREFIX = '/api';


  const defaultHeaders = new Headers({
    "Authorization": authorization,
    "Content-Type": "application/json",
  });
  return {
    API_PREFIX,
    defaultHeaders,
  }
};

export default {
  get: async (endpoint: string = '', options: {
    headers?: object,
    params?: string | Record<string, string> | string[][] | URLSearchParams | undefined,
  } = {}) => {
    const { API_PREFIX, defaultHeaders } = await config();

    const { headers, params } = options;

    const paramsString = (new URLSearchParams(params)).toString();

    const path = `${API_PREFIX}${endpoint}${paramsString ? '?' + paramsString : ''}`;

    if (headers) {
      Object.keys(headers).forEach(key => {
        defaultHeaders.append(key, headers[key]);
      });
    }

    return fetch(path, {
      headers: defaultHeaders,
    })
  },

  post: async (endpoint: string, options: {
    headers?: object,
    data?: object | string,
  } = {}) => {
    const { API_PREFIX, defaultHeaders } = await config();
    const { headers, data } = options;

    const path = `${API_PREFIX}${endpoint}`;

    if (headers) {
      Object.keys(headers).forEach(key => {
        defaultHeaders.append(key, headers[key]);
      });
    }

    return fetch(path, {
      headers: defaultHeaders,
      method: 'POST',
      body: JSON.stringify(data),
    });
  }
};
