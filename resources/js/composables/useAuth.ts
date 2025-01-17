import {
  getAuth,
  signOut as firebaseSignOut,
  type User,
  signInWithEmailAndPassword,
  AuthErrorCodes,
  connectAuthEmulator,
} from 'firebase/auth'
import { ref } from 'vue'
import firebaseApp from '@/config/firebase';
import { FirebaseError } from 'firebase/app';
import axios from '@/axios';

const firebaseUser = ref<User | null>(null);
const errorText = ref('');

const auth = getAuth(firebaseApp);
if (import.meta.env.DEV) {
  connectAuthEmulator(auth, "http://127.0.0.1:9099");
}

const initUser = () => {
  return new Promise<User | null>((resolve) => {
    const unsubscribe = auth.onAuthStateChanged((user) => {
      resolve(user);
      unsubscribe();
    });
  });
}
firebaseUser.value = await initUser();

auth.onAuthStateChanged((user) => {
  firebaseUser.value = user;
});

export const useAuth = () => {

  const signUp = async (username: string, email: string, password: string) => {
    errorText.value = '';
    await axios.post('/api/user/register', {
      username,
      email,
      password,
    });
  }

  const signIn = async (email: string, password: string) => {
    errorText.value = '';
    try {
      const userCredential = await signInWithEmailAndPassword(
        auth,
        email,
        password
      );

      if (!userCredential.user.emailVerified) {
        errorText.value = 'メールアドレスの確認が完了していません。受信メールをご確認ください。';
        await signOut();
        return false;
      }

      firebaseUser.value = userCredential.user;
      return true;
    } catch (e) {
      if (e instanceof FirebaseError) {

        if (e.code === AuthErrorCodes.INVALID_LOGIN_CREDENTIALS) {
          errorText.value = 'メールアドレスまたはパスワードが間違っています';
        }
      }
      await signOut();
      return false;
    }

  }

  const signOut = async () => {
    await firebaseSignOut(auth);
    firebaseUser.value = null;
  };


  return {
    signIn,
    signUp,
    signOut,
    errorText,
    firebaseUser,
  }
}
